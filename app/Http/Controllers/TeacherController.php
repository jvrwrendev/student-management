<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $teachers = Teacher::query()
            ->with('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->orderBy('last_name')
            ->paginate(10);

        return view('teachers.index', ['teachers' => $teachers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $teacher = DB::transaction(function () use ($request, $validated): Teacher {
            $teacher = Teacher::create($validated);

            $this->createLogin($request, $teacher);

            return $teacher;
        });

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher): View
    {
        $teacher->load(['user', 'schedules.subject', 'schedules.student', 'grades.subject']);

        return view('teachers.show', ['teacher' => $teacher]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher): View
    {
        return view('teachers.edit', ['teacher' => $teacher->load('user')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate($this->rules($teacher));

        DB::transaction(function () use ($request, $teacher, $validated): void {
            $teacher->update($validated);

            $this->resetPassword($request, $teacher);
        });

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        DB::transaction(function () use ($teacher): void {
            $teacher->user?->delete();
            $teacher->delete();
        });

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Create a login account for a new teacher when requested.
     */
    private function createLogin(Request $request, Teacher $teacher): void
    {
        if (! $request->boolean('create_account')) {
            return;
        }

        $email = $request->input('login_email') ?: $teacher->email;

        $user = User::create([
            'name' => $teacher->full_name,
            'email' => $email,
            'password' => $request->input('password'),
            'role' => Role::Teacher,
        ]);

        $teacher->user()->associate($user)->save();
    }

    /**
     * Reset the linked login password when requested.
     */
    private function resetPassword(Request $request, Teacher $teacher): void
    {
        if ($request->filled('reset_password') && $teacher->user) {
            $teacher->user->update(['password' => $request->input('reset_password')]);
        }
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    private function rules(?Teacher $teacher = null): array
    {
        $emailRule = Rule::unique('teachers', 'email');

        if ($teacher !== null) {
            $emailRule = $emailRule->ignore($teacher->id);
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $emailRule],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'create_account' => ['sometimes', 'boolean'],
            'login_email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required_if:create_account,1', 'string', 'min:8', 'confirmed'],
            'reset_password' => ['sometimes', 'nullable', 'string', 'min:8'],
        ];
    }
}
