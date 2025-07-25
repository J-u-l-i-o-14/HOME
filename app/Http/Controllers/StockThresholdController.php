<?php

namespace App\Http\Controllers;

use App\Models\StockThreshold;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockThresholdController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $thresholds = StockThreshold::with('bloodType')
            ->where('center_id', $user->center_id)
            ->get();

        return view('stock-thresholds.index', compact('thresholds'));
    }

    public function create()
    {
        $bloodTypes = BloodType::all();
        return view('stock-thresholds.create', compact('bloodTypes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id|unique:stock_thresholds,blood_type_id,NULL,id,center_id,' . $user->center_id,
            'warning_threshold' => 'required|integer|min:1',
            'critical_threshold' => 'required|integer|min:1|lt:warning_threshold',
        ]);

        StockThreshold::create([
            'center_id' => $user->center_id,
            'blood_type_id' => $request->blood_type_id,
            'warning_threshold' => $request->warning_threshold,
            'critical_threshold' => $request->critical_threshold,
        ]);

        return redirect()->route('stock-thresholds.index')->with('success', 'Seuil ajouté avec succès.');
    }

    public function edit(StockThreshold $stockThreshold)
    {
        $this->authorizeThreshold($stockThreshold);
        $bloodTypes = BloodType::all();
        return view('stock-thresholds.edit', compact('stockThreshold', 'bloodTypes'));
    }

    public function update(Request $request, StockThreshold $stockThreshold)
    {
        $this->authorizeThreshold($stockThreshold);
        $user = Auth::user();
        $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id|unique:stock_thresholds,blood_type_id,' . $stockThreshold->id . ',id,center_id,' . $user->center_id,
            'warning_threshold' => 'required|integer|min:1',
            'critical_threshold' => 'required|integer|min:1|lt:warning_threshold',
        ]);

        $stockThreshold->update([
            'blood_type_id' => $request->blood_type_id,
            'warning_threshold' => $request->warning_threshold,
            'critical_threshold' => $request->critical_threshold,
        ]);

        return redirect()->route('stock-thresholds.index')->with('success', 'Seuil modifié avec succès.');
    }

    public function destroy(StockThreshold $stockThreshold)
    {
        $this->authorizeThreshold($stockThreshold);
        $stockThreshold->delete();
        return redirect()->route('stock-thresholds.index')->with('success', 'Seuil supprimé.');
    }

    private function authorizeThreshold(StockThreshold $threshold)
    {
        $user = Auth::user();
        if ($threshold->center_id !== $user->center_id) {
            abort(403, 'Accès refusé.');
        }
    }
}