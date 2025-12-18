<?php

namespace App\Http\Controllers;

use App\Models\ProductionTeam;
use App\Models\User;
use Illuminate\Http\Request;

class ProductionTeamController extends Controller
{
    public function index()
    {
        $teams = ProductionTeam::with('leader')->latest()->paginate(20);
        return view('manufacturing.production-teams.index', compact('teams'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('manufacturing.production-teams.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'leader_id' => 'required|exists:users,id',
        ]);

        ProductionTeam::create($request->all());
        return redirect()->route('production-teams.index')->with('success', 'Tim Produksi berhasil ditambahkan');
    }

    public function edit(ProductionTeam $productionTeam)
    {
        $users = User::where('is_active', true)->get();
        return view('manufacturing.production-teams.edit', compact('productionTeam', 'users'));
    }

    public function update(Request $request, ProductionTeam $productionTeam)
    {
        $request->validate([
            'name' => 'required|max:255',
            'leader_id' => 'required|exists:users,id',
        ]);

        $productionTeam->update($request->all());
        return redirect()->route('production-teams.index')->with('success', 'Tim Produksi berhasil diperbarui');
    }

    public function destroy(ProductionTeam $productionTeam)
    {
        $productionTeam->delete();
        return redirect()->route('production-teams.index')->with('success', 'Tim Produksi berhasil dihapus');
    }
}
