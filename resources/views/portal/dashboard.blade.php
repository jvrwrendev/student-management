@extends('layouts.admin')

@section('title', 'My Dashboard')

@section('content')
    <div>
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome, {{ $student->first_name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Your personal student portal.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Subjects Recorded</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $gradeCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Average Grade</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $averageGrade !== null ? number_format((float) $averageGrade, 2) : '&mdash;' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Passed Subjects</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $passedCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Assigned Classes</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $scheduleCount }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-lg border border-slate-200 bg-white">
            <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">My Information</h2>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student ID</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $student->student_id }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Full Name</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $student->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $student->course->course_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $student->year_level }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Email</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $student->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Phone</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $student->phone ?? '&mdash;' }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <a href="{{ route('portal.grades') }}"
                class="group flex items-center justify-between rounded-lg border border-slate-200 bg-white p-6 hover:border-accent-200 hover:bg-accent-50">
                <div>
                    <p class="text-sm font-medium text-slate-500">My Grades</p>
                    <p class="mt-1 text-sm text-slate-900">View your grades by subject and term</p>
                </div>
                <span class="text-accent-600 group-hover:translate-x-1">&rarr;</span>
            </a>
            <a href="{{ route('portal.schedule') }}"
                class="group flex items-center justify-between rounded-lg border border-slate-200 bg-white p-6 hover:border-accent-200 hover:bg-accent-50">
                <div>
                    <p class="text-sm font-medium text-slate-500">My Schedule</p>
                    <p class="mt-1 text-sm text-slate-900">View your assigned classes</p>
                </div>
                <span class="text-accent-600 group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>
    </div>
@endsection