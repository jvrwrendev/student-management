<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Support\Term;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $grades = Grade::query()
            ->with(['student.course', 'subject', 'teacher'])
            ->where('teacher_id', $this->teacherId($request))
            ->when($request->filled('student_search'), function ($query) use ($request): void {
                $search = $request->string('student_search')->toString();
                $query->whereHas('student', function ($query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('subject_id'), fn ($query) => $query->where('subject_id', $request->integer('subject_id')))
            ->when($request->filled('semester'), fn ($query) => $query->where('semester', $request->string('semester')->toString()))
            ->when($request->filled('academic_year'), fn ($query) => $query->where('academic_year', $request->string('academic_year')->toString()))
            ->latest()
            ->paginate(12);

        $reportCardStudent = $request->filled('student_search')
            ? Student::query()
                ->where(function ($query) use ($request): void {
                    $search = $request->string('student_search')->toString();
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%");
                })
                ->orderBy('last_name')
                ->first()
            : null;

        return view('grades.index', [
            'grades' => $grades,
            'subjects' => $this->subjects($request),
            'semesters' => Term::semesters(),
            'academicYears' => Term::academicYears(),
            'reportCardStudent' => $reportCardStudent,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('grades.create', array_merge(
            ['preselectedStudentId' => $request->input('student_id') ? (int) $request->input('student_id') : 0],
            $this->formData($request),
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules($request));

        $validated['teacher_id'] = $this->teacherId($request);

        Grade::create($validated);

        return redirect()
            ->route('grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Grade $grade): View
    {
        $this->authorize($request, $grade);

        return view('grades.edit', array_merge(
            ['grade' => $grade],
            $this->formData($request),
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $this->authorize($request, $grade);

        $validated = $request->validate($this->rules($request, $grade));

        $validated['teacher_id'] = $this->teacherId($request);

        $grade->update($validated);

        return redirect()
            ->route('grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Grade $grade): RedirectResponse
    {
        $this->authorize($request, $grade);

        $grade->delete();

        return redirect()
            ->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    /**
     * Ensure the authenticated teacher owns the given grade.
     */
    private function authorize(Request $request, Grade $grade): void
    {
        if ($grade->teacher_id !== $this->teacherId($request)) {
            abort(403);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        $teacherId = $this->teacherId($request);

        return [
            'students' => Student::query()
                ->active()
                ->with('course')
                ->whereHas('schedules', fn ($query) => $query->where('teacher_id', $teacherId))
                ->orderBy('last_name')
                ->get(),
            'subjects' => $this->subjects($request),
            'semesters' => Term::semesters(),
            'academicYears' => Term::academicYears(),
        ];
    }

    /**
     * Subjects the authenticated teacher teaches.
     */
    private function subjects(Request $request): Collection
    {
        return Subject::query()
            ->active()
            ->whereHas('schedules', fn ($query) => $query->where('teacher_id', $this->teacherId($request)))
            ->orderBy('subject_name')
            ->get();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(Request $request, ?Grade $grade = null): array
    {
        $uniqueStudent = Rule::unique('grades', 'student_id')
            ->where('subject_id', $request->integer('subject_id'))
            ->where('semester', $request->string('semester')->toString())
            ->where('academic_year', $request->string('academic_year')->toString());

        if ($grade !== null) {
            $uniqueStudent = $uniqueStudent->ignore($grade->id);
        }

        $rules = [
            'student_id' => ['required', 'integer', 'exists:students,id', $uniqueStudent, function ($attribute, $value, $fail) use ($request): void {
                $exists = Schedule::query()
                    ->where('teacher_id', $this->teacherId($request))
                    ->where('student_id', $value)
                    ->where('subject_id', $request->integer('subject_id'))
                    ->where('semester', $request->string('semester')->toString())
                    ->where('academic_year', $request->string('academic_year')->toString())
                    ->exists();

                if (! $exists) {
                    $fail('You can only record grades for students assigned to your class in the selected term.');
                }
            }],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'semester' => ['required', Rule::in(Term::semesters())],
            'academic_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'grade' => ['required', 'numeric', 'min:1', 'max:5', 'regex:/^\d+(\.\d{1,2})?$/', function ($attribute, $value, $fail): void {
                if (((int) round((float) $value * 100)) % 25 !== 0) {
                    $fail('The '.$attribute.' must be in increments of 0.25.');
                }
            }],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];

        return $rules;
    }

    private function teacherId(Request $request): int
    {
        return $request->user()->teacher?->id ?? abort(403);
    }
}
