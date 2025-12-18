<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('symbol', 'like', '%' . $request->search . '%');
            });
        }

        $data = [
            'units' => $query->orderBy('name')->paginate(15)->withQueryString(),
            'filters' => $request->only(['search']),
        ];

        return view('backend.master-data.units.index', $data);
    }

    public function create()
    {
        return view('backend.master-data.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'symbol' => 'required|max:10',
        ]);

        $unit = Unit::create($request->only(['name', 'symbol']));
        ActivityLog::log('create', "Menambah satuan {$unit->name}", $unit);

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil ditambahkan');
    }

    public function edit(Unit $unit)
    {
        $data = [
            'unit' => $unit,
        ];

        return view('backend.master-data.units.edit', $data);
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|max:100',
            'symbol' => 'required|max:10',
        ]);

        $unit->update($request->only(['name', 'symbol']));
        ActivityLog::log('update', "Mengubah satuan {$unit->name}", $unit);

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy(Unit $unit)
    {
        ActivityLog::log('delete', "Menghapus satuan {$unit->name}", $unit);
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil dihapus');
    }
}
