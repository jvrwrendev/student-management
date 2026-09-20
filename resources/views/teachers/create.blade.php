@extends('layouts.admin')

@section('title', 'Add Teacher')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add Teacher</h1>
            <p class="mt-1 text-sm text-slate-500">Fill in the details to record a new teacher.</p>
        </div>

        <form method="POST" action="{{ route('teachers.store') }}" class="rounded-lg border border-slate-200 bg-white">
            @csrf
            @include('teachers._form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                <x-button variant="secondary" href="{{ route('teachers.index') }}">Cancel</x-button>
                <x-button type="submit" icon="check">Save Teacher</x-button>
            </div>
        </form>
    </div>
@endsection