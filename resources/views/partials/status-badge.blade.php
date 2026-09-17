@php
    $isActive = ($status ?? 'inactive') === 'active';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
    {{ $isActive ? 'Active' : 'Inactive' }}
</span>