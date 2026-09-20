@extends('layouts.admin')

@section('title', 'Report Card')

@section('content')
    <style>
        @media print {
            #sidebar, #sidebar-backdrop, header, footer { display: none !important; }
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>

    <div class="mx-auto max-w-3xl">
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Report Card</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $student->full_name }} &middot; {{ $student->student_id }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-button icon="printer" onclick="window.print()">
                    Print
                </x-button>
                <x-button variant="secondary" icon="arrow-left" href="{{ url()->previous() }}">
                    Back
                </x-button>
            </div>
        </div>

        <form method="GET" action="{{ route('report-cards.show', $student) }}"
            class="no-print mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <select name="semester"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                @foreach ($semesters as $option)
                    <option value="{{ $option }}" @selected($option === $semester)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="academic_year"
                class="block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                @foreach ($academicYears as $option)
                    <option value="{{ $option }}" @selected($option === $academicYear)>{{ $option }}</option>
                @endforeach
            </select>
            <x-button variant="dark" type="submit" class="sm:col-span-2">
                Load Report Card
            </x-button>
        </form>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-8 py-6 text-center">
                <p class="text-lg font-bold tracking-tight text-slate-900">{{ config('app.name') }}</p>
                <p class="text-sm font-medium text-slate-600">Student Report Card</p>
                <p class="mt-1 text-sm text-slate-500">{{ $semester }} &middot; {{ $academicYear }}</p>
            </div>

            <div class="grid grid-cols-2 gap-x-6 gap-y-3 px-8 py-6 text-sm">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student Name</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student ID</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->student_id }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->course->course_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->year_level }}</p>
                </div>
            </div>

            <div class="overflow-x-auto px-8 pb-6">
                @if ($grades->isEmpty())
                    <div class="rounded-md border border-dashed border-slate-200 py-12 text-center">
                        <p class="text-sm text-slate-500">No grades recorded for this term.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-2 pr-4">Subject</th>
                                <th scope="col" class="py-2 pr-4">Teacher</th>
                                <th scope="col" class="py-2 pr-4 text-center">Grade</th>
                                <th scope="col" class="py-2 pr-4">Remarks</th>
                                <th scope="col" class="py-2 text-center">Result</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($grades as $grade)
                                <tr>
                                    <td class="py-3 pr-4">
                                        <p class="font-medium text-slate-900">{{ $grade->subject->subject_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $grade->subject->subject_code }}</p>
                                    </td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $grade->teacher->full_name }}</td>
                                    <td class="py-3 pr-4 text-center font-semibold text-slate-900">{{ $grade->grade }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $grade->remarks ?? '&mdash;' }}</td>
                                    <td class="py-3 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $grade->isPassed() ? 'bg-accent-100 text-accent-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $grade->result }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="border-t border-slate-200 px-8 py-5">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Subjects</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $grades->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Passed</p>
                        <p class="mt-1 text-lg font-bold text-accent-600">{{ $passedCount }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Failed</p>
                        <p class="mt-1 text-lg font-bold text-rose-600">{{ $grades->count() - $passedCount }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">General Average</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ $average !== null ? number_format($average, 2) : '&mdash;' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 px-8 pb-8 pt-2 text-center text-sm text-slate-600">
                <div>
                    <p class="border-t border-slate-300 pt-2">Class Adviser</p>
                </div>
                <div>
                    <p class="border-t border-slate-300 pt-2">Registrar</p>
                </div>
            </div>
        </div>
    </div>
@endsection