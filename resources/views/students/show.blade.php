@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $student->full_name }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $student->student_id }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('students.index') }}"
                    class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Back to Students
                </a>
                <a href="{{ route('students.edit', $student) }}"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('students.destroy', $student) }}"
                    onsubmit="return confirm('Are you sure you want to delete {{ $student->full_name }}? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-red-600 ring-1 ring-inset ring-red-300 hover:bg-red-50">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white">
                <h2 class="border-b border-gray-200 px-6 py-4 text-base font-semibold text-gray-900">Student Information</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Student ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->gender }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->date_of_birth->format('F j, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white">
                <h2 class="border-b border-gray-200 px-6 py-4 text-base font-semibold text-gray-900">Contact Information</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->phone ?? '&mdash;' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->address }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white">
                <h2 class="border-b border-gray-200 px-6 py-4 text-base font-semibold text-gray-900">Enrollment</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Course</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->course->course_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Year Level</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->year_level }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Enrollment Status</dt>
                        <dd class="mt-1">
                            @include('partials.status-badge', ['status' => $student->status])
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->updated_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection