@props(['title', 'value', 'icon', 'color' => 'blue', 'trend' => null])

@php
    $colorClasses = [
        'blue' => 'bg-blue-500',
        'red' => 'bg-red-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'purple' => 'bg-purple-500',
        'indigo' => 'bg-indigo-500',
        'pink' => 'bg-pink-500',
        'gray' => 'bg-gray-500',
    ];
    $bgColor = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center">
        <div class="p-3 {{ $bgColor }} rounded-full">
            <i class="{{ $icon }} text-white text-xl"></i>
        </div>
        <div class="ml-4 flex-1">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <div class="flex items-baseline">
                <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
                @if($trend)
                    <span class="ml-2 text-sm {{ $trend > 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-arrow-{{ $trend > 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($trend) }}%
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>