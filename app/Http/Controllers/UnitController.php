<?php
namespace App\Http\Controllers;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index() { return view('master-data.units.index', ['units' => Unit::latest()->paginate(20)]); }
    public function create() { return view('master-data.units.create'); }
    public function store(Request $request) {
        $request->validate(['code' => 'required|unique:units', 'name' => 'required']);
        Unit::create($request->all());
        return redirect()->route('units.index')->with('success', 'Satuan berhasil ditambahkan');
    }
    public function edit(Unit $unit) { return view('master-data.units.edit', compact('unit')); }
    public function update(Request $request, Unit $unit) {
        $request->validate(['code' => 'required|unique:units,code,'.$unit->id, 'name' => 'required']);
        $unit->update($request->all());
        return redirect()->route('units.index')->with('success', 'Satuan berhasil diperbarui');
    }
    public function destroy(Unit $unit) {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Satuan berhasil dihapus');
    }
}
