@extends('layouts.main')

@section('title', 'Liste des régions')

@section('content')
<div class="py-8">
    <div class=" mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Liste des régions</h1>
        <a href="{{ route('regions.create') }}" class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Ajouter une région</a>
        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nom</th>
                </tr>
            </thead>
            <tbody>
            @forelse($regions as $region)
                <tr>
                    <td class="border px-4 py-2">{{ $region->id }}</td>
                    <td class="border px-4 py-2">{{ $region->name }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center py-4">Aucune région trouvée.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
