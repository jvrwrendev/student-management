<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    private const YEAR_LEVELS = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with('course')
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
            ->paginate(10);

        return view('students.index', ['students' => $students]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('students.create', ['courses' => $this->courses()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        $student->load('course');

        return view('students.show', ['student' => $student]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        return view('students.edit', ['student' => $student, 'courses' => $this->courses()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate($this->rules($student));

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    private function rules(?Student $student = null): array
    {
        $studentIdRule = Rule::unique('students', 'student_id');
        $emailRule = Rule::unique('students', 'email');

        if ($student !== null) {
            $studentIdRule = $studentIdRule->ignore($student->id);
            $emailRule = $emailRule->ignore($student->id);
        }

        return [
            'student_id' => ['required', 'string', 'max:20', $studentIdRule],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'email' => ['required', 'string', 'email', 'max:255', $emailRule],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\- ]+$/', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'year_level' => ['required', Rule::in(self::YEAR_LEVELS)],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    /**
     * @return Collection<int, Course>
     */
    private function courses(): Collection
    {
        return Course::query()->orderBy('course_name')->get();
    }
}
