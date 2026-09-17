<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'totalStudents' => Student::count(),
            'activeStudents' => Student::active()->count(),
            'inactiveStudents' => Student::inactive()->count(),
            'totalCourses' => Course::count(),
            'recentStudents' => Student::with('course')->latest()->limit(5)->get(),
        ]);
    }
}
