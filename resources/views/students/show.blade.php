@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $student->full_name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $student->student_id }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-button icon="document" href="{{ route('students.spr', $student) }}">
                    Permanent Record
                </x-button>
                <x-button variant="secondary" size="sm" href="{{ route('students.edit', $student) }}">
                    Edit
                </x-button>
                <form method="POST" action="{{ route('students.destroy', $student) }}"
                    onsubmit="return confirm('Are you sure you want to delete {{ $student->full_name }}? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" size="sm" type="submit">
                        Delete
                    </x-button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Student Information</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Student ID</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->student_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Gender</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->gender }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->date_of_birth->format('F j, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Contact Information</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Email</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Phone</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->phone ?? '&mdash;' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Address</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Login Account</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            @if ($student->user)
                                {{ $student->user->email }}
                            @else
                                <span class="text-amber-600">No login yet</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Enrollment</h2>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->course->course_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Year Level</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->year_level }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Enrollment Status</dt>
                        <dd class="mt-1">
                            @include('partials.status-badge', ['status' => $student->status])
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Created</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $student->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Academic Records</h2>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('report-cards.show', $student) }}" class="text-sm font-medium text-accent-600 hover:text-accent-500">Report Card</a>
                        <a href="{{ route('students.spr', $student) }}" class="text-sm font-medium text-accent-600 hover:text-accent-500">Permanent Record</a>
                    </div>
                </div>
                @if ($student->grades->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-500">No grades recorded for this student yet.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-200">
                        @foreach ($gradeGroups as $academicYear => $semesters)
                            <div>
                                <div class="bg-slate-50 px-6 py-3">
                                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-700">School Year {{ $academicYear }}</h3>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach ($semesters as $semester => $grades)
                                        <div class="px-6 py-4">
                                            <h4 class="mb-2 text-sm font-semibold text-slate-600">{{ $semester }}</h4>
                                            <ul class="divide-y divide-slate-100">
                                                @foreach ($grades as $grade)
                                                    <li class="flex items-center justify-between gap-4 py-2.5">
                                                        <div class="flex items-center gap-3">
                                                            <div>
                                                                <p class="font-medium text-slate-900">{{ $grade->subject->subject_name }}</p>
                                                                <p class="text-sm text-slate-500">{{ $grade->subject->subject_code }}</p>
                                                            </div>
                                                            <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $grade->subject->units }} units</span>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-sm text-slate-600">{{ $grade->result }}</span>
                                                            @include('partials.grade-badge', ['grade' => $grade])
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-lg border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Class Schedule</h2>
                    <a href="{{ route('schedules.index', ['student_search' => $student->last_name]) }}" class="text-sm font-medium text-accent-600 hover:text-accent-500">View all</a>
                </div>
                @if ($student->schedules->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-500">No classes assigned yet.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($student->schedules as $schedule)
                            <li class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $schedule->subject->subject_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $schedule->subject->subject_code }} &middot; {{ $schedule->teacher->full_name }} &middot; {{ $schedule->room ?? 'No room' }}</p>
                                </div>
                                <span class="text-sm text-slate-600">{{ $schedule->day }} &middot; {{ \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) }}-{{ \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection