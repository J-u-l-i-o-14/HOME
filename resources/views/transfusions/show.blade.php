@extends('layouts.main')

@section('title', 'Détail de la transfusion')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Détail de la transfusion</h1>
        <ul class="mb-4">
            <li><strong>ID :</strong> {{ $transfusion->id }}</li>
            <li><strong>Patient :</strong> {{ $transfusion->patient->name ?? '-' }}</li>
            <li><strong>Poche de sang :</strong> #{{ $transfusion->bloodBag->id ?? '-' }} ({{ $transfusion->bloodBag->bloodType->group ?? '-' }})</li>
            <li><strong>Date :</strong> {{ optional($transfusion->transfusion_date ?? $transfusion->created_at)->format('d/m/Y') }}</li>
            <li><strong>Centre :</strong> {{ $transfusion->bloodBag->center->name ?? '-' }}</li>
        </ul>
        <a href="{{ route('transfusions.edit', $transfusion) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Modifier</a>
        <a href="{{ route('transfusions.index') }}" class="ml-4 text-blue-500 underline">Retour à la liste</a>
    </div>
</div>
@endsection
