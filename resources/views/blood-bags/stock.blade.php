@extends('layouts.main')

@section('page-title', 'Gestion des stocks de sang')

@section('content')
    <h2 class="text-2xl font-semibold mb-6">Stocks de sang par centre</h2>

    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($stockByCenter as $center)
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-bold mb-2 text-red-700 flex items-center">
                    <i class="fas fa-hospital mr-2"></i> {{ $center->name }}
                </h3>
                <div class="mb-2 text-sm text-gray-500">Région : {{ optional($center->region)->name }}</div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 text-left">Groupe</th>
                            <th class="px-2 py-1 text-left">Disponible</th>
                            <th class="px-2 py-1 text-left">Réservé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($center->inventory as $inv)
                            <tr>
                                <td class="px-2 py-1 font-semibold">{{ optional($inv->bloodType)->group }}</td>
                                <td class="px-2 py-1">{{ $inv->available_quantity }}</td>
                                <td class="px-2 py-1">{{ $inv->reserved_quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2 text-red-700 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i> Alertes</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="font-bold text-yellow-700">Poches expirant bientôt</div>
                <div class="text-sm text-yellow-800">{{ $expiringSoonBags }} poche(s) expireront dans les 7 jours.</div>
            </div>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="font-bold text-red-700">Poches expirées</div>
                <div class="text-sm text-red-800">{{ $expiredBags }} poche(s) sont expirées.</div>
            </div>
        </div>
        @if(isset($alerts) && $alerts->count() > 0)
            <div class="mt-6">
                <h4 class="font-semibold text-red-700 mb-2">Alertes actives du centre</h4>
                <ul class="space-y-2">
                    @foreach($alerts as $alert)
                        <li class="bg-red-100 border-l-4 border-red-500 p-3 rounded flex items-center">
                            <i class="fas fa-bell text-red-600 mr-2"></i>
                            <span class="text-red-800">[{{ ucfirst($alert->type) }}] {{ $alert->message }}</span>
                            <span class="ml-auto text-xs text-gray-500">{{ $alert->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="flex justify-end">
        <form action="{{ route('blood-bags.markExpired') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                <i class="fas fa-skull-crossbones mr-2"></i> Marquer les poches expirées
            </button>
        </form>
    </div>
@endsection 