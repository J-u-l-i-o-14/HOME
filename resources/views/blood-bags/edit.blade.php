@extends('layouts.main')

@section('page-title', 'Modifier une poche de sang')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Modifier une poche de sang</h2>
        <form action="{{ route('blood-bags.update', $bloodBag) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="blood_type_id" class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                <select name="blood_type_id" id="blood_type_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Sélectionner</option>
                    @foreach($bloodTypes as $type)
                        <option value="{{ $type->id }}" @if($bloodBag->blood_type_id == $type->id) selected @endif>{{ $type->group }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="donor_id" class="block text-sm font-medium text-gray-700">Donneur (optionnel)</label>
                <select name="donor_id" id="donor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($donors as $donor)
                        <option value="{{ $donor->id }}" @if($bloodBag->donor_id == $donor->id) selected @endif>{{ $donor->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="volume" class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                <input type="number" name="volume" id="volume" min="100" max="500" value="{{ old('volume', $bloodBag->volume) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="collected_at" class="block text-sm font-medium text-gray-700">Date de collecte</label>
                <input type="date" name="collected_at" id="collected_at" value="{{ old('collected_at', optional($bloodBag->collected_at)->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @foreach(['available'=>'Disponible','reserved'=>'Réservée','transfused'=>'Transfusée','expired'=>'Expirée','discarded'=>'Jetée'] as $value=>$label)
                        <option value="{{ $value }}" @if($bloodBag->status == $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->is_admin || auth()->user()->is_manager)
                <input type="hidden" name="center_id" value="{{ auth()->user()->center_id }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Centre</label>
                    <div class="mt-1">{{ auth()->user()->center->name }}</div>
                </div>
            @else
                <div>
                    <label for="center_id" class="block text-sm font-medium text-gray-700">Centre</label>
                    <select name="center_id" id="center_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">Sélectionner</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}" @if($bloodBag->center_id == $center->id) selected @endif>{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Enregistrer</button>
        </form>
    </div>
@endsection 