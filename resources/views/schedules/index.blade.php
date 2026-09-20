@extends('layouts.admin')

@section('title', 'Schedules')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Schedules</h1>
                <p class="mt-1 text-sm text-slate-500">Manage class schedules for students.</p>
            </div>
            <x-button icon="plus" href="{{ route('schedules.create') }}">Add Schedule</x-button>
        </div>

        <form method="GET" action="{{ route('schedules.index') }}" class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="Search student..."
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @if (! auth()->user()->isTeacher())
                <select name="teacher_id"
                    class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                    <option value="">All teachers</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected((int) request('teacher_id') === $teacher->id)>{{ $teacher->full_name }}</option>
                    @endforeach
                </select>
            @endif
            <select name="day"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                <option value="">All days</option>
                @foreach ($days as $day)
                    <option value="{{ $day }}" @selected(request('day') === $day)>{{ $day }}</option>
                @endforeach
            </select>
            <select name="semester"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                <option value="">All semesters</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester }}" @selected(request('semester') === $semester)>{{ $semester }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-3 lg:col-span-4">
                <x-button variant="dark" type="submit" icon="search">Filter</x-button>
                @if (request()->hasAny(['student_search', 'teacher_id', 'day', 'semester']))
                    <a href="{{ route('schedules.index') }}"
                        class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            @if ($schedules->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-900">No schedules found.</p>
                    <p class="mt-1 text-sm text-slate-500">Add a schedule to get started.</p>
                    <x-button icon="plus" href="{{ route('schedules.create') }}" class="mt-4">Add Schedule</x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Subject</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Teacher</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Day &amp; Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Room</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($schedules as $schedule)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-900">{{ $schedule->student->full_name }}</p>
                                        <p class="text-sm text-slate-500">{{ $schedule->student->student_id }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-slate-900">{{ $schedule->subject->subject_name }}</p>
                                        <p class="text-sm text-slate-500">{{ $schedule->subject->subject_code }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600">{{ $schedule->teacher->full_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $schedule->semester }} &middot; {{ $schedule->academic_year }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $schedule->day }} &middot; {{ \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) }}&ndash;{{ \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $schedule->room ?? '&mdash;' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <x-button variant="secondary" size="sm" href="{{ route('schedules.edit', $schedule) }}" icon="pencil">Edit</x-button>
                                            <form method="POST" action="{{ route('schedules.destroy', $schedule) }}"
                                                onsubmit="return confirm('Are you sure you want to remove this schedule for {{ $schedule->student->full_name }}? This action cannot be undone.');">
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
            {{ $schedules->withQueryString()->links() }}
        </div>
    </div>
@endsection