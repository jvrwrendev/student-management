@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div>
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Overview of the student records management system.</p>
            </div>
            <div class="flex gap-3">
                <x-button icon="plus" href="{{ route('students.create') }}">Add Student</x-button>
                <x-button variant="secondary" icon="plus" href="{{ route('courses.create') }}">Add Course</x-button>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Total Students</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalStudents }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Active Students</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $activeStudents }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Inactive Students</p>
                <p class="mt-2 text-3xl font-bold text-slate-400">{{ $inactiveStudents }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Total Courses</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $totalCourses }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Subjects</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $totalSubjects }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Teachers</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $totalTeachers }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Recent Students</h2>
                    <a href="{{ route('students.index') }}" class="text-sm font-medium text-accent-600 hover:text-accent-500">View all</a>
                </div>

                @if ($recentStudents->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-slate-500">No students have been recorded yet.</p>
                        <a href="{{ route('students.create') }}" class="mt-3 inline-block text-sm font-medium text-accent-600 hover:text-accent-500">
                            Add your first student &rarr;
                        </a>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($recentStudents as $student)
                            <li class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <a href="{{ route('students.show', $student) }}" class="font-medium text-slate-900 hover:text-accent-600">
                                        {{ $student->full_name }}
                                    </a>
                                    <p class="text-sm text-slate-500">
                                        {{ $student->student_id }} &middot; {{ $student->course->course_name }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @include('partials.status-badge', ['status' => $student->status])
                                    <span class="text-xs text-slate-400">{{ $student->created_at->diffForHumans() }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Recent Grades</h2>
                    <span class="text-xs text-slate-400">Managed by teachers</span>
                </div>

                @if ($recentGrades->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-slate-500">No grades have been recorded yet.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($recentGrades as $grade)
                            <li class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $grade->student->full_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $grade->subject->subject_name }} &middot; {{ $grade->semester }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @include('partials.grade-badge', ['grade' => $grade])
                                    <span class="text-xs text-slate-400">{{ $grade->created_at->diffForHumans() }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection