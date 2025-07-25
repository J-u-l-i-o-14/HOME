@extends('layouts.main')

@section('title', 'Détail du donneur')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Détail du donneur</h1>
            <ul class="mb-6">
                <li><strong>Nom :</strong> {{ $donor->name }}</li>
                <li><strong>Email :</strong> {{ $donor->email }}</li>
                <li><strong>Groupe sanguin :</strong> {{ $donor->blood_type }}</li>
                <li><strong>Téléphone :</strong> {{ $donor->phone ?? '-' }}</li>
                <li><strong>Adresse :</strong> {{ $donor->address ?? '-' }}</li>
            </ul>
            <a href="{{ route('donors.edit', $donor) }}" class="btn btn-warning mr-2">Modifier</a>
            <form method="POST" action="{{ route('donors.destroy', $donor) }}" class="d-inline" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce donneur ?')">Supprimer</button>
            </form>
            <a href="{{ route('donors.index') }}" class="btn btn-secondary ml-2">Retour</a>
        </div>
    </div>
</div>
@endsection
