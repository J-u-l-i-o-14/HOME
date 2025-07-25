@extends('layouts.main')

@section('title', 'Nouvelle transfusion')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Nouvelle transfusion</h1>
        <form method="POST" action="{{ route('transfusions.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Patient</label>
                <select name="patient_id" class="w-full border rounded p-2">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Poche de sang</label>
                <select name="blood_bag_id" class="w-full border rounded p-2">
                    @foreach($bloodBags as $bag)
                        <option value="{{ $bag->id }}">#{{ $bag->id }} ({{ $bag->bloodType->group }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Date de transfusion</label>
                <input type="date" name="transfusion_date" class="w-full border rounded p-2" value="{{ old('transfusion_date') }}">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
