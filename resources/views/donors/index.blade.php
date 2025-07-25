@extends('layouts.main')

@section('title', 'Donneurs')

@section('content')
<div class="py-8">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Liste des donneurs</h1>
            <a href="{{ route('donors.create') }}" class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Nouveau donneur</a>
            <table class="min-w-full divide-y divide-gray-200 mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Groupe sanguin</th>
                        <th class="px-4 py-2">Centre</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($donors as $donor)
                    <tr>
                        <td class="border px-4 py-2">{{ $donor->id }}</td>
                        <td class="border px-4 py-2">{{ $donor->name }}</td>
                        <td class="border px-4 py-2">{{ $donor->bloodType->group ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $donor->center->name ?? '-' }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('donors.show', $donor) }}" class="text-blue-500 underline">Voir</a>
                            <a href="{{ route('donors.edit', $donor) }}" class="text-yellow-500 underline ml-2">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Aucun donneur trouvé.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
