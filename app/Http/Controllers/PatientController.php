<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\BloodType;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Patient::with('bloodType');

        // Filtrer par centre pour admin/manager
        if (in_array($user->role, ['admin', 'manager'])) {
            $query->where('center_id', $user->center_id);
        }

        // Filtres
        if ($request->filled('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(15);
        $bloodTypes = BloodType::all();

        return view('patients.index', compact('patients', 'bloodTypes'));
    }

    public function create()
    {
        $bloodTypes = BloodType::all();
        return view('patients.create', compact('bloodTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $data = $request->all();
        if (in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        Patient::create($data);

        return redirect()->route('patients.index')
            ->with('success', 'Patient enregistré avec succès.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['bloodType', 'transfusions.bloodBag']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();
        return view('patients.edit', compact('patient', 'bloodTypes'));
    }

    public function update(Request $request, Patient $patient)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $data = $request->all();
        if (in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        $patient->update($data);

        return redirect()->route('patients.index')
            ->with('success', 'Patient mis à jour avec succès.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->transfusions()->exists()) {
            return redirect()->route('patients.index')
                ->with('error', 'Impossible de supprimer ce patient car il a des transfusions associées.');
        }

        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient supprimé avec succès.');
    }
}