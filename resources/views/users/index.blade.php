@extends('layouts.main')

@section('page-title', 'Gestion des utilisateurs')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h2 class="text-xl font-semibold">Utilisateurs</h2>
        <form method="GET" action="" class="flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Recherche nom/email..." class="border rounded px-2 py-1 text-sm" />
            <select name="role" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous rôles</option>
                @foreach(['admin','manager','donor','patient','client'] as $role)
                    <option value="{{ $role }}" @if(request('role') == $role) selected @endif>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <select name="center_id" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous centres</option>
                @foreach($centers as $center)
                    <option value="{{ $center->id }}" @if(request('center_id') == $center->id) selected @endif>{{ $center->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-3 py-1 rounded">Filtrer</button>
        </form>
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-user-plus mr-2"></i> Ajouter Utilisateur
        </a>
    </div>
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Centre</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('users.update', $user) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="border rounded px-2 py-1 text-xs" onchange="this.form.submit()">
                                    @foreach(['admin','manager','donor','patient','client'] as $role)
                                        <option value="{{ $role }}" @if($user->role === $role) selected @endif>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-2">{{ $user->center->name ?? '-' }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i> Modifier</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"><i class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
@endsection 