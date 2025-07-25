@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier la campagne</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('campaigns.update', $campaign) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la campagne</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $campaign->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description', $campaign->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Lieu</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $campaign->location) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date de début</label>
                            <input type="datetime-local" class="form-control" id="date" name="date" value="{{ old('date', $campaign->date ? $campaign->date->format('Y-m-d\TH:i') : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
