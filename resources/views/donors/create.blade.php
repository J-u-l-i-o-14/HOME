@extends('layouts.main')

@section('title', 'Ajouter un donneur')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Ajouter un donneur</h1>
        <form method="POST" action="{{ route('donors.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Prénom</label>
                <input type="text" name="first_name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nom</label>
                <input type="text" name="last_name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Téléphone</label>
                <input type="text" name="phone" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Date de naissance</label>
                <input type="date" name="birthdate" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Sexe</label>
                <select name="gender" class="w-full border rounded p-2" required>
                    <option value="">Sélectionner</option>
                    <option value="male">Homme</option>
                    <option value="female">Femme</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Groupe sanguin</label>
                <select name="blood_type_id" class="w-full border rounded p-2" required>
                    <option value="">Choisir...</option>
                    @foreach($bloodTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Centre</label>
                <select name="center_id" class="w-full border rounded p-2" required>
                    <option value="" disabled {{ old('center_id') ? '' : 'selected' }}>Choisir...</option>
                    @foreach($centers as $center)
                        <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Adresse</label>
                <input type="text" name="address" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
    </div>
</div>
@endsection
