@extends('layouts.admin')

@section('title', 'My Students')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Students</h1>
                <p class="mt-1 text-sm text-slate-500">Students assigned to you across your classes.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('teacher.students') }}" class="mb-6 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, or email..."
                class="block w-full max-w-md rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <x-button variant="dark" type="submit" icon="search">Search</x-button>
            @if (request()->filled('search'))
                <a href="{{ route('teacher.students') }}"
                    class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900">
                    Clear
                </a>
            @endif
        </form>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            @if ($students->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-900">@if (request()->filled('search')) No students match your search. @else You have no assigned students yet. @endif</p>
                    <p class="mt-1 text-sm text-slate-500">@if (request()->filled('search')) Try a different name, ID, or email. @else Students appear here once they are added to one of your classes. @endif</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Course</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Assigned Classes</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($students as $student)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('report-cards.show', $student) }}" class="font-medium text-slate-900 hover:text-accent-600">
                                            {{ $student->full_name }}
                                        </a>
                                        <p class="text-sm text-slate-500">{{ $student->student_id }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $student->course->course_code }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $student->year_level }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $student->schedules->pluck('subject.subject_name')->implode(', ') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <x-button variant="view" size="sm" href="{{ route('report-cards.show', $student) }}" icon="document">
                                                Report Card
                                            </x-button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection