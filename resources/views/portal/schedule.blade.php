@extends('layouts.admin')

@section('title', 'My Schedule')

@section('content')
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Schedule</h1>
            <p class="mt-1 text-sm text-slate-500">Your assigned classes for the current term.</p>
        </div>

        @if ($schedulesByDay->isEmpty())
            <div class="rounded-lg border border-slate-200 bg-white px-6 py-16 text-center">
                <p class="text-sm font-medium text-slate-900">No classes assigned yet.</p>
                <p class="mt-1 text-sm text-slate-500">Your class schedule will appear here once it is set.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach (\App\Enums\SchoolDay::options() as $day)
                    @if (! $schedulesByDay->has($day))
                        @continue
                    @endif

                    <div class="rounded-lg border border-slate-200 bg-white">
                        <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">{{ $day }}</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Subject</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Teacher</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Room</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($schedulesByDay[$day] as $schedule)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                                {{ \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) }}&ndash;{{ \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-medium text-slate-900">{{ $schedule->subject->subject_name }}</p>
                                                <p class="text-sm text-slate-500">{{ $schedule->subject->subject_code }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600">{{ $schedule->teacher->full_name }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-600">{{ $schedule->room ?? '&mdash;' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection