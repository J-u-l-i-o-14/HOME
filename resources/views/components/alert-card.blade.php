@props(['type' => 'warning', 'title', 'items' => []])

@php
    $typeClasses = [
        'danger' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'success' => 'bg-green-50 border-green-200 text-green-800',
    ];
    $iconClasses = [
        'danger' => 'fas fa-exclamation-triangle text-red-500',
        'warning' => 'fas fa-exclamation-circle text-yellow-500',
        'info' => 'fas fa-info-circle text-blue-500',
        'success' => 'fas fa-check-circle text-green-500',
    ];
    $classes = $typeClasses[$type] ?? $typeClasses['warning'];
    $iconClass = $iconClasses[$type] ?? $iconClasses['warning'];
@endphp

<div class="rounded-lg border p-4 {{ $classes }}">
    <div class="flex items-start">
        <i class="{{ $iconClass }} mr-3 mt-1"></i>
        <div class="flex-1">
            <h3 class="font-medium mb-2">{{ $title }}</h3>
            @if(count($items) > 0)
                <ul class="space-y-1">
                    @foreach($items as $item)
                        <li class="flex items-center">
                            <span class="w-1.5 h-1.5 bg-current rounded-full mr-2"></span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>