<?php
namespace App\Http\Controllers;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index() { return view('master-data.warehouses.index', ['warehouses' => Warehouse::latest()->paginate(20)]); }
    public function create() { return view('master-data.warehouses.create'); }
    public function store(Request $request) {
        $request->validate(['code' => 'required|unique:warehouses', 'name' => 'required']);
        Warehouse::create($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil ditambahkan');
    }
    public function edit(Warehouse $warehouse) { return view('master-data.warehouses.edit', compact('warehouse')); }
    public function update(Request $request, Warehouse $warehouse) {
        $request->validate(['code' => 'required|unique:warehouses,code,'.$warehouse->id, 'name' => 'required']);
        $warehouse->update($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil diperbarui');
    }
    public function destroy(Warehouse $warehouse) {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil dihapus');
    }
}
