@extends('layouts.main')

@section('page-title', 'Ajouter un utilisateur')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Ajouter un utilisateur</h2>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="admin">Administrateur</option>
                    <option value="manager">Manager</option>
                    <option value="donor">Donneur</option>
                    <option value="patient">Patient</option>
                    <option value="client">Client</option>
                </select>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="phone" id="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                <input type="date" name="birth_date" id="birth_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" name="address" id="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Genre</label>
                <select name="gender" id="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Sélectionner</option>
                    <option value="M">Homme</option>
                    <option value="F">Femme</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">Créer</button>
        </form>
    </div>
@endsection 