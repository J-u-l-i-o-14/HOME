@extends('layouts.main')

@section('title', 'Modifier la transfusion')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Modifier la transfusion</h1>
        <form method="POST" action="{{ route('transfusions.update', $transfusion) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Patient</label>
                <select name="patient_id" class="w-full border rounded p-2">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @if($patient->id == $transfusion->patient_id) selected @endif>{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Poche de sang</label>
                <select name="blood_bag_id" class="w-full border rounded p-2">
                    @foreach($bloodBags as $bag)
                        <option value="{{ $bag->id }}" @if($bag->id == $transfusion->blood_bag_id) selected @endif>#{{ $bag->id }} ({{ $bag->bloodType->group }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Date de transfusion</label>
                <input type="date" name="transfusion_date" class="w-full border rounded p-2" value="{{ old('transfusion_date', optional($transfusion->transfusion_date)->format('Y-m-d')) }}">
            </div>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
