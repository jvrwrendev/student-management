@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Student</h1>
            <p class="mt-1 text-sm text-gray-500">Update the details for {{ $student->full_name }}.</p>
        </div>

        <form method="POST" action="{{ route('students.update', $student) }}" class="rounded-lg border border-gray-200 bg-white">
            @csrf
            @method('PUT')
            @include('students._form')

            <div class="flex items-center justify-between gap-4 border-t border-gray-200 px-6 py-4">
                <a href="{{ route('students.show', $student) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Update Student
                </button>
            </div>
        </form>
    </div>
@endsection