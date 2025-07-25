@extends('layouts.main')

@section('title', 'Nouveau patient')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Nouveau patient</h1>
        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nom</label>
                <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Groupe sanguin</label>
                <select name="blood_type_id" class="w-full border rounded p-2">
                    @foreach($bloodTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->group }}</option>
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
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
