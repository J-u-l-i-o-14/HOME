@extends('layouts.public')
@section('title', 'Prendre un rendez-vous')
@section('content')
<div class="container py-5">
    <h2 class="mb-4">Prendre un rendez-vous</h2>
    <form method="POST" action="{{ route('appointment.public.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Campagne</label>
            <select name="campaign_id" class="form-control" required>
                @foreach($campaigns as $campaign)
                    <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Valider le rendez-vous</button>
    </form>
</div>
@endsection
