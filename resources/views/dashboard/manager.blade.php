@extends('layouts.main')

@section('title', 'Dashboard Manager')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">Dashboard Manager</h2>

                <!-- Statistiques principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_campaigns'] }}</div>
                        <div class="text-sm text-gray-600">Campagnes totales</div>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['upcoming_campaigns'] }}</div>
                        <div class="text-sm text-gray-600">Campagnes à venir</div>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_appointments'] }}</div>
                        <div class="text-sm text-gray-600">RDV en attente</div>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['total_donors'] }}</div>
                        <div class="text-sm text-gray-600">Donneurs</div>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $stats['total_blood_bags'] }}</div>
                        <div class="text-sm text-gray-600">Poches disponibles</div>
                    </div>
                </div>

                <!-- Alertes -->
                @if($alerts['expired_bags'] > 0 || $alerts['expiring_soon_bags'] > 0 || !empty($alerts['low_stock_types']))
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-red-600">⚠️ Alertes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($alerts['expired_bags'] > 0)
                        <div class="bg-red-100 border border-red-300 p-4 rounded-lg">
                            <div class="text-lg font-bold text-red-600">{{ $alerts['expired_bags'] }}</div>
                            <div class="text-sm text-red-700">Poches expirées</div>
                        </div>
                        @endif
                        @if($alerts['expiring_soon_bags'] > 0)
                        <div class="bg-orange-100 border border-orange-300 p-4 rounded-lg">
                            <div class="text-lg font-bold text-orange-600">{{ $alerts['expiring_soon_bags'] }}</div>
                            <div class="text-sm text-orange-700">Poches expirant bientôt</div>
                        </div>
                        @endif
                        @if(!empty($alerts['low_stock_types']))
                        <div class="bg-yellow-100 border border-yellow-300 p-4 rounded-lg">
                            <div class="text-lg font-bold text-yellow-600">{{ count($alerts['low_stock_types']) }}</div>
                            <div class="text-sm text-yellow-700">Groupes en stock faible</div>
                        </div>
                        @endif
                    </div>
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Stock par groupe sanguin -->
                    <div class="bg-white border rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Stock par groupe sanguin</h3>
                        <div class="space-y-3">
                            @foreach($stockByBloodType as $bloodType => $count)
                            <div class="flex justify-between items-center">
                                <span class="font-medium">{{ $bloodType }}</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $count }} poches
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Prochaines campagnes -->
                    <div class="bg-white border rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Prochaines campagnes</h3>
                        <div class="space-y-3">
                            @forelse($upcomingCampaigns as $campaign)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <div class="font-medium">{{ $campaign->name }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ optional($campaign->campaign_date)->format('d/m/Y H:i') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $campaign->location }}
                                </div>
                            </div>
                            @empty
                            <div class="text-gray-500 text-center py-4">
                                Aucune campagne à venir
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous récents -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Rendez-vous récents</h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Donneur
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Campagne
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentAppointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $appointment->donor->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ optional($appointment->appointment_date)->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $appointment->campaign->name ?? 'Centre' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $appointment->status === 'planifie' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $appointment->status === 'confirme' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $appointment->status === 'complete' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $appointment->status === 'annule' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Aucun rendez-vous récent
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 