@extends('layouts.admin')

@section('title', 'Courses')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Courses</h1>
                <p class="mt-1 text-sm text-slate-500">Manage the courses offered to students.</p>
            </div>
            <x-button icon="plus" href="{{ route('courses.create') }}">Add Course</x-button>
        </div>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            @if ($courses->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-900">No courses yet.</p>
                    <p class="mt-1 text-sm text-slate-500">Add a course to get started.</p>
                    <x-button icon="plus" href="{{ route('courses.create') }}" class="mt-4">Add Course</x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Course Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Course Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Students</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($courses as $course)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('courses.show', $course) }}" class="font-medium text-slate-900 hover:text-accent-600">
                                            {{ $course->course_code }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $course->course_name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $course->students_count }}</td>
                                    <td class="px-6 py-4">
                                        @include('partials.status-badge', ['status' => $course->status])
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <x-button variant="view" size="sm" href="{{ route('courses.show', $course) }}" icon="eye">View</x-button>
                                            <x-button variant="secondary" size="sm" href="{{ route('courses.edit', $course) }}" icon="pencil">Edit</x-button>
                                            <form method="POST" action="{{ route('courses.destroy', $course) }}"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $course->course_name }}? All enrolled students will also be deleted.');">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger" size="sm" type="submit">Delete</x-button>
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