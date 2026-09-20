<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Support\Term;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    /**
     * Display a student's report card for the selected term.
     */
    public function __invoke(Request $request, Student $student): View
    {
        $student->load('course');

        $semester = ($request->string('semester')->toString() !== '' ? $request->string('semester')->toString() : Term::semesters()[0]);
        $academicYear = ($request->string('academic_year')->toString() !== '' ? $request->string('academic_year')->toString() : Term::currentAcademicYear());

        $grades = $student->grades()
            ->where('semester', $semester)
            ->where('academic_year', $academicYear)
            ->with(['subject', 'teacher'])
            ->get();

        return view('report-cards.show', [
            'student' => $student,
            'semester' => $semester,
            'academicYear' => $academicYear,
            'semesters' => Term::semesters(),
            'academicYears' => Term::academicYears(),
            'grades' => $grades,
            'average' => $grades->isEmpty() ? null : round($grades->avg('grade') * 100) / 100,
            'passedCount' => $grades->filter(fn (Grade $grade): bool => $grade->isPassed())->count(),
        ]);
    }
}
