@extends('layouts.public')
@section('title', 'Prendre un rendez-vous')
@section('content')
<div class="flex items-center justify-center p-16 bg-gray-50">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="mb-6 text-center text-2xl font-bold">Prendre un rendez-vous</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('appointment.public.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Campagne</label>
                        <select name="campaign_id" class="form-control" required>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date et heure souhaitées</label>
                        <input type="datetime-local" name="appointment_date" class="form-control" required>
                    </div>
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition">Valider le rendez-vous</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
