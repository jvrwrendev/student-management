@php
    $value = $value ?? $grade?->grade ?? null;
    $isPassed = $passed ?? ($grade && method_exists($grade, 'isPassed') ? $grade->isPassed() : (float) $value <= 3.00);
@endphp

@if ($value !== null)
    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $isPassed ? 'bg-accent-100 text-accent-700' : 'bg-rose-100 text-rose-700' }}">
        {{ number_format((float) $value, 2) }}
    </span>
@endif