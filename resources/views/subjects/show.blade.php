@extends('layouts.admin')

@section('title', 'Subject Details')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $subject->subject_name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $subject->subject_code }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('subjects.index') }}" icon="arrow-left">Back to Subjects</x-button>
                <x-button href="{{ route('subjects.edit', $subject) }}" icon="pencil">Edit</x-button>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white">
            <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Subject Information</h2>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Subject Code</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $subject->subject_code }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                    <dd class="mt-1">@include('partials.status-badge', ['status' => $subject->status])</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Description</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $subject->description ?? '&mdash;' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Recorded Grades</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $subject->grades_count }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Units</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $subject->units }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection