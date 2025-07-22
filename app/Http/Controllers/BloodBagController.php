<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodBag;
use App\Models\BloodType;
use App\Models\Center;
use App\Models\Donor;
use App\Models\CenterBloodTypeInventory;
use Carbon\Carbon;

class BloodBagController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BloodBag::with(['bloodType', 'center', 'donor']);

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
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('donor', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        $bloodBags = $query->latest()->paginate(15);
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        return view('blood-bags.index', compact('bloodBags', 'bloodTypes', 'centers'));
    }

    public function create()
    {
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        $donors = Donor::all();

        return view('blood-bags.create', compact('bloodTypes', 'centers', 'donors'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'donor_id' => 'nullable|exists:donors,id',
            'donor_name' => 'nullable|string|max:255',
            'volume' => 'required|numeric|min:100|max:500',
            'collected_at' => 'required|date',
        ]);
        // Exiger un donneur (sélection ou nom)
        if (empty($request->donor_id) && empty($request->donor_name)) {
            return back()->withErrors(['donor_id' => 'Vous devez sélectionner un donneur ou saisir un nom.'])->withInput();
        }
        $centerId = $user->center_id;
        // Si aucun donneur sélectionné mais nom fourni, créer un donneur minimal
        $donorId = $request->donor_id;
        if (!$donorId && $request->donor_name) {
            $donor = \App\Models\Donor::create([
                'first_name' => $request->donor_name,
                'last_name' => '',
                'center_id' => $centerId,
                'blood_type_id' => $request->blood_type_id,
                'gender' => 'other',
            ]);
            $donorId = $donor->id;
        }
        // Calculer la date d'expiration (42 jours après la collecte)
        $collectedAt = \Carbon\Carbon::parse($request->collected_at);
        $expiresAt = $collectedAt->copy()->addDays(42);
        $bloodBag = \App\Models\BloodBag::create([
            'blood_type_id' => $request->blood_type_id,
            'center_id' => $centerId,
            'donor_id' => $donorId,
            'volume' => $request->volume,
            'collected_at' => $collectedAt,
            'expires_at' => $expiresAt,
            'status' => 'available',
        ]);
        // Mettre à jour l'inventaire
        $this->updateInventory($centerId, $request->blood_type_id);
        return redirect()->route('blood-bags.index')
            ->with('success', 'Poche de sang créée avec succès.');
    }

    public function show(BloodBag $bloodBag)
    {
        $bloodBag->load(['bloodType', 'center', 'donor', 'transfusion', 'donationHistory']);
        return view('blood-bags.show', compact('bloodBag'));
    }

    public function edit(BloodBag $bloodBag)
    {
        $bloodTypes = BloodType::all();
        $centers = Center::all();
        $donors = Donor::all();

        return view('blood-bags.edit', compact('bloodBag', 'bloodTypes', 'centers', 'donors'));
    }

    public function update(Request $request, BloodBag $bloodBag)
    {
        $user = auth()->user();
        $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'donor_id' => 'nullable|exists:donors,id',
            'volume' => 'required|numeric|min:100|max:500',
            'collected_at' => 'required|date',
            'status' => 'required|in:available,reserved,transfused,expired,discarded',
        ]);
        $centerId = $user->center_id;
        $collectedAt = Carbon::parse($request->collected_at);
        $expiresAt = $collectedAt->copy()->addDays(42);
        $bloodBag->update([
            'blood_type_id' => $request->blood_type_id,
            'center_id' => $centerId,
            'donor_id' => $request->donor_id,
            'volume' => $request->volume,
            'collected_at' => $collectedAt,
            'expires_at' => $expiresAt,
            'status' => $request->status,
        ]);
        // Mettre à jour l'inventaire
        $this->updateInventory($centerId, $request->blood_type_id);
        return redirect()->route('blood-bags.index')
            ->with('success', 'Poche de sang mise à jour avec succès.');
    }

    public function destroy(BloodBag $bloodBag)
    {
        $bloodBag->delete();

        // Mettre à jour l'inventaire
        $this->updateInventory($bloodBag->center_id, $bloodBag->blood_type_id);

        return redirect()->route('blood-bags.index')
            ->with('success', 'Poche de sang supprimée avec succès.');
    }

    public function stock()
    {
        $user = auth()->user();
        if (in_array($user->role, ['admin', 'manager'])) {
            $stockByCenter = Center::with(['inventory.bloodType'])
                ->where('id', $user->center_id)
                ->get();
            $alerts = \App\Models\Alert::where('center_id', $user->center_id)
                ->where('resolved', false)
                ->latest()
                ->get();
        } else {
            $stockByCenter = Center::with(['inventory.bloodType'])->get();
            $alerts = \App\Models\Alert::where('resolved', false)->latest()->get();
        }
        $expiredBags = BloodBag::expired()
            ->when(in_array($user->role, ['admin', 'manager']), function($q) use ($user) {
                $q->where('center_id', $user->center_id);
            })
            ->count();
        $expiringSoonBags = BloodBag::expiringSoon()
            ->when(in_array($user->role, ['admin', 'manager']), function($q) use ($user) {
                $q->where('center_id', $user->center_id);
            })
            ->count();
        return view('blood-bags.stock', compact('stockByCenter', 'expiredBags', 'expiringSoonBags', 'alerts'));
    }

    public function markExpired()
    {
        $expiredBags = BloodBag::available()
            ->where('expires_at', '<', Carbon::now())
            ->update(['status' => 'expired']);

        return redirect()->back()
            ->with('success', "{$expiredBags} poches marquées comme expirées.");
    }

    private function updateInventory($centerId, $bloodTypeId)
    {
        $availableCount = BloodBag::where('center_id', $centerId)
            ->where('blood_type_id', $bloodTypeId)
            ->where('status', 'available')
            ->count();

        CenterBloodTypeInventory::updateOrCreate(
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