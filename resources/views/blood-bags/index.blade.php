@extends('layouts.main')

@section('page-title', 'Gestion des poches de sang')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h2 class="text-xl font-semibold">Poches de sang</h2>
        <form method="GET" action="" class="flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Recherche donneur..." class="border rounded px-2 py-1 text-sm" />
            <select name="blood_type_id" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous groupes</option>
                @foreach($bloodTypes as $type)
                    <option value="{{ $type->id }}" @if(request('blood_type_id') == $type->id) selected @endif>{{ $type->group }}</option>
                @endforeach
            </select>
            <select name="status" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous statuts</option>
                <option value="available" @if(request('status')=='available') selected @endif>Disponible</option>
                <option value="reserved" @if(request('status')=='reserved') selected @endif>Réservée</option>
                <option value="transfused" @if(request('status')=='transfused') selected @endif>Transfusée</option>
                <option value="expired" @if(request('status')=='expired') selected @endif>Expirée</option>
                <option value="discarded" @if(request('status')=='discarded') selected @endif>Jetée</option>
            </select>
            @if(auth()->user()->is_admin)
                <select name="center_id" class="border rounded px-2 py-1 text-sm">
                    <option value="">Tous centres</option>
                    @foreach($centers as $center)
                        <option value="{{ $center->id }}" @if(request('center_id') == $center->id) selected @endif>{{ $center->name }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-3 py-1 rounded">Filtrer</button>
        </form>
        <a href="{{ route('blood-bags.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter Poche
        </a>
    </div>
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Groupe</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Volume</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Donneur</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date collecte</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Expiration</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Centre</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bloodBags as $bag)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ optional($bag->bloodType)->group }}</td>
                        <td class="px-4 py-2">{{ $bag->volume }} ml</td>
                        <td class="px-4 py-2">{{ optional($bag->donor)->full_name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ optional($bag->collected_at)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ optional($bag->expires_at)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ optional($bag->center)->name }}</td>
                        <td class="px-4 py-2">
                            @switch($bag->status)
                                @case('available') <span class="text-green-600">Disponible</span> @break
                                @case('reserved') <span class="text-yellow-600">Réservée</span> @break
                                @case('transfused') <span class="text-blue-600">Transfusée</span> @break
                                @case('expired') <span class="text-red-600">Expirée</span> @break
                                @case('discarded') <span class="text-gray-600">Jetée</span> @break
                                @default <span>{{ $bag->status }}</span>
                            @endswitch
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('blood-bags.edit', $bag) }}" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i> Modifier</a>
                            <form action="{{ route('blood-bags.destroy', $bag) }}" method="POST" onsubmit="return confirm('Supprimer cette poche ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"><i class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $bloodBags->appends(request()->query())->links() }}
        </div>
    </div>
@endsection 