@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white p-8">
        <h1 class="text-xl font-bold tracking-tight text-slate-900">Sign in to your account</h1>
        <p class="mt-1 text-sm text-slate-500">Use your school email and password.</p>

        @if (session('status'))
            <div class="mt-4 rounded-md bg-accent-50 px-4 py-3 text-sm text-accent-700">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                @error('email')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                @error('password')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500">
                    Remember me
                </label>
            </div>

            <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-md bg-accent-600 px-4 py-2 text-sm font-medium text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2">
                Sign in
            </button>
        </form>
    </div>
@endsection