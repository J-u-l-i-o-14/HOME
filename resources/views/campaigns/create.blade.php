@extends('layouts.main')

@section('title', 'Nouvelle campagne')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Nouvelle campagne</h1>
        <form method="POST" action="{{ route('campaigns.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nom de la campagne</label>
                <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Date</label>
                <input type="date" name="date" class="w-full border rounded p-2" value="{{ old('date') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Lieu</label>
                <input type="text" name="location" class="w-full border rounded p-2" value="{{ old('location') }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Organisateur</label>
                <select name="organizer_id" class="w-full border rounded p-2">
                    @foreach($organizers as $organizer)
                        <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Centre</label>
                <select name="center_id" class="w-full border rounded p-2">
                    @foreach($centers as $center)
                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Créer</button>
        </form>
    </div>
</div>
@endsection
