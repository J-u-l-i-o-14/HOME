@extends('layouts.main')

@section('title', 'Patients')

@section('content')
<div class="py-8">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Liste des patients</h1>
            <a href="{{ route('patients.create') }}" class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Nouveau patient</a>
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
                @forelse($patients as $patient)
                    <tr>
                        <td class="border px-4 py-2">{{ $patient->id }}</td>
                        <td class="border px-4 py-2">{{ $patient->name }}</td>
                        <td class="border px-4 py-2">{{ $patient->bloodType->group ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $patient->center->name ?? '-' }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('patients.show', $patient) }}" class="text-blue-500 underline">Voir</a>
                            <a href="{{ route('patients.edit', $patient) }}" class="text-yellow-500 underline ml-2">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Aucun patient trouvé.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
