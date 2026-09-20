@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div>
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome, {{ $teacher->first_name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Your teaching overview for {{ config('app.name') }}.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Assigned Classes</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $scheduleCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Students Assigned</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $studentCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <p class="text-sm font-medium text-slate-500">Grades Recorded</p>
                <p class="mt-2 text-3xl font-bold text-accent-600">{{ $gradeCount }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-lg border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Recent Classes</h2>
                <a href="{{ route('schedules.index') }}" class="text-sm font-medium text-accent-600 hover:text-accent-500">View all</a>
            </div>

            @if ($recentSchedules->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-slate-500">No classes assigned yet.</p>
                    <a href="{{ route('schedules.create') }}" class="mt-3 inline-block text-sm font-medium text-accent-600 hover:text-accent-500">
                        Add your first schedule &rarr;
                    </a>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentSchedules as $schedule)
                        <li class="flex items-center justify-between gap-4 px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $schedule->subject->subject_name }}</p>
                                <p class="text-sm text-slate-500">{{ $schedule->student->full_name }} &middot; {{ $schedule->student->student_id }}</p>
                            </div>
                            <span class="text-xs text-slate-400">{{ $schedule->day }} &middot; {{ \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) }}-{{ \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection