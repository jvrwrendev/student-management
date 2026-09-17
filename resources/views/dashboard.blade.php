@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Overview of the student records management system.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('students.create') }}"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Add Student
                </a>
                <a href="{{ route('courses.create') }}"
                    class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Add Course
                </a>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Total Students</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Active Students</p>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $activeStudents }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Inactive Students</p>
                <p class="mt-2 text-3xl font-bold text-gray-400">{{ $inactiveStudents }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Total Courses</p>
                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $totalCourses }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-lg border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h2 class="text-base font-semibold text-gray-900">Recent Students</h2>
                <a href="{{ route('students.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
            </div>

            @if ($recentStudents->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-500">No students have been recorded yet.</p>
                    <a href="{{ route('students.create') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Add your first student &rarr;
                    </a>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($recentStudents as $student)
                        <li class="flex items-center justify-between gap-4 px-6 py-4">
                            <div>
                                <a href="{{ route('students.show', $student) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $student->full_name }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ $student->student_id }} &middot; {{ $student->course->course_name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                @include('partials.status-badge', ['status' => $student->status])
                                <span class="text-xs text-gray-400">{{ $student->created_at->diffForHumans() }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection