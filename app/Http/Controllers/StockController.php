<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Material;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::active()->get();
        $selectedWarehouse = $request->warehouse_id ? Warehouse::find($request->warehouse_id) : Warehouse::getDefault();

        $stocks = Stock::with(['stockable', 'warehouse'])
            ->when($selectedWarehouse, fn($q) => $q->where('warehouse_id', $selectedWarehouse->id))
            ->paginate(20);

        return view('warehouse.stocks.index', compact('stocks', 'warehouses', 'selectedWarehouse'));
    }

    public function create()
    {
        $warehouses = Warehouse::active()->get();
        $materials = Material::active()->get();
        $products = Product::active()->get();
        return view('warehouse.stocks.create', compact('warehouses', 'materials', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:material,product',
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $stockable = $request->type === 'material'
            ? Material::findOrFail($request->item_id)
            : Product::findOrFail($request->item_id);

        $stock = Stock::getOrCreate($request->warehouse_id, $stockable);
        $stock->addStock($request->quantity, null, null, $request->notes);

        ActivityLog::log('create', "Menambah stok {$stockable->name} sebanyak {$request->quantity}", $stock);

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil ditambahkan');
    }

    public function show(Stock $stock)
    {
        $stock->load(['stockable', 'warehouse', 'movements.creator']);
        return view('warehouse.stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        return view('warehouse.stocks.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $stock->adjust($request->quantity, $request->notes);
        ActivityLog::log('update', "Menyesuaikan stok menjadi {$request->quantity}", $stock);

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil disesuaikan');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Stok berhasil dihapus');
    }

    public function adjust(Request $request, Stock $stock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'notes' => 'required|string|max:500',
        ]);

        $stock->adjust($request->quantity, $request->notes);
        ActivityLog::log('update', "Menyesuaikan stok menjadi {$request->quantity}", $stock);

        return back()->with('success', 'Stok berhasil disesuaikan');
    }
}
