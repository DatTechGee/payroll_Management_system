<!-- Form group component -->
@props(['label', 'for', 'error' => false])

<div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
    <label for="{{ $for }}" class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5">
        {{ $label }}
    </label>
    <div class="mt-2 sm:col-span-2 sm:mt-0">
        {{ $slot }}
        @if($error)
            <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
        @endif
    </div>
</div>