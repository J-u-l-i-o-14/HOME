<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('regions.index', compact('regions'));
    }
    public function create()
    {
        return view('regions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:regions,name',
        ]);
        Region::create(['name' => $request->name]);
        return redirect()->route('regions.create')->with('success', 'Région ajoutée avec succès.');
    }
}
