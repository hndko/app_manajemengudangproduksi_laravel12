<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::withCount('stocks');

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $data = [
            'warehouses' => $query->orderBy('name')->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'is_active']),
        ];

        return view('master-data.warehouses.index', $data);
    }

    public function create()
    {
        return view('master-data.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:warehouses,code|max:20',
            'name' => 'required|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|max:20',
        ]);

        $warehouseData = $request->only(['code', 'name', 'address', 'phone']);
        $warehouseData['is_active'] = $request->boolean('is_active', true);

        $warehouse = Warehouse::create($warehouseData);
        ActivityLog::log('create', "Menambah gudang {$warehouse->name}", $warehouse);

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil ditambahkan');
    }

    public function edit(Warehouse $warehouse)
    {
        $data = [
            'warehouse' => $warehouse,
        ];

        return view('master-data.warehouses.edit', $data);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'code' => 'required|max:20|unique:warehouses,code,' . $warehouse->id,
            'name' => 'required|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|max:20',
        ]);

        $warehouseData = $request->only(['code', 'name', 'address', 'phone']);
        $warehouseData['is_active'] = $request->boolean('is_active', true);

        $warehouse->update($warehouseData);
        ActivityLog::log('update', "Mengubah gudang {$warehouse->name}", $warehouse);

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil diperbarui');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->stocks()->count() > 0) {
            return redirect()->route('warehouses.index')
                ->with('error', 'Gudang masih memiliki stok');
        }

        ActivityLog::log('delete', "Menghapus gudang {$warehouse->name}", $warehouse);
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil dihapus');
    }
}
