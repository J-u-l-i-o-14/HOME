<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\BloodType;
use App\Models\Center;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Donor::with(['bloodType', 'center']);

        // Filtrer par centre pour admin/manager
        if (in_array($user->role, ['admin', 'manager'])) {
            $query->where('center_id', $user->center_id);
        }

        // Filtres
        if ($request->filled('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }
        if ($request->filled('center_id')) {
            $query->where('center_id', $request->center_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $donors = $query->latest()->paginate(15);
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        return view('donors.index', compact('donors', 'bloodTypes', 'centers'));
    }

    public function create()
    {
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        
        return view('donors.create', compact('bloodTypes', 'centers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:donors,email',
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_type_id' => 'required|exists:blood_types,id',
            'address' => 'required|string',
            'emergency_contact' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'last_donation_date' => 'nullable|date|before:today',
        ]);
        $data = $request->all();
        if (in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        Donor::create($data);
        return redirect()->route('donors.index')
            ->with('success', 'Donneur enregistré avec succès.');
    }

    public function show(Donor $donor)
    {
        $donor->load(['bloodType', 'center', 'bloodBags', 'donationHistories.campaign']);
        return view('donors.show', compact('donor'));
    }

    public function edit(Donor $donor)
    {
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        
        return view('donors.edit', compact('donor', 'bloodTypes', 'centers'));
    }

    public function update(Request $request, Donor $donor)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:donors,email,' . $donor->id,
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_type_id' => 'required|exists:blood_types,id',
            'address' => 'required|string',
            'emergency_contact' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'last_donation_date' => 'nullable|date|before:today',
        ]);
        $data = $request->all();
        if (in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        $donor->update($data);
        return redirect()->route('donors.index')
            ->with('success', 'Donneur mis à jour avec succès.');
    }

    public function destroy(Donor $donor)
    {
        if ($donor->bloodBags()->exists()) {
            return redirect()->route('donors.index')
                ->with('error', 'Impossible de supprimer ce donneur car il a des dons associés.');
        }

        $donor->delete();

        return redirect()->route('donors.index')
            ->with('success', 'Donneur supprimé avec succès.');
    }

    public function eligible()
    {
        $donors = Donor::eligible()->with(['bloodType', 'center'])->paginate(15);
        return view('donors.eligible', compact('donors'));
    }
} 