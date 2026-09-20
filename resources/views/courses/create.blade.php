@extends('layouts.admin')

@section('title', 'Add Course')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add Course</h1>
            <p class="mt-1 text-sm text-slate-500">Create a new course that students can enroll in.</p>
        </div>

        <form method="POST" action="{{ route('courses.store') }}" class="rounded-lg border border-slate-200 bg-white">
            @csrf
            @include('courses._form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                <x-button variant="secondary" href="{{ route('courses.index') }}">Cancel</x-button>
                <x-button type="submit" icon="check">Save Course</x-button>
            </div>
        </form>
    </div>
@endsection