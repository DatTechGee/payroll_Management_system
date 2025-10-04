<!-- Button component -->
@props(['variant' => 'primary'])

@php
    $baseClasses = 'inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';
    
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-500 focus:ring-indigo-500',
        'secondary' => 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-500 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-500 focus:ring-green-500',
    ];
@endphp

<button {{ $attributes->merge(['class' => $baseClasses . ' ' . $variants[$variant]]) }}>
    {{ $slot }}
</button>