<?php

namespace App\Http\Controllers;

use App\Enums\SchoolDay;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Display the student portal dashboard.
     */
    public function dashboard(Request $request): View
    {
        $student = $this->student($request);

        $student->load('course');

        $grades = $student->grades()->with(['subject', 'teacher'])->get();

        return view('portal.dashboard', [
            'student' => $student,
            'gradeCount' => $grades->count(),
            'averageGrade' => $grades->isEmpty() ? null : $grades->avg('grade'),
            'passedCount' => $grades->filter(fn (Grade $grade): bool => $grade->isPassed())->count(),
            'scheduleCount' => $student->schedules()->count(),
        ]);
    }

    /**
     * Display the student's complete grade history.
     */
    public function grades(Request $request): View
    {
        $student = $this->student($request);

        $gradeGroups = $student->grades()
            ->with(['subject', 'teacher'])
            ->get()
            ->groupBy('academic_year')
            ->map(fn ($byYear) => $byYear->groupBy('semester'));

        return view('portal.grades', [
            'student' => $student,
            'gradeGroups' => $gradeGroups,
        ]);
    }

    /**
     * Display the student's assigned class schedule.
     */
    public function schedule(Request $request): View
    {
        $student = $this->student($request);

        $dayOrder = array_flip(SchoolDay::options());
        $schedules = $student->schedules()
            ->with(['subject', 'teacher'])
            ->get()
            ->sortBy(fn ($schedule) => $dayOrder[$schedule->day] * 1000 + substr($schedule->start_time, 0, 2) * 60 + substr($schedule->start_time, 3, 2))
            ->groupBy('day');

        return view('portal.schedule', [
            'student' => $student,
            'dayOrder' => $dayOrder,
            'schedulesByDay' => $schedules,
        ]);
    }

    /**
     * Resolve the authenticated student profile.
     */
    private function student(Request $request): Student
    {
        return $request->user()->student ?? abort(403, 'No student profile is linked to your account.');
    }
}
