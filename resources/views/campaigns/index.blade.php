@extends('layouts.main')

@section('title', 'Campagnes')

@section('content')
<div class="py-8">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Liste des campagnes</h1>
            <a href="{{ route('campaigns.create') }}" class="mb-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Nouvelle campagne</a>
            <table class="min-w-full divide-y divide-gray-200 mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Organisateur</th>
                        <th class="px-4 py-2">Centre</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td class="border px-4 py-2">{{ $campaign->id }}</td>
                        <td class="border px-4 py-2">{{ $campaign->name }}</td>
                        <td class="border px-4 py-2">{{ $campaign->date ? $campaign->date->format('d/m/Y') : '-' }}</td>
                        <td class="border px-4 py-2">{{ $campaign->organizer->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $campaign->center->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ __(ucfirst($campaign->status)) }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('campaigns.show', $campaign) }}" class="text-blue-500 underline">Voir</a>
                            <a href="{{ route('campaigns.edit', $campaign) }}" class="text-yellow-500 underline ml-2">Modifier</a>
                            @if($campaign->isDraft())
                                <form action="{{ route('campaigns.publish', $campaign) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 underline ml-2 bg-transparent border-0 p-0">Publier</button>
                                </form>
                            @elseif($campaign->isPublished())
                                <form action="{{ route('campaigns.archive', $campaign) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 underline ml-2 bg-transparent border-0 p-0">Archiver</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune campagne trouvée.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
