<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = User::query();

        // Filtrer par centre pour admin/manager
        if (in_array($user->role, ['admin', 'manager'])) {
            $query->where('center_id', $user->center_id);
        }

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('center_id')) {
            $query->where('center_id', $request->center_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $query->latest()->paginate(15);
        $centers = \App\Models\Center::all();
        return view('users.index', compact('users', 'centers'));
    }

    public function create()
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('users.create', compact('bloodTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,donor,patient,client',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:M,F',
        ]);
        $data = $request->all();
        if (in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load(['donations', 'appointments', 'bloodBags']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('users.edit', compact('user', 'bloodTypes'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,donor,patient,client',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:M,F',
        ]);
        $updateData = $request->except(['password', 'password_confirmation']);
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        if (in_array($authUser->role, ['admin', 'manager'])) {
            $updateData['center_id'] = $authUser->center_id;
        }
        $user->update($updateData);
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // Empêcher la suppression si l'utilisateur a des relations importantes
        if ($user->donations()->exists() || $user->transfusions()->exists() || $user->organizedCampaigns()->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il a des données associées.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}