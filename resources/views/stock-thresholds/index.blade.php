@extends('layouts.main')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Seuils d'alerte par groupe sanguin</h2>
        <a href="{{ route('stock-thresholds.create') }}" class="btn btn-primary">Ajouter un seuil</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Groupe sanguin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seuil warning</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seuil critique</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($thresholds as $threshold)
                    <tr>
                        <td class="px-6 py-4">{{ $threshold->bloodType->label ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $threshold->warning_threshold }}</td>
                        <td class="px-6 py-4">{{ $threshold->critical_threshold }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('stock-thresholds.edit', $threshold) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="{{ route('stock-thresholds.destroy', $threshold) }}" method="POST" onsubmit="return confirm('Supprimer ce seuil ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucun seuil configuré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection