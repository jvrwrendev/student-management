<?php

namespace App\Http\Controllers;

use App\Enums\SchoolDay;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ScheduleConflictService;
use App\Support\Term;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $schedules = Schedule::query()
            ->with(['student.course', 'subject', 'teacher'])
            ->when($request->user()->isTeacher(), function ($query) use ($request): void {
                $query->where('teacher_id', $request->user()->teacher?->id);
            })
            ->when($request->filled('student_search'), function ($query) use ($request): void {
                $search = $request->string('student_search')->toString();
                $query->whereHas('student', function ($query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('teacher_id'), fn ($query) => $query->where('teacher_id', $request->integer('teacher_id')))
            ->when($request->filled('day'), fn ($query) => $query->where('day', $request->string('day')->toString()))
            ->when($request->filled('semester'), fn ($query) => $query->where('semester', $request->string('semester')->toString()))
            ->when($request->filled('academic_year'), fn ($query) => $query->where('academic_year', $request->string('academic_year')->toString()))
            ->orderBy('day')
            ->orderBy('start_time')
            ->paginate(12);

        return view('schedules.index', [
            'schedules' => $schedules,
            'teachers' => Teacher::query()->active()->orderBy('last_name')->get(),
            'days' => SchoolDay::options(),
            'semesters' => Term::semesters(),
            'academicYears' => Term::academicYears(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('schedules.create', $this->formData($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ScheduleConflictService $conflicts): RedirectResponse
    {
        $validated = $request->validate($this->rules($request));

        if ($request->user()->isTeacher()) {
            $validated['teacher_id'] = $this->teacherId($request);
        }

        if ($this->hasConflicts($validated, 0, $conflicts)) {
            return back()->withInput();
        }

        Schedule::create($validated);

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Schedule $schedule): View
    {
        $this->authorize($request, $schedule);

        return view('schedules.edit', array_merge(
            ['schedule' => $schedule],
            $this->formData($request),
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, ScheduleConflictService $conflicts): RedirectResponse
    {
        $this->authorize($request, $schedule);

        $validated = $request->validate($this->rules($request));

        if ($request->user()->isTeacher()) {
            $validated['teacher_id'] = $this->teacherId($request);
        }

        if ($this->hasConflicts($validated, $schedule->id, $conflicts)) {
            return back()->withInput();
        }

        $schedule->update($validated);

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->authorize($request, $schedule);

        $schedule->delete();

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule removed successfully.');
    }

    /**
     * Ensure the authenticated user may manage the given schedule.
     */
    private function authorize(Request $request, Schedule $schedule): void
    {
        if ($request->user()->isTeacher() && $schedule->teacher_id !== $this->teacherId($request)) {
            abort(403);
        }
    }

    /**
     * Attach the teacher's conflict to the request error bag when found.
     *
     * @param  array<string, string|int>  $validated
     */
    private function hasConflicts(array $validated, int $ignoreScheduleId, ScheduleConflictService $conflicts): bool
    {
        $conflicting = $conflicts->conflicts($validated, $ignoreScheduleId);

        if ($conflicting->isEmpty()) {
            return false;
        }

        $details = $conflicting->map(function (Schedule $schedule) use ($validated): string {
            $name = $schedule->student_id === (int) $validated['student_id']
                ? 'student '.$schedule->student->full_name
                : 'teacher '.$schedule->teacher->full_name;

            return $name.' ('.$schedule->subject->subject_name.', '.substr($schedule->start_time, 0, 5).'-'.substr($schedule->end_time, 0, 5).')';
        })->implode(', ');

        session()->flash('error', 'This class overlaps an existing class: '.$details.'.');

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        $isTeacher = $request->user()->isTeacher();
        $teacher = $request->user()->teacher;

        return [
            'students' => Student::query()->active()->with('course')->orderBy('last_name')->get(),
            'subjects' => Subject::query()->active()->orderBy('subject_name')->get(),
            'teachers' => $isTeacher ? collect([$teacher]) : Teacher::query()->active()->orderBy('last_name')->get(),
            'days' => SchoolDay::cases(),
            'semesters' => Term::semesters(),
            'academicYears' => Term::academicYears(),
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(Request $request): array
    {
        $rules = [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'room' => ['nullable', 'string', 'max:100'],
            'day' => ['required', Rule::in(SchoolDay::options())],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'semester' => ['required', Rule::in(Term::semesters())],
            'academic_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
        ];

        if (! $request->user()->isTeacher()) {
            $rules['teacher_id'] = ['required', 'integer', 'exists:teachers,id'];
        }

        $rules['end_time'][] = function ($attribute, $value, $fail) use ($request): void {
            if ($value <= $request->string('start_time')->toString()) {
                $fail('The end time must be after the start time.');
            }
        };

        return $rules;
    }

    private function teacherId(Request $request): int
    {
        return $request->user()->teacher?->id ?? abort(403);
    }
}
