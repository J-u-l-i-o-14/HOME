<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservationRequest;
use App\Models\ReservationItem;
use App\Models\ReservationBloodBag;
use App\Models\Center;
use App\Models\BloodType;
use App\Models\BloodBag;
use App\Models\CenterBloodTypeInventory;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = ReservationRequest::with(['user', 'center', 'items.bloodType']);

        if ($user->role === 'client') {
            $query->where('user_id', $user->id);
        } elseif (in_array($user->role, ['admin', 'manager'])) {
            $query->where('center_id', $user->center_id);
        }

        $reservations = $query->latest()->paginate(15);
        $centers = Center::all();

        return view('reservations.index', compact('reservations', 'centers'));
    }

    public function create()
    {
        $centers = Center::all();
        $bloodTypes = BloodType::all();
        
        return view('reservations.create', compact('centers', 'bloodTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.blood_type_id' => 'required|exists:blood_types,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $centerId = $user->role === 'client' ? $request->center_id : $user->center_id;
        // Vérifier la disponibilité
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $available = CenterBloodTypeInventory::where('center_id', $centerId)
                ->where('blood_type_id', $item['blood_type_id'])
                ->value('available_quantity') ?? 0;
            if ($available < $item['quantity']) {
                return back()->withErrors(['items' => "Stock insuffisant pour le groupe sanguin sélectionné."]);
            }
            $totalAmount += $item['quantity'] * 50;
        }
        // Créer la réservation
        $reservation = ReservationRequest::create([
            'user_id' => $user->id,
            'center_id' => $centerId,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
        ]);
        // Créer les items
        foreach ($request->items as $item) {
            ReservationItem::create([
                'request_id' => $reservation->id,
                'blood_type_id' => $item['blood_type_id'],
                'quantity' => $item['quantity'],
            ]);
        }
        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Demande de réservation créée avec succès.');
    }

    public function show(ReservationRequest $reservation)
    {
        $reservation->load(['user', 'center', 'items.bloodType', 'payments', 'documents']);
        
        return view('reservations.show', compact('reservation'));
    }

    public function confirm(ReservationRequest $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Cette réservation ne peut pas être confirmée.');
        }

        // Vérifier le paiement de 50%
        $requiredPayment = $reservation->total_amount * 0.5;
        if ($reservation->paid_amount < $requiredPayment) {
            return back()->with('error', 'Le paiement de 50% est requis pour confirmer la réservation.');
        }

        // Confirmer la réservation
        $reservation->update([
            'status' => 'confirmed',
            'expires_at' => Carbon::now()->addHours(72), // 72 heures
        ]);

        // Réserver les poches spécifiques
        $this->reserveBloodBags($reservation);

        // Créer un audit
        $reservation->audits()->create([
            'user_id' => auth()->id(),
            'action' => 'confirmed',
            'notes' => 'Réservation confirmée par le gestionnaire',
        ]);

        return back()->with('success', 'Réservation confirmée avec succès.');
    }

    private function reserveBloodBags($reservation)
    {
        foreach ($reservation->items as $item) {
            $bloodBags = BloodBag::where('center_id', $reservation->center_id)
                ->where('blood_type_id', $item->blood_type_id)
                ->where('status', 'available')
                ->limit($item->quantity)
                ->get();

            foreach ($bloodBags as $bloodBag) {
                $bloodBag->update(['status' => 'reserved']);
                
                ReservationBloodBag::create([
                    'reservation_id' => $reservation->id,
                    'blood_bag_id' => $bloodBag->id,
                ]);
            }

            // Mettre à jour l'inventaire
            $this->updateInventory($reservation->center_id, $item->blood_type_id);
        }
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