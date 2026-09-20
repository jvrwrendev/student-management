@extends('layouts.admin')

@section('title', 'Course Details')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $course->course_name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $course->course_code }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('courses.index') }}" icon="arrow-left">Back to Courses</x-button>
                <x-button variant="secondary" size="sm" href="{{ route('courses.edit', $course) }}" icon="pencil">Edit</x-button>
                <form method="POST" action="{{ route('courses.destroy', $course) }}"
                    onsubmit="return confirm('Are you sure you want to delete {{ $course->course_name }}? All enrolled students will also be deleted.');">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" size="sm" type="submit">Delete</x-button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Course Information</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course Code</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $course->course_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                        <dd class="mt-1">
                            @include('partials.status-badge', ['status' => $course->status])
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Description</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $course->description ?? 'No description provided.' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Created</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $course->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $course->updated_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Enrolled Students</h2>
                    <a href="{{ route('students.create') }}"
                        class="text-sm font-medium text-accent-600 hover:text-accent-500">Add student</a>
                </div>

                @if ($students->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-slate-500">No students are enrolled in this course.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($students as $student)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('students.show', $student) }}" class="font-medium text-slate-900 hover:text-accent-600">
                                                {{ $student->full_name }}
                                            </a>
                                            <p class="text-sm text-slate-500">{{ $student->student_id }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $student->year_level }}</td>
                                        <td class="px-6 py-4">
                                            @include('partials.status-badge', ['status' => $student->status])
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <x-button variant="view" size="sm" href="{{ route('students.show', $student) }}" icon="eye">View</x-button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($students->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection