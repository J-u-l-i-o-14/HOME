@extends('layouts.main')

@section('title', 'Modifier le donneur')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Modifier le donneur</h1>
            <form method="POST" action="{{ route('donors.update', $donor) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700">Nom</label>
                    <input type="text" name="name" value="{{ old('name', $donor->name) }}" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $donor->email) }}" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Groupe sanguin</label>
                    <input type="text" name="blood_type" value="{{ old('blood_type', $donor->blood_type) }}" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $donor->phone) }}" class="form-control">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Adresse</label>
                    <input type="text" name="address" value="{{ old('address', $donor->address) }}" class="form-control">
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('donors.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
