@props(['type'])

@php
    $colorClasses = [
        'A+' => 'blood-type-A',
        'A-' => 'blood-type-A',
        'B+' => 'blood-type-B',
        'B-' => 'blood-type-B',
        'AB+' => 'blood-type-AB',
        'AB-' => 'blood-type-AB',
        'O+' => 'blood-type-O',
        'O-' => 'blood-type-O',
    ];
    $colorClass = $colorClasses[$type] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
    {{ $type }}
</span>