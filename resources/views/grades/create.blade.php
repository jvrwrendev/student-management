@extends('layouts.admin')

@section('title', 'Record Grade')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Record Grade</h1>
            <p class="mt-1 text-sm text-slate-500">Select a student and subject to record a grade.</p>
        </div>

        <form method="POST" action="{{ route('grades.store') }}" class="rounded-lg border border-slate-200 bg-white">
            @csrf
            @include('grades._form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                <x-button variant="secondary" href="{{ route('grades.index') }}">
                    Cancel
                </x-button>
                <x-button type="submit" icon="check">
                    Save Grade
                </x-button>
            </div>
        </form>
    </div>
@endsection