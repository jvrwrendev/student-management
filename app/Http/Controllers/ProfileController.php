<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile settings.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['student', 'teacher']);

        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update the authenticated user's profile settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate($this->rules($user));

        DB::transaction(function () use ($request, $user, $validated): void {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => $validated['password']]);
            }

            if ($user->isTeacher() && $user->teacher) {
                $user->teacher->update([
                    'phone' => $validated['phone'],
                    'department' => $validated['department'] ?? null,
                ]);
            }

            if ($user->isStudent() && $user->student) {
                $user->student->update([
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                ]);
            }
        });

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(User $user): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($user->isTeacher()) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['department'] = ['nullable', 'string', 'max:255'];
        }

        if ($user->isStudent()) {
            $rules['phone'] = ['nullable', 'string', 'regex:/^[0-9+\- ]+$/', 'max:20'];
            $rules['address'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
