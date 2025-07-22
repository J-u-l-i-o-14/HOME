@extends('layouts.main')

@section('page-title', 'Modifier un centre')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Modifier un centre</h2>
        <form action="{{ route('centers.update', $center) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name', $center->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="region_id" class="block text-sm font-medium text-gray-700">Région</label>
                <select name="region_id" id="region_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Sélectionner une région</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" @if($center->region_id == $region->id) selected @endif>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" name="address" id="address" value="{{ old('address', $center->address) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">Enregistrer</button>
        </form>
    </div>
@endsection 