@php
    $isActive = ($status ?? 'inactive') === 'active';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $isActive ? 'bg-accent-100 text-accent-700' : 'bg-slate-100 text-slate-600' }}">
    {{ $isActive ? 'Active' : 'Inactive' }}
</span>