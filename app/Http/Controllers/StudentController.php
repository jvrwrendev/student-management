<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\Semester;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $student = DB::transaction(function () use ($request, $validated): Student {
            $student = Student::create($validated);

            $this->createLogin($request, $student);

            return $student;
        });

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        $student->load([
            'course',
            'user',
            'grades.subject',
            'grades.teacher',
            'schedules.subject',
            'schedules.teacher',
        ]);

        $gradeGroups = $this->gradeGroups($student);

        return view('students.show', [
            'student' => $student,
            'gradeGroups' => $gradeGroups,
            'semesterOrder' => array_flip(Semester::options()),
        ]);
    }

    /**
     * Display the student's permanent record (printable).
     */
    public function spr(Student $student): View
    {
        $student->load([
            'course',
            'user',
            'grades.subject',
            'grades.teacher',
        ]);

        $gradeGroups = $this->gradeGroups($student);

        $yearSummaries = $gradeGroups->map(function (Collection $semesters): array {
            $grades = $semesters->flatten(1);
            $totalUnits = (float) $grades->sum(fn (Grade $grade): float => (float) ($grade->subject?->units ?? 3.0));

            return [
                'totalUnits' => $totalUnits,
                'weightedGwa' => $totalUnits > 0
                    ? round($grades->sum(fn (Grade $grade): float => (float) $grade->grade * (float) ($grade->subject?->units ?? 3.0)) / $totalUnits, 2)
                    : null,
                'weightedPercent' => $totalUnits > 0
                    ? round($grades->sum(fn (Grade $grade): float => $grade->percentage() * (float) ($grade->subject?->units ?? 3.0)) / $totalUnits, 2)
                    : null,
            ];
        });

        $allGrades = $student->grades;
        $totalUnits = (float) $allGrades->sum(fn (Grade $grade): float => (float) ($grade->subject?->units ?? 3.0));

        return view('students.spr', [
            'student' => $student,
            'gradeGroups' => $gradeGroups,
            'semesterOrder' => array_flip(Semester::options()),
            'yearSummaries' => $yearSummaries,
            'honorsThreshold' => (float) Setting::value('honors_threshold', '90.00'),
            'overallUnits' => $totalUnits,
            'overallGwa' => $totalUnits > 0
                ? round($allGrades->sum(fn (Grade $grade): float => (float) $grade->grade * (float) ($grade->subject?->units ?? 3.0)) / $totalUnits, 2)
                : null,
            'overallPercent' => $totalUnits > 0
                ? round($allGrades->sum(fn (Grade $grade): float => $grade->percentage() * (float) ($grade->subject?->units ?? 3.0)) / $totalUnits, 2)
                : null,
        ]);
    }

    /**
     * Group a student's grades by academic year, then by semester in order.
     *
     * @return \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, Grade>>>
     */
    private function gradeGroups(Student $student): \Illuminate\Support\Collection
    {
        $semesterOrder = array_flip(Semester::options());

        return $student->grades
            ->sortBy(fn (Grade $grade): int => (int) array_reverse(explode('-', $grade->academic_year))[0] * 100 + ($semesterOrder[$grade->semester] ?? 99))
            ->groupBy('academic_year')
            ->map(fn ($grades): \Illuminate\Support\Collection => $grades
                ->sortBy(fn (Grade $grade): int => $semesterOrder[$grade->semester] ?? 99)
                ->groupBy('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        return view('students.edit', ['student' => $student->load('user'), 'courses' => $this->courses()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate($this->rules($student));

        DB::transaction(function () use ($request, $student, $validated): void {
            $student->update($validated);

            $this->resetPassword($request, $student);
        });

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        DB::transaction(function () use ($student): void {
            $student->user?->delete();
            $student->delete();
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Create a login account for a new student when requested.
     */
    private function createLogin(Request $request, Student $student): void
    {
        if (! $request->boolean('create_account')) {
            return;
        }

        $email = $request->input('login_email') ?: $student->email;

        $user = User::create([
            'name' => $student->full_name,
            'email' => $email,
            'password' => $request->input('password'),
            'role' => Role::Student,
        ]);

        $student->user()->associate($user)->save();
    }

    /**
     * Reset the linked login password when requested.
     */
    private function resetPassword(Request $request, Student $student): void
    {
        if ($request->filled('reset_password') && $student->user) {
            $student->user->update(['password' => $request->input('reset_password')]);
        }
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    private function rules(?Student $student = null): array
    {
        $studentIdRule = Rule::unique('students', 'student_id');
        $emailRule = Rule::unique('students', 'email');
        $loginEmailRule = Rule::unique('users', 'email');

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
            'create_account' => ['sometimes', 'boolean'],
            'login_email' => ['nullable', 'string', 'email', 'max:255', $loginEmailRule],
            'password' => ['required_if:create_account,1', 'string', 'min:8', 'confirmed'],
            'reset_password' => ['sometimes', 'nullable', 'string', 'min:8'],
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
