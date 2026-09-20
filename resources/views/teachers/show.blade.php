@extends('layouts.admin')

@section('title', 'Teacher Details')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $teacher->full_name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $teacher->email }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('teachers.index') }}" icon="arrow-left">Back to Teachers</x-button>
                <x-button variant="secondary" size="sm" href="{{ route('teachers.edit', $teacher) }}" icon="pencil">Edit</x-button>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Teacher Information</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $teacher->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                        <dd class="mt-1">@include('partials.status-badge', ['status' => $teacher->status])</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Phone</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $teacher->phone ?? '&mdash;' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Department</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $teacher->department ?? '&mdash;' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Login Account</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            @if ($teacher->user)
                                {{ $teacher->user->email }}
                            @else
                                <span class="text-amber-600">No login yet</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Assigned Schedules</h2>
                </div>
                @if ($teacher->schedules->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-500">No schedules assigned yet.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($teacher->schedules as $schedule)
                            <li class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $schedule->subject->subject_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $schedule->student->full_name }} &middot; {{ $schedule->semester }} &middot; {{ $schedule->academic_year }}</p>
                                </div>
                                <div class="text-sm text-slate-600">
                                    {{ $schedule->day }} &middot; {{ \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) }}-{{ \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Recorded Grades</h2>
                </div>
                @if ($teacher->grades->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-500">No grades recorded yet.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($teacher->grades as $grade)
                            <li class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $grade->student->full_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $grade->subject->subject_name }} &middot; {{ $grade->semester }} &middot; {{ $grade->academic_year }}</p>
                                </div>
                                @include('partials.grade-badge', ['grade' => $grade])
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection