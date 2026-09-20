<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    @php($user = auth()->user())
    @php($isAdmin = $user->isAdmin())
    @php($isTeacher = $user->isTeacher())
    @php($dashboardRoute = $isAdmin ? 'admin.dashboard' : ($isTeacher ? 'teacher.dashboard' : 'portal.dashboard'))
    @php($sidebarLink = fn (string $routeName) => request()->routeIs($routeName) ? 'bg-accent-600/15 text-accent-400' : 'text-slate-400 hover:bg-white/5 hover:text-slate-100')

    <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/40 md:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-slate-800 bg-slate-900 transition-transform duration-200 md:translate-x-0">
        <div class="flex h-16 items-center justify-between border-b border-slate-800 px-6">
            <a href="{{ route($dashboardRoute) }}" class="text-base font-semibold tracking-tight text-white">
                Student Manager
            </a>
            <button type="button" class="text-slate-500 hover:text-slate-300 md:hidden" onclick="toggleSidebar()" aria-label="Close menu">
                <x-icon name="x-mark" class="h-5 w-5" />
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <p class="px-3 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Overview</p>
            <a href="{{ route($dashboardRoute) }}"
                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('*.dashboard') }}">
                <x-icon name="dashboard" class="h-5 w-5" />
                Dashboard
            </a>

            @if ($isAdmin)
                <p class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Records</p>
                <a href="{{ route('students.index') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('students.*') }}">
                    <x-icon name="students" class="h-5 w-5" />
                    Students
                </a>

                <a href="{{ route('courses.index') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('courses.*') }}">
                    <x-icon name="courses" class="h-5 w-5" />
                    Courses
                </a>

                <a href="{{ route('subjects.index') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('subjects.*') }}">
                    <x-icon name="subjects" class="h-5 w-5" />
                    Subjects
                </a>

                <a href="{{ route('teachers.index') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('teachers.*') }}">
                    <x-icon name="teachers" class="h-5 w-5" />
                    Teachers
                </a>
            @endif

            @if ($isAdmin || $isTeacher)
                <p class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Academics</p>

                @if ($isTeacher)
                    <a href="{{ route('grades.index') }}"
                        class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('grades.*') }}">
                        <x-icon name="grades" class="h-5 w-5" />
                        Grades
                    </a>

                    <a href="{{ route('teacher.students') }}"
                        class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('teacher.students') }}">
                        <x-icon name="students" class="h-5 w-5" />
                        My Students
                    </a>
                @endif

                <a href="{{ route('schedules.index') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('schedules.*') }}">
                    <x-icon name="schedules" class="h-5 w-5" />
                    Schedules
                </a>
            @endif

            @if (! $isAdmin && ! $isTeacher)
                <p class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Student</p>
                <a href="{{ route('portal.grades') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('portal.grades') }}">
                    <x-icon name="grades" class="h-5 w-5" />
                    My Grades
                </a>

                <a href="{{ route('portal.schedule') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('portal.schedule') }}">
                    <x-icon name="schedules" class="h-5 w-5" />
                    My Schedule
                </a>
            @endif

            @if ($isAdmin)
                <p class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500">System</p>
                <a href="{{ route('settings.index') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('settings.*') }}">
                    <x-icon name="settings" class="h-5 w-5" />
                    Settings
                </a>
            @endif

            <p class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Account</p>
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium {{ $sidebarLink('profile.*') }}">
                <x-icon name="user-circle" class="h-5 w-5" />
                Profile
            </a>
        </nav>

        <div class="border-t border-slate-800 px-6 py-4">
            <p class="text-xs text-slate-500">{{ config('app.name') }}</p>
        </div>
    </aside>

    <div class="flex min-h-screen flex-col md:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-4 backdrop-blur sm:px-6">
            <div class="flex items-center gap-3">
                <button type="button" class="-ml-2 rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 md:hidden"
                    onclick="toggleSidebar()" aria-label="Open menu">
                    <x-icon name="bars-3" class="h-5 w-5" />
                </button>
                <span class="text-sm font-medium text-slate-700">@yield('title', 'Dashboard')</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="hidden rounded-md text-right sm:block hover:opacity-80" title="Edit profile">
                <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                <p class="text-xs capitalize text-slate-500">{{ $user->role->value }}</p>
            </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button variant="secondary" size="sm" type="submit">
                        Sign out
                    </x-button>
                </form>
            </div>
        </header>

        <main class="flex-1 px-6 py-8 sm:px-8 lg:px-10">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-accent-200 bg-accent-50 px-4 py-3 text-sm text-accent-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Student Management System &middot; Built with Laravel {{ app()->version() }}
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>
</html>