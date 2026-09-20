@extends('layouts.admin')

@section('title', 'Edit Course')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Course</h1>
            <p class="mt-1 text-sm text-slate-500">Update the details for {{ $course->course_name }}.</p>
        </div>

        <form method="POST" action="{{ route('courses.update', $course) }}" class="rounded-lg border border-slate-200 bg-white">
            @csrf
            @method('PUT')
            @include('courses._form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                <x-button variant="secondary" href="{{ route('courses.show', $course) }}">Cancel</x-button>
                <x-button type="submit" icon="check">Update Course</x-button>
            </div>
        </form>
    </div>
@endsection