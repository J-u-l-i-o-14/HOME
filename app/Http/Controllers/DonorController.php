<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index()
    {
        $donors = Donor::paginate(20);
        return view('donors.index', compact('donors'));
    }

    public function create()
    {
        return view('donors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:donors,email',
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
            'blood_type_id' => 'required|exists:blood_types,id',
            'center_id' => 'required|exists:centers,id',
            'address' => 'required|string|max:255',
        ]);
        if (empty($validated['center_id']) && auth()->user()->center_id) {
            $validated['center_id'] = auth()->user()->center_id;
        }
        $donor = Donor::create($validated);
        return redirect()->route('donors.index')->with('success', 'Donneur créé avec succès.');
    }

    public function show(Donor $donor)
    {
        return view('donors.show', compact('donor'));
    }

    public function edit(Donor $donor)
    {
        return view('donors.edit', compact('donor'));
    }

    public function update(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:donors,email,' . $donor->id,
            'blood_type' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        $donor->update($validated);
        return redirect()->route('donors.index')->with('success', 'Donneur mis à jour avec succès.');
    }

    public function destroy(Donor $donor)
    {
        $donor->delete();
        return redirect()->route('donors.index')->with('success', 'Donneur supprimé avec succès.');
    }
}
