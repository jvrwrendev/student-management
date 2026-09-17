@extends('layouts.admin')

@section('title', 'Courses')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Courses</h1>
                <p class="mt-1 text-sm text-gray-500">Manage the courses offered to students.</p>
            </div>
            <a href="{{ route('courses.create') }}"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Add Course
            </a>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            @if ($courses->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-gray-900">No courses yet.</p>
                    <p class="mt-1 text-sm text-gray-500">Add a course to get started.</p>
                    <a href="{{ route('courses.create') }}"
                        class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Add Course
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Course Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Course Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Students</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($courses as $course)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('courses.show', $course) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $course->course_code }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $course->course_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $course->students_count }}</td>
                                    <td class="px-6 py-4">
                                        @include('partials.status-badge', ['status' => $course->status])
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('courses.show', $course) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">View</a>
                                            <a href="{{ route('courses.edit', $course) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                                            <form method="POST" action="{{ route('courses.destroy', $course) }}"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $course->course_name }}? All enrolled students will also be deleted.');">
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
            {{ $courses->links() }}
        </div>
    </div>
@endsection