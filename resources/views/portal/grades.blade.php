@extends('layouts.admin')

@section('title', 'My Grades')

@section('content')
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Grades</h1>
            <p class="mt-1 text-sm text-slate-500">Your complete grade history by subject and term.</p>
        </div>

        @if ($gradeGroups->isEmpty())
            <div class="rounded-lg border border-slate-200 bg-white px-6 py-16 text-center">
                <p class="text-sm font-medium text-slate-900">No grades recorded yet.</p>
                <p class="mt-1 text-sm text-slate-500">Your grades will appear here once they are published.</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($gradeGroups as $academicYear => $semesterGroups)
                    <div class="rounded-lg border border-slate-200 bg-white">
                        <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Academic Year {{ $academicYear }}</h2>

                        @foreach ($semesterGroups as $semester => $grades)
                            <div class="border-b border-slate-100 last:border-b-0">
                                <h3 class="px-6 py-3 text-sm font-semibold text-slate-700">{{ $semester }}</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Subject</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Teacher</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Grade</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Result</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @foreach ($grades as $grade)
                                                <tr class="hover:bg-slate-50">
                                                    <td class="px-6 py-4">
                                                        <p class="text-sm font-medium text-slate-900">{{ $grade->subject->subject_name }}</p>
                                                        <p class="text-sm text-slate-500">{{ $grade->subject->subject_code }}</p>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $grade->teacher->full_name }}</td>
                                                    <td class="px-6 py-4">@include('partials.grade-badge', ['grade' => $grade])</td>
                                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $grade->result }}</td>
                                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $grade->remarks ?? '&mdash;' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection