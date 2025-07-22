@extends('layouts.app')

@section('title', 'Dashboard Client')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">Dashboard Client</h2>

                <!-- Statistiques principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['available_blood_bags'] }}</div>
                        <div class="text-sm text-gray-600">Poches disponibles</div>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['upcoming_campaigns'] }}</div>
                        <div class="text-sm text-gray-600">Campagnes à venir</div>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['total_donors'] }}</div>
                        <div class="text-sm text-gray-600">Donneurs actifs</div>
                    </div>
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
                            <div class="border-l-4 border-green-500 pl-4">
                                <div class="font-medium">{{ $campaign->name }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ optional($campaign->campaign_date)->format('d/m/Y H:i') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $campaign->location }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Organisé par {{ optional($campaign->organizer)->name }}
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

                <!-- Poches de sang disponibles -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Poches de sang disponibles</h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Groupe sanguin
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Donneur
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date de collecte
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Expiration
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($availableBloodBags as $bloodBag)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $bloodBag->blood_type }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ optional($bloodBag->donor)->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ optional($bloodBag->collection_date)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ optional($bloodBag->expiration_date)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disponible
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Aucune poche de sang disponible
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