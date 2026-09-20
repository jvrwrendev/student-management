@extends('layouts.admin')

@section('title', 'Student Permanent Record')

@section('content')
    <style>
        @media print {
            #sidebar, #sidebar-backdrop, header, footer { display: none !important; }
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>

    <div class="mx-auto max-w-4xl">
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Permanent Record</h1>
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

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-8 py-6 text-center">
                <p class="text-lg font-bold tracking-tight text-slate-900">{{ config('app.name') }}</p>
                <p class="text-sm font-medium text-slate-600">Student Permanent Record</p>
                <p class="mt-1 text-sm text-slate-500">{{ $student->course->course_name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-x-6 gap-y-3 px-8 py-6 text-sm sm:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student Name</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student ID</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->student_id }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->year_level }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Enrollment Status</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->status }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Gender</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->gender }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Date of Birth</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->date_of_birth->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->course->course_code }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Login Account</p>
                    <p class="mt-1 font-medium text-slate-900">{{ $student->user?->email ?? '&mdash;' }}</p>
                </div>
            </div>

            @if ($gradeGroups->isEmpty())
                <div class="px-8 pb-8">
                    <div class="rounded-md border border-dashed border-slate-200 py-12 text-center">
                        <p class="text-sm text-slate-500">No academic records recorded for this student yet.</p>
                    </div>
                </div>
            @else
                @foreach ($gradeGroups as $academicYear => $semesters)
                    <div class="border-t border-slate-200">
                        <div class="bg-slate-50 px-8 py-3">
                            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-700">School Year {{ $academicYear }}</h2>
                        </div>

                        @foreach ($semesters as $semester => $grades)
                            <div class="px-8 py-5">
                                <h3 class="mb-3 text-sm font-semibold text-slate-600">{{ $semester }}</h3>
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead>
                                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                            <th scope="col" class="py-2 pr-4">Subject</th>
                                            <th scope="col" class="py-2 pr-4 text-center">Units</th>
                                            <th scope="col" class="py-2 pr-4 text-center">Grade</th>
                                            <th scope="col" class="py-2 pr-4 text-center">%</th>
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
                                                <td class="py-3 pr-4 text-center text-slate-600">{{ $grade->subject->units }}</td>
                                                <td class="py-3 pr-4 text-center font-semibold text-slate-900">{{ $grade->grade }}</td>
                                                <td class="py-3 pr-4 text-center text-slate-600">{{ number_format($grade->percentage(), 2) }}</td>
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
                            </div>
                        @endforeach

                        @php($summary = $yearSummaries[$academicYear])
                        <div class="grid grid-cols-2 gap-6 border-t border-slate-100 px-8 py-4 text-sm sm:grid-cols-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Units</p>
                                <p class="mt-1 font-bold text-slate-900">{{ number_format($summary['totalUnits'], 1) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Weighted GWA</p>
                                <p class="mt-1 font-bold text-slate-900">{{ $summary['weightedGwa'] !== null ? number_format($summary['weightedGwa'], 2) : '&mdash;' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Percentage Average</p>
                                <p class="mt-1 font-bold text-slate-900">{{ $summary['weightedPercent'] !== null ? number_format($summary['weightedPercent'], 2) : '&mdash;' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Honors</p>
                                <p class="mt-1">
                                    @if ($summary['weightedPercent'] !== null && $summary['weightedPercent'] >= $honorsThreshold)
                                        <span class="font-bold text-accent-600">With Honors</span>
                                    @else
                                        <span class="text-slate-400">&mdash;</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="border-t border-slate-200 bg-slate-50 px-8 py-5">
                    <div class="grid grid-cols-2 gap-6 text-sm sm:grid-cols-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Subject Records</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">{{ $student->grades->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Units</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">{{ number_format($overallUnits, 1) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">General Weighted Average</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">{{ $overallGwa !== null ? number_format($overallGwa, 2) : '&mdash;' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Overall Percentage</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">{{ $overallPercent !== null ? number_format($overallPercent, 2) : '&mdash;' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-8 border-t border-slate-200 px-8 py-8 text-center text-sm text-slate-600">
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