@extends('layouts.main')

@section('page-title', 'Ajouter une poche de sang')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Ajouter une poche de sang</h2>
        <form action="{{ route('blood-bags.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="blood_type_id" class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                <select name="blood_type_id" id="blood_type_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Sélectionner</option>
                    @foreach($bloodTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->group }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="donor_id" class="block text-sm font-medium text-gray-700">Donneur (optionnel)</label>
                <select name="donor_id" id="donor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm donor-select">
                    <option value="">Aucun</option>
                    @foreach($donors as $donor)
                        <option value="{{ $donor->id }}">{{ $donor->full_name }} ({{ $donor->email }})</option>
                    @endforeach
                </select>
                <div class="mt-2 text-xs text-gray-500">Si le donneur n'est pas dans la liste, saisissez son nom&nbsp;:</div>
                <input type="text" name="donor_name" id="donor_name" placeholder="Nom du donneur (optionnel)" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="volume" class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                <input type="number" name="volume" id="volume" min="100" max="500" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="collected_at" class="block text-sm font-medium text-gray-700">Date de collecte</label>
                <input type="date" name="collected_at" id="collected_at" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
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
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Créer</button>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script>
    $(document).ready(function() {
        $('.donor-select').select2({
            placeholder: 'Rechercher un donneur',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush 