@extends('layouts.admin')

@section('title', 'Add Schedule')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add Schedule</h1>
            <p class="mt-1 text-sm text-slate-500">Assign a class for a student. Overlapping classes are blocked.</p>
        </div>

        <form method="POST" action="{{ route('schedules.store') }}" class="rounded-lg border border-slate-200 bg-white">
            @csrf
            @include('schedules._form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                <x-button variant="secondary" href="{{ route('schedules.index') }}">Cancel</x-button>
                <x-button type="submit" icon="check">Save Schedule</x-button>
            </div>
        </form>
    </div>
@endsection