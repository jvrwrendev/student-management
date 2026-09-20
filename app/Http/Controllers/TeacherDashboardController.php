<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = $request->user()->teacher;

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'scheduleCount' => $teacher->schedules()->count(),
            'gradeCount' => $teacher->grades()->count(),
            'studentCount' => $teacher->schedules()->distinct()->count('student_id'),
            'recentSchedules' => $teacher->schedules()->with(['student', 'subject'])->latest()->limit(5)->get(),
        ]);
    }
}
