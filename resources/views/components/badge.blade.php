<!-- Badge component -->
@props(['variant' => 'primary'])

@php
    $baseClasses = 'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset';
    
    $variants = [
        'primary' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        'success' => 'bg-green-50 text-green-700 ring-green-600/20',
        'warning' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-red-600/20',
        'info' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
    ];
@endphp

<span {{ $attributes->merge(['class' => $baseClasses . ' ' . $variants[$variant]]) }}>
    {{ $slot }}
</span>