@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Student</h1>
            <p class="mt-1 text-sm text-slate-500">Update the details for {{ $student->full_name }}.</p>
        </div>

        <form method="POST" action="{{ route('students.update', $student) }}" class="rounded-lg border border-slate-200 bg-white">
            @csrf
            @method('PUT')
            @include('students._form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                <x-button variant="secondary" href="{{ route('students.show', $student) }}">
                    Cancel
                </x-button>
                <x-button type="submit" icon="check">
                    Update Student
                </x-button>
            </div>
        </form>
    </div>
@endsection