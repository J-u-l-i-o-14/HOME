@extends('layouts.main')

@section('page-title', 'Gestion des centres')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Centres</h2>
        <a href="{{ route('centers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter Centre
        </a>
    </div>
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Région</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Adresse</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centers as $center)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $center->name }}</td>
                        <td class="px-4 py-2">{{ optional($center->region)->name }}</td>
                        <td class="px-4 py-2">{{ $center->address }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('centers.edit', $center) }}" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i> Modifier</a>
                            <form action="{{ route('centers.destroy', $center) }}" method="POST" onsubmit="return confirm('Supprimer ce centre ?');">
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
            {{ $centers->links() }}
        </div>
    </div>
@endsection 