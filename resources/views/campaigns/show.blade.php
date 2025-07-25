@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détail de la campagne</div>
                <div class="card-body">
                    <h3>{{ $campaign->name }}</h3>
                    <p><strong>Description :</strong> {{ $campaign->description }}</p>
                    <p><strong>Lieu :</strong> {{ $campaign->location }}</p>
                    <p><strong>Date de début :</strong> {{ $campaign->date ? $campaign->date->format('d/m/Y H:i') : '-' }}</p>
                    <p><strong>Statut :</strong> {{ __(ucfirst($campaign->status)) }}</p>
                    <div class="mb-3">
                        @if($campaign->isDraft())
                            <form action="{{ route('campaigns.publish', $campaign) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Publier</button>
                            </form>
                        @elseif($campaign->isPublished())
                            <form action="{{ route('campaigns.archive', $campaign) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Archiver</button>
                            </form>
                        @endif
                    </div>
                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning">Modifier</a>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
