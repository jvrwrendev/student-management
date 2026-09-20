@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
])

@php
    $variants = [
        'primary' => 'bg-accent-600 text-white hover:bg-accent-700 active:bg-accent-800 focus-visible:outline-accent-600',
        'secondary' => 'bg-white text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 active:bg-slate-100 focus-visible:outline-slate-600',
        'danger' => 'bg-white text-rose-700 ring-1 ring-inset ring-rose-300 hover:bg-rose-50 active:bg-rose-100 focus-visible:outline-rose-600',
        'view' => 'bg-white text-accent-700 ring-1 ring-inset ring-accent-400 hover:bg-accent-50 active:bg-accent-100 focus-visible:outline-accent-600',
        'dark' => 'bg-slate-900 text-white hover:bg-slate-700 active:bg-slate-800 focus-visible:outline-slate-900',
    ];

    $sizes = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
    ];

    $classes = 'inline-flex items-center justify-center gap-1.5 rounded-md font-medium transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50 '.$variants[$variant].' '.$sizes[$size];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <x-icon name="{{ $icon }}" class="h-4 w-4" />
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
        @if ($icon)
            <x-icon name="{{ $icon }}" class="h-4 w-4" />
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif