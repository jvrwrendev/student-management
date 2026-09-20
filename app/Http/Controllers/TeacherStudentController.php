<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class TeacherStudentController extends Controller
{
    /**
     * Display the students assigned to the authenticated teacher.
     */
    public function index(Request $request): View
    {
        $teacher = $request->user()->teacher;

        $students = Student::query()
            ->whereHas('schedules', fn (Builder $query): Builder => $query->where('teacher_id', $teacher->id))
            ->with([
                'course',
                'schedules' => fn (HasMany $query): HasMany => $query
                    ->where('teacher_id', $teacher->id)
                    ->with('subject'),
            ])
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('student_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('last_name')
            ->orderBy('student_id')
            ->get();

        return view('teacher.students', [
            'students' => $students,
            'teacher' => $teacher,
        ]);
    }
}
