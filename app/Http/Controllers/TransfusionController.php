<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfusion;
use App\Models\Patient;
use App\Models\BloodBag;
use App\Models\Center;

class TransfusionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Transfusion::with(['patient', 'bloodBag.center', 'bloodBag.bloodType']);

        // Filtrer par centre pour admin/manager
        if (in_array($user->role, ['admin', 'manager'])) {
            $query->whereHas('bloodBag', function($q) use ($user) {
                $q->where('center_id', $user->center_id);
            });
        }

        // Filtres
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('center_id')) {
            $query->whereHas('bloodBag', function($q) use ($request) {
                $q->where('center_id', $request->center_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $transfusions = $query->latest()->paginate(15);
        $patients = Patient::all();
        $centers = Center::all();

        return view('transfusions.index', compact('transfusions', 'patients', 'centers'));
    }

    public function create()
    {
        $patients = Patient::all();
        $bloodBags = BloodBag::available()->with(['bloodType', 'center'])->get();
        
        return view('transfusions.create', compact('patients', 'bloodBags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'blood_bag_id' => 'required|exists:blood_bags,id',
            'transfusion_date' => 'required|date|before_or_equal:today',
            'volume_transfused' => 'required|numeric|min:100|max:500',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que la poche est disponible
        $bloodBag = BloodBag::findOrFail($request->blood_bag_id);
        if ($bloodBag->status !== 'available') {
            return back()->withErrors(['blood_bag_id' => 'Cette poche de sang n\'est pas disponible.']);
        }

        // Créer la transfusion
        $transfusion = Transfusion::create([
            'patient_id' => $request->patient_id,
            'blood_bag_id' => $request->blood_bag_id,
            'transfusion_date' => $request->transfusion_date,
            'volume_transfused' => $request->volume_transfused,
            'notes' => $request->notes,
        ]);

        // Marquer la poche comme utilisée
        $bloodBag->update(['status' => 'transfused']);

        // Mettre à jour l'inventaire
        $this->updateInventory($bloodBag->center_id, $bloodBag->blood_type_id);

        return redirect()->route('transfusions.index')
            ->with('success', 'Transfusion enregistrée avec succès.');
    }

    public function show(Transfusion $transfusion)
    {
        $transfusion->load(['patient', 'bloodBag.bloodType', 'bloodBag.center', 'bloodBag.donor']);
        return view('transfusions.show', compact('transfusion'));
    }

    public function edit(Transfusion $transfusion)
    {
        $patients = Patient::all();
        $bloodBags = BloodBag::available()->with(['bloodType', 'center'])->get();
        
        return view('transfusions.edit', compact('transfusion', 'patients', 'bloodBags'));
    }

    public function update(Request $request, Transfusion $transfusion)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'blood_bag_id' => 'required|exists:blood_bags,id',
            'transfusion_date' => 'required|date',
            'volume_transfused' => 'required|numeric|min:100|max:500',
            'notes' => 'nullable|string',
        ]);

        $transfusion->update($request->all());

        return redirect()->route('transfusions.index')
            ->with('success', 'Transfusion mise à jour avec succès.');
    }

    public function destroy(Transfusion $transfusion)
    {
        $transfusion->delete();

        return redirect()->route('transfusions.index')
            ->with('success', 'Transfusion supprimée avec succès.');
    }

    private function updateInventory($centerId, $bloodTypeId)
    {
        $availableCount = BloodBag::where('center_id', $centerId)
            ->where('blood_type_id', $bloodTypeId)
            ->where('status', 'available')
            ->count();

        \App\Models\CenterBloodTypeInventory::updateOrCreate(
            [
                'center_id' => $centerId,
                'blood_type_id' => $bloodTypeId,
            ],
            [
                'available_quantity' => $availableCount,
            ]
        );
    }
}