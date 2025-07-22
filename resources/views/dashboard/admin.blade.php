@extends('layouts.main')

@section('page-title', 'Dashboard Administrateur')

@section('content')
    <!-- Bouton Gérer les utilisateurs -->
    <div class="mb-8 flex justify-end">
        <a href="{{ route('users.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow inline-flex items-center">
            <i class="fas fa-users mr-2"></i> Gérer les utilisateurs
        </a>
    </div>
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            title="Total Donneurs" 
            :value="$stats['total_donors']" 
            icon="fas fa-users" 
            color="blue" />
        <x-stat-card 
            title="Poches Disponibles" 
            :value="$stats['total_blood_bags']" 
            icon="fas fa-tint" 
            color="red" />
        <x-stat-card 
            title="Dons ce Mois" 
            :value="$stats['total_donations_this_month']" 
            icon="fas fa-heart" 
            color="green" />
        <x-stat-card 
            title="Transfusions ce Mois" 
            :value="$stats['total_transfusions_this_month']" 
            icon="fas fa-syringe" 
            color="purple" />
    </div>

            <!-- Alertes -->
    @if($alerts['expired_bags'] > 0 || $alerts['expiring_soon_bags'] > 0 || count($alerts['low_stock_types']) > 0)
        @php
            $alertItems = [];
            if($alerts['expired_bags'] > 0) $alertItems[] = $alerts['expired_bags'] . ' poche(s) expirée(s)';
            if($alerts['expiring_soon_bags'] > 0) $alertItems[] = $alerts['expiring_soon_bags'] . ' poche(s) expire(nt) bientôt';
            if(count($alerts['low_stock_types']) > 0) $alertItems[] = 'Stock faible pour les groupes : ' . implode(', ', array_keys($alerts['low_stock_types']));
        @endphp
        <div class="mb-8">
            <x-alert-card type="danger" title="🚨 Alertes Critiques" :items="$alertItems" />
        </div>
    @endif

    @if(isset($alerts['active_alerts']) && $alerts['active_alerts']->count() > 0)
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-2 text-red-700 flex items-center"><i class="fas fa-bell mr-2"></i> Alertes actives du centre</h3>
            <ul class="space-y-2">
                @foreach($alerts['active_alerts'] as $alert)
                    <li class="bg-red-100 border-l-4 border-red-500 p-3 rounded flex items-center">
                        <i class="fas fa-bell text-red-600 mr-2"></i>
                        <span class="text-red-800">[{{ ucfirst($alert->type) }}] {{ $alert->message }}</span>
                        <span class="ml-auto text-xs text-gray-500">{{ $alert->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Stock par groupe sanguin -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Stock par Groupe Sanguin</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $type)
                    @php
                        $count = $stockByBloodType[$type] ?? 0;
                        $alertClass = $count < 5 ? 'text-red-600' : ($count < 10 ? 'text-yellow-600' : 'text-green-600');
                    @endphp
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <x-blood-type-badge :type="$type" />
                        <span class="font-bold {{ $alertClass }}">{{ $count }} poches</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Graphique des dons -->
        <x-chart-card 
            title="Évolution des Dons (6 derniers mois)" 
            type="line" 
            :data="$donationsChart['data']" 
            :labels="$donationsChart['labels']" 
            id="donationsChart" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Prochaines campagnes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Prochaines Campagnes</h3>
            @if($upcomingCampaigns->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingCampaigns as $campaign)
                        <div class="border-l-4 border-red-500 pl-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $campaign->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $campaign->location }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>{{ optional($campaign->campaign_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <x-status-badge :status="$campaign->status" type="campaign" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucune campagne prévue</p>
            @endif
        </div>

        <!-- Rendez-vous récents -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rendez-vous Récents</h3>
            @if($recentAppointments->count() > 0)
                <div class="space-y-4">
                    @foreach($recentAppointments->take(5) as $appointment)
                        <div class="border-l-4 border-blue-500 pl-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $appointment->donor->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $appointment->type_display }}
                                        @if($appointment->campaign)
                                            - {{ $appointment->campaign->name }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $appointment->formatted_date }}</p>
                                </div>
                                <x-status-badge :status="$appointment->status" type="appointment" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucun rendez-vous récent</p>
            @endif
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors duration-200">
            <i class="fas fa-user-plus mr-2"></i>Ajouter Donneur
        </a>
        <a href="{{ route('blood-bags.create') }}" class="bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors duration-200">
            <i class="fas fa-tint mr-2"></i>Ajouter Poche
        </a>
        <a href="{{ route('campaigns.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors duration-200">
            <i class="fas fa-bullhorn mr-2"></i>Nouvelle Campagne
        </a>
        <a href="{{ route('blood-bags.stock') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors duration-200">
            <i class="fas fa-chart-bar mr-2"></i>Voir Stock
        </a>
    </div>
@endsection