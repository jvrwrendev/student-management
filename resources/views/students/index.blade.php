@extends('layouts.admin')

@section('title', 'Students')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Students</h1>
                <p class="mt-1 text-sm text-gray-500">Manage the enrolled students and their records.</p>
            </div>
            <a href="{{ route('students.create') }}"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Add Student
            </a>
        </div>

        <form method="GET" action="{{ route('students.index') }}" class="mb-6 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, or email..."
                class="block w-full max-w-md rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
            <button type="submit"
                class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Search
            </button>
            @if (request()->filled('search'))
                <a href="{{ route('students.index') }}"
                    class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                    Clear
                </a>
            @endif
        </form>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            @if ($students->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-gray-900">@if (request()->filled('search')) No students match your search. @else No students yet. @endif</p>
                    <p class="mt-1 text-sm text-gray-500">Add a student to get started.</p>
                    <a href="{{ route('students.create') }}"
                        class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Add Student
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Course</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Year Level</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('students.show', $student) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $student->full_name }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ $student->student_id }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $student->course->course_code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $student->year_level }}</td>
                                    <td class="px-6 py-4">
                                        @include('partials.status-badge', ['status' => $student->status])
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('students.show', $student) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">View</a>
                                            <a href="{{ route('students.edit', $student) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                                            <form method="POST" action="{{ route('students.destroy', $student) }}"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $student->full_name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-6">
            {{ $students->withQueryString()->links() }}
        </div>
    </div>
@endsection