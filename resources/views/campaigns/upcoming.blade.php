@extends('layouts.app')

@section('title', 'Campagnes à venir')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Campagnes de don à venir</h1>
    @if($campaigns->count())
        <div class="row">
            @foreach($campaigns as $campaign)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $campaign->name }}</h5>
                            <p class="card-text mb-1"><strong>Lieu :</strong> {{ $campaign->location }}</p>
                            <p class="card-text mb-1"><strong>Date :</strong> {{ $campaign->date ? $campaign->date->format('d/m/Y H:i') : '-' }}</p>
                            <p class="card-text mb-1"><strong>Centre :</strong> {{ $campaign->center->name ?? '-' }}</p>
                            <p class="card-text">{{ Str::limit($campaign->description, 100) }}</p>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline-primary mt-auto">Voir la campagne</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            {{ $campaigns->links() }}
        </div>
    @else
        <div class="alert alert-info">Aucune campagne à venir pour le moment.</div>
    @endif
</div>
@endsection
