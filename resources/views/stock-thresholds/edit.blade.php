@extends('layouts.main')

@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-xl font-bold mb-6">Modifier un seuil d'alerte</h2>
    <form action="{{ route('stock-thresholds.update', $stockThreshold) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="blood_type_id" class="block text-gray-700">Groupe sanguin</label>
            <select name="blood_type_id" id="blood_type_id" class="form-select w-full" required>
                <option value="">Sélectionner</option>
                @foreach($bloodTypes as $type)
                    <option value="{{ $type->id }}" {{ (old('blood_type_id', $stockThreshold->blood_type_id) == $type->id) ? 'selected' : '' }}>
                        {{ $type->label }}
                    </option>
                @endforeach
            </select>
            @error('blood_type_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="warning_threshold" class="block text-gray-700">Seuil warning</label>
            <input type="number" name="warning_threshold" id="warning_threshold" min="1" class="form-input w-full" value="{{ old('warning_threshold', $stockThreshold->warning_threshold) }}" required>
            @error('warning_threshold')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="critical_threshold" class="block text-gray-700">Seuil critique</label>
            <input type="number" name="critical_threshold" id="critical_threshold" min="1" class="form-input w-full" value="{{ old('critical_threshold', $stockThreshold->critical_threshold) }}" required>
            @error('critical_threshold')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
