@extends('layouts.admin')

@section('title', 'Grades')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Grades</h1>
                <p class="mt-1 text-sm text-slate-500">Record and review grades for the students you teach.</p>
            </div>
            <x-button icon="plus" href="{{ route('grades.create') }}">
                Record Grade
            </x-button>
        </div>

        <form method="GET" action="{{ route('grades.index') }}" class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="Search student..."
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <select name="subject_id"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                <option value="">All subjects</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected((int) request('subject_id') === $subject->id)>{{ $subject->subject_code }} &mdash; {{ $subject->subject_name }}</option>
                @endforeach
            </select>
            <select name="semester"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                <option value="">All semesters</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester }}" @selected(request('semester') === $semester)>{{ $semester }}</option>
                @endforeach
            </select>
            <select name="academic_year"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                <option value="">All academic years</option>
                @foreach ($academicYears as $year)
                    <option value="{{ $year }}" @selected(request('academic_year') === $year)>{{ $year }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-3 sm:col-span-2 lg:col-span-4">
                <x-button variant="dark" type="submit" icon="search">
                    Filter
                </x-button>
                @if (request()->hasAny(['student_search', 'subject_id', 'semester', 'academic_year']))
                    <x-button variant="secondary" href="{{ route('grades.index') }}">
                        Clear
                    </x-button>
                @endif
                @if ($reportCardStudent)
                    <x-button variant="view" class="ml-auto" href="{{ route('report-cards.show', $reportCardStudent) }}">
                        Report Card: {{ $reportCardStudent->full_name }}
                    </x-button>
                @endif
            </div>
        </form>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            @if ($grades->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-900">No grades found.</p>
                    <p class="mt-1 text-sm text-slate-500">Record a grade to get started.</p>
                    <x-button class="mt-4" icon="plus" href="{{ route('grades.create') }}">
                        Record Grade
                    </x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Subject</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Teacher</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Term</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Grade</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Result</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($grades as $grade)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-900">{{ $grade->student->full_name }}</p>
                                        <p class="text-sm text-slate-500">{{ $grade->student->student_id }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-slate-900">{{ $grade->subject->subject_name }}</p>
                                        <p class="text-sm text-slate-500">{{ $grade->subject->subject_code }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $grade->teacher->full_name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $grade->semester }}<br><span class="text-xs text-slate-400">{{ $grade->academic_year }}</span></td>
                                    <td class="px-6 py-4">@include('partials.grade-badge', ['grade' => $grade])</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $grade->result }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <x-button variant="secondary" size="sm" icon="pencil" href="{{ route('grades.edit', $grade) }}">
                                                Edit
                                            </x-button>
                                            <form method="POST" action="{{ route('grades.destroy', $grade) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this grade for {{ $grade->student->full_name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger" size="sm" type="submit">
                                                    Delete
                                                </x-button>
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
            {{ $grades->withQueryString()->links() }}
        </div>
    </div>
@endsection