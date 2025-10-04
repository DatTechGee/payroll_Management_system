<!-- Dashboard stat card component -->
@props(['title', 'value', 'icon' => null, 'trend' => null, 'trendValue' => null])

<div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
    <dt>
        @if($icon)
            <div class="absolute rounded-md bg-indigo-500 p-3">
                {{ $icon }}
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">{{ $title }}</p>
        @else
            <p class="truncate text-sm font-medium text-gray-500">{{ $title }}</p>
        @endif
    </dt>
    <dd class="{{ $icon ? 'ml-16' : '' }} flex items-baseline pb-6 sm:pb-7">
        <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
        @if($trend)
            <p class="ml-2 flex items-baseline text-sm font-semibold {{ $trend === 'up' ? 'text-green-600' : 'text-red-600' }}">
                @if($trend === 'up')
                    <svg class="h-5 w-5 flex-shrink-0 self-center text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="h-5 w-5 flex-shrink-0 self-center text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span class="sr-only"> {{ $trend === 'up' ? 'Increased' : 'Decreased' }} by </span>
                {{ $trendValue }}
            </p>
        @endif
    </dd>
</div>