<!-- Avatar component -->
@props(['src' => null, 'name', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10',
        'lg' => 'h-12 w-12',
        'xl' => 'h-14 w-14',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span class="inline-block relative">
    @if($src)
        <img class="{{ $sizeClass }} rounded-full object-cover" src="{{ $src }}" alt="{{ $name }}">
    @else
        <span class="{{ $sizeClass }} rounded-full flex items-center justify-center bg-indigo-600 text-white font-medium">
            {{ substr($name, 0, 2) }}
        </span>
    @endif
    {{ $slot }}
</span>