@extends('layouts.main')

@section('title', 'Ajouter une région')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Ajouter une région</h1>
        @if(session('success'))
            <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('regions.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nom de la région</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
    </div>
</div>
@endsection
