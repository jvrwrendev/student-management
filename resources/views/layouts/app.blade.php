<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')){{ !empty($__env->yieldContent('title')) ? ' | ' : '' }}{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand">{{ config('app.name') }}</a>
            <nav class="nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home', 'posts.show') ? 'active' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </nav>
        </div>
    </header>

    <main class="container main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Built with Laravel {{ app()->version() }}.</p>
        </div>
    </footer>
</body>
</html>