@extends('layouts.main')

@section('title', 'Transfusions')

@section('content')
<div class="py-8">
    <div class=" mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Liste des transfusions</h1>
            <button @click="open = true" class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Nouvelle transfusion</button>

<!-- Modal Alpine.js -->
<div x-data="{ open: false }">
    <template x-if="open">
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
                <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
                <h2 class="text-xl font-bold mb-4">Nouvelle transfusion</h2>
                <form method="POST" action="{{ route('transfusions.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Patient</label>
                        <select name="patient_id" class="w-full border rounded p-2">
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Poche de sang</label>
                        <select name="blood_bag_id" class="w-full border rounded p-2">
                            @foreach($bloodBags as $bag)
                                <option value="{{ $bag->id }}">#{{ $bag->id }} ({{ $bag->bloodType->group }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Date de transfusion</label>
                        <input type="date" name="transfusion_date" class="w-full border rounded p-2" value="{{ old('transfusion_date') }}">
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
                </form>
            </div>
        </div>
    </template>
</div>
            <table class="min-w-full divide-y divide-gray-200 mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Patient</th>
                        <th class="px-4 py-2">Centre</th>
                        <th class="px-4 py-2">Poche</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($transfusions as $transfusion)
                    <tr>
                        <td class="border px-4 py-2">{{ $transfusion->id }}</td>
                        <td class="border px-4 py-2">{{ $transfusion->patient->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $transfusion->bloodBag->center->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $transfusion->bloodBag->id ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $transfusion->created_at->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('transfusions.show', $transfusion) }}" class="text-blue-500 underline">Voir</a>
                            <a href="{{ route('transfusions.edit', $transfusion) }}" class="text-yellow-500 underline ml-2">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune transfusion trouvée.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
