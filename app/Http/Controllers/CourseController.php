<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $courses = Course::query()
            ->withCount('students')
            ->orderBy('course_name')
            ->paginate(10);

        return view('courses.index', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Course::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): View
    {
        $students = $course->students()->with('course')->orderBy('last_name')->paginate(10);

        return view('courses.show', ['course' => $course, 'students' => $students]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        return view('courses.edit', ['course' => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate($this->rules($course));

        $course->update($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    private function rules(?Course $course = null): array
    {
        $codeRule = Rule::unique('courses', 'course_code');

        if ($course !== null) {
            $codeRule = $codeRule->ignore($course->id);
        }

        return [
            'course_code' => ['required', 'string', 'max:20', $codeRule],
            'course_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
