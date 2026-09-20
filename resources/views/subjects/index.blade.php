@extends('layouts.admin')

@section('title', 'Subjects')

@section('content')
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Subjects</h1>
                <p class="mt-1 text-sm text-slate-500">Manage the subjects offered by the institution.</p>
            </div>
            <x-button icon="plus" href="{{ route('subjects.create') }}">Add Subject</x-button>
        </div>

        <form method="GET" action="{{ route('subjects.index') }}" class="mb-6 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code or name..."
                class="block w-full max-w-md rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <x-button variant="dark" type="submit" icon="search">Search</x-button>
            @if (request()->filled('search'))
                <a href="{{ route('subjects.index') }}"
                    class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900">
                    Clear
                </a>
            @endif
        </form>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            @if ($subjects->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-900">@if (request()->filled('search')) No subjects match your search. @else No subjects yet. @endif</p>
                    <p class="mt-1 text-sm text-slate-500">Add a subject to get started.</p>
                    <x-button icon="plus" href="{{ route('subjects.create') }}">Add Subject</x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Subject</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Units</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Grades</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($subjects as $subject)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-accent-600">{{ $subject->subject_code }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('subjects.show', $subject) }}" class="font-medium text-slate-900 hover:text-accent-600">
                                            {{ $subject->subject_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $subject->units }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $subject->grades_count }}</td>
                                    <td class="px-6 py-4">
                                        @include('partials.status-badge', ['status' => $subject->status])
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <x-button variant="view" size="sm" href="{{ route('subjects.show', $subject) }}" icon="eye">View</x-button>
                                            <x-button variant="secondary" size="sm" href="{{ route('subjects.edit', $subject) }}" icon="pencil">Edit</x-button>
                                            <form method="POST" action="{{ route('subjects.destroy', $subject) }}"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $subject->subject_name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger" size="sm" type="submit">Delete</x-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-6">
            {{ $subjects->withQueryString()->links() }}
        </div>
    </div>
@endsection