<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Center;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Campaign::with(['center', 'organizer']);

        // Filtrer par centre pour admin/manager
        if (in_array($user->role, ['admin', 'manager']) && $user->center_id) {
            $query->where('center_id', $user->center_id);
        }

        // Filtres
        if ($request->filled('center_id')) {
            $query->where('center_id', $request->center_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->latest()->paginate(15);
        $centers = Center::all();

        return view('campaigns.index', compact('campaigns', 'centers'));
    }

    public function create()
    {
        $centers = Center::all();
        $organizers = \App\Models\User::whereIn('role', ['admin', 'manager'])->get();
        return view('campaigns.create', compact('centers', 'organizers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date|after:now',
            'end_date' => 'nullable|date|after:date',
            'center_id' => 'required|exists:centers,id',
        ]);
        $data = $request->all();
        // Si le user a un center_id par défaut et rien n'est envoyé, fallback
        if (empty($data['center_id']) && in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        Campaign::create($data);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne créée avec succès.');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['center', 'donationHistories.donor', 'donationHistories.bloodBag']);
        return view('campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $centers = Center::all();
        return view('campaigns.edit', compact('campaign', 'centers'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after:date',
        ]);
        $data = $request->all();
        if (in_array($user->role, ['admin', 'manager'])) {
            $data['center_id'] = $user->center_id;
        }
        $campaign->update($data);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne mise à jour avec succès.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->donationHistories()->exists()) {
            return redirect()->route('campaigns.index')
                ->with('error', 'Impossible de supprimer cette campagne car elle a des dons associés.');
        }

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campagne supprimée avec succès.');
    }

    /**
     * Affiche les campagnes à venir (publiques, statut published, date future)
     */
    public function upcomingPublic()
    {
        $now = now();
        $campaigns = \App\Models\Campaign::where('status', 'published')
            ->where('date', '>=', $now)
            ->orderBy('date')
            ->with('center')
            ->paginate(12);
        return view('campaigns.upcoming', compact('campaigns'));
    }

    public function archive(Campaign $campaign)
    {
        $campaign->archive();
        return redirect()->route('campaigns.index')->with('success', 'Campagne archivée avec succès.');
    }
}