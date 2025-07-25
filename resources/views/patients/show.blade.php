@extends('layouts.main')

@section('title', 'Détail du patient')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Détail du patient</h1>
        <ul class="mb-4">
            <li><strong>ID :</strong> {{ $patient->id }}</li>
            <li><strong>Nom :</strong> {{ $patient->name }}</li>
            <li><strong>Groupe sanguin :</strong> {{ $patient->bloodType->group ?? '-' }}</li>
            <li><strong>Centre :</strong> {{ $patient->center->name ?? '-' }}</li>
        </ul>
        <a href="{{ route('patients.edit', $patient) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Modifier</a>
        <a href="{{ route('patients.index') }}" class="ml-4 text-blue-500 underline">Retour à la liste</a>
    </div>
</div>
@endsection
