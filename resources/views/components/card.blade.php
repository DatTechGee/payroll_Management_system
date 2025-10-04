<!-- Card component -->
@props(['title' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if($title)
        <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    
    <div class="bg-white p-6">
        {{ $slot }}
    </div>
</div>