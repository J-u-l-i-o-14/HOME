@props(['status', 'type' => 'default'])

@php
    $statusConfig = [
        'appointment' => [
            'scheduled' => ['text' => 'Programmé', 'class' => 'bg-blue-100 text-blue-800'],
            'confirmed' => ['text' => 'Confirmé', 'class' => 'bg-green-100 text-green-800'],
            'completed' => ['text' => 'Terminé', 'class' => 'bg-gray-100 text-gray-800'],
            'cancelled' => ['text' => 'Annulé', 'class' => 'bg-red-100 text-red-800'],
        ],
        'campaign' => [
            'active' => ['text' => 'Active', 'class' => 'bg-green-100 text-green-800'],
            'planned' => ['text' => 'Planifiée', 'class' => 'bg-blue-100 text-blue-800'],
            'completed' => ['text' => 'Terminée', 'class' => 'bg-gray-100 text-gray-800'],
            'cancelled' => ['text' => 'Annulée', 'class' => 'bg-red-100 text-red-800'],
        ],
        'default' => [
            'active' => ['text' => 'Actif', 'class' => 'bg-green-100 text-green-800'],
            'inactive' => ['text' => 'Inactif', 'class' => 'bg-gray-100 text-gray-800'],
            'pending' => ['text' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800'],
        ]
    ];

    $config = $statusConfig[$type][$status] ?? ['text' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
    {{ $config['text'] }}
</span>