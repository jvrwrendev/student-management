@extends('layouts.admin')

@section('title', 'Edit Course')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Course</h1>
            <p class="mt-1 text-sm text-gray-500">Update the details for {{ $course->course_name }}.</p>
        </div>

        <form method="POST" action="{{ route('courses.update', $course) }}" class="rounded-lg border border-gray-200 bg-white">
            @csrf
            @method('PUT')
            @include('courses._form')

            <div class="flex items-center justify-between gap-4 border-t border-gray-200 px-6 py-4">
                <a href="{{ route('courses.show', $course) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Update Course
                </button>
            </div>
        </form>
    </div>
@endsection