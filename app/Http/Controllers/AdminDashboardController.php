<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'totalStudents' => Student::count(),
            'activeStudents' => Student::active()->count(),
            'inactiveStudents' => Student::inactive()->count(),
            'totalCourses' => Course::count(),
            'totalSubjects' => Subject::count(),
            'totalTeachers' => Teacher::count(),
            'recentStudents' => Student::with('course')->latest()->limit(5)->get(),
            'recentGrades' => Grade::with(['student', 'subject'])->latest()->limit(5)->get(),
        ]);
    }
}
