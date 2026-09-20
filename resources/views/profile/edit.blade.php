@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('content')
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Profile Settings</h1>
            <p class="mt-1 text-sm text-slate-500">Update your account information and password.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="rounded-lg border border-slate-200 bg-white">
                    <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Account</h2>

                    <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                            @error('email')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <h3 class="text-sm font-medium text-slate-700">Change Password</h3>
                            <p class="mt-1 text-sm text-slate-500">Leave these fields blank to keep your current password.</p>
                        </div>

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-slate-700">Current Password</label>
                            <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                            @error('current_password')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">New Password</label>
                            <input type="password" name="password" id="password" autocomplete="new-password"
                                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                            @error('password')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                        </div>
                    </div>
                </div>

                @php($teacher = $user->isTeacher() ? $user->teacher : null)
                @php($student = $user->isStudent() ? $user->student : null)

                @if ($teacher || $student)
                    <div class="rounded-lg border border-slate-200 bg-white">
                        <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Profile</h2>

                        @if ($student)
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 border-b border-slate-200 px-6 py-5 sm:grid-cols-3">
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student ID</dt>
                                    <dd class="mt-1 text-sm text-slate-900">{{ $student->student_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course</dt>
                                    <dd class="mt-1 text-sm text-slate-900">{{ $student->course->course_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</dt>
                                    <dd class="mt-1 text-sm text-slate-900">{{ $student->year_level }}</dd>
                                </div>
                            </dl>
                        @endif

                        <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                                <input type="text" name="phone" id="phone"
                                    value="{{ old('phone', $teacher?->phone ?? $student?->phone ?? '') }}"
                                    placeholder="e.g. +63 912 345 6789"
                                    class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                                @error('phone')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($student)
                                <div class="sm:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                                    <input type="text" name="address" id="address"
                                        value="{{ old('address', $student->address ?? '') }}"
                                        placeholder="e.g. 123 Mabini Street, Manila"
                                        class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                                    @error('address')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @if ($teacher)
                                <div>
                                    <label for="department" class="block text-sm font-medium text-slate-700">Department</label>
                                    <input type="text" name="department" id="department"
                                        value="{{ old('department', $teacher->department ?? '') }}"
                                        placeholder="e.g. College of Information Technology"
                                        class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                                    @error('department')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between gap-4">
                    <x-button variant="secondary" href="{{ url()->previous() }}">
                        Cancel
                    </x-button>
                    <x-button type="submit" icon="check">
                        Save Profile
                    </x-button>
                </div>
            </div>
        </form>
    </div>
@endsection