<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign in') &middot; {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 text-slate-900 antialiased">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <a href="{{ route('login') }}" class="text-2xl font-semibold tracking-tight text-slate-900">Student Manager</a>
            <p class="mt-1 text-sm text-slate-500">{{ config('app.name') }}</p>
        </div>

        @yield('content')
    </div>
</body>
</html>