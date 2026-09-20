@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Settings</h1>
            <p class="mt-1 text-sm text-slate-500">Configure system-wide academic rules.</p>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-900">Academic Honors</h2>
        </div>

        <form method="POST" action="{{ route('settings.update') }}" class="px-6 py-5">
            @csrf
            @method('PUT')

            <div class="max-w-md">
                <label for="honors_threshold" class="block text-sm font-medium text-slate-700">Honors Percentage Threshold</label>
                <p class="mt-1 text-sm text-slate-500">
                    A student qualifies for honors when their percentage average in an academic year is at or above this value.
                </p>
                <input type="number" name="honors_threshold" id="honors_threshold" value="{{ old('honors_threshold', $honorsThreshold) }}"
                    step="0.01" min="0" max="100" required
                    class="mt-3 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">

                @error('honors_threshold')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <x-button type="submit" icon="check">
                    Save Settings
                </x-button>
            </div>
        </form>
    </div>
@endsection