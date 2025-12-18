<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Material;
use App\Models\Product;
use App\Models\Production;
use App\Models\ProductionMaterial;
use App\Models\ProductionProduct;
use App\Models\ProductionTeam;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with(['productionTeam', 'creator'])
            ->latest('date')
            ->paginate(20);

        return view('backend.manufacturing.productions.index', compact('productions'));
    }

    public function create()
    {
        $teams = ProductionTeam::active()->get();
        $materials = Material::active()->with('unit')->get();
        $products = Product::active()->with('unit')->get();
        $warehouses = Warehouse::active()->get();
        return view('backend.manufacturing.productions.create', compact('teams', 'materials', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'production_team_id' => 'required|exists:production_teams,id',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity_planned' => 'required|integer|min:1',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity_planned' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $production = Production::create([
                'date' => $request->date,
                'production_team_id' => $request->production_team_id,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->materials as $mat) {
                $material = Material::find($mat['material_id']);
                ProductionMaterial::create([
                    'production_id' => $production->id,
                    'material_id' => $mat['material_id'],
                    'warehouse_id' => $mat['warehouse_id'] ?? Warehouse::getDefault()?->id,
                    'quantity_planned' => $mat['quantity_planned'],
                    'unit_cost' => $material->purchase_price,
                ]);
            }

            foreach ($request->products as $prd) {
                $product = Product::find($prd['product_id']);
                ProductionProduct::create([
                    'production_id' => $production->id,
                    'product_id' => $prd['product_id'],
                    'warehouse_id' => $prd['warehouse_id'] ?? Warehouse::getDefault()?->id,
                    'quantity_planned' => $prd['quantity_planned'],
                    'unit_cost' => $product->base_price,
                ]);
            }

            ActivityLog::log('create', "Membuat produksi {$production->number}", $production);
        });

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil dibuat');
    }

    public function show(Production $production)
    {
        $production->load(['productionTeam', 'creator', 'materials.material.unit', 'products.product.unit']);
        return view('backend.manufacturing.productions.show', compact('production'));
    }

    public function edit(Production $production)
    {
        if ($production->status !== 'draft') {
            return back()->with('error', 'Hanya produksi draft yang dapat diedit');
        }

        $teams = ProductionTeam::active()->get();
        $materials = Material::active()->with('unit')->get();
        $products = Product::active()->with('unit')->get();
        $warehouses = Warehouse::active()->get();
        $production->load(['materials', 'products']);

        return view('backend.manufacturing.productions.edit', compact('production', 'teams', 'materials', 'products', 'warehouses'));
    }

    public function update(Request $request, Production $production)
    {
        if ($production->status !== 'draft') {
            return back()->with('error', 'Hanya produksi draft yang dapat diedit');
        }

        $request->validate([
            'date' => 'required|date',
            'production_team_id' => 'required|exists:production_teams,id',
        ]);

        $production->update($request->only(['date', 'production_team_id', 'notes']));
        ActivityLog::log('update', "Mengubah produksi {$production->number}", $production);

        return redirect()->route('productions.show', $production)->with('success', 'Produksi berhasil diperbarui');
    }

    public function destroy(Production $production)
    {
        if ($production->status !== 'draft') {
            return back()->with('error', 'Hanya produksi draft yang dapat dihapus');
        }

        ActivityLog::log('delete', "Menghapus produksi {$production->number}", $production);
        $production->delete();

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil dihapus');
    }

    public function start(Production $production)
    {
        if ($production->status !== 'draft') {
            return back()->with('error', 'Produksi tidak dapat dimulai');
        }

        // Reduce material stocks
        foreach ($production->materials as $mat) {
            $stock = Stock::getOrCreate($mat->warehouse_id, $mat->material);
            if ($stock->quantity < $mat->quantity_planned) {
                return back()->with('error', "Stok material {$mat->material->name} tidak cukup");
            }
        }

        DB::transaction(function () use ($production) {
            foreach ($production->materials as $mat) {
                $stock = Stock::getOrCreate($mat->warehouse_id, $mat->material);
                $stock->reduceStock($mat->quantity_planned, Production::class, $production->id, "Digunakan untuk produksi {$production->number}");
                $mat->update(['quantity_used' => $mat->quantity_planned]);
            }

            $production->start();
            ActivityLog::log('update', "Memulai produksi {$production->number}", $production);
        });

        return back()->with('success', 'Produksi berhasil dimulai');
    }

    public function complete(Request $request, Production $production)
    {
        if ($production->status !== 'in_progress') {
            return back()->with('error', 'Produksi tidak dapat diselesaikan');
        }

        $request->validate([
            'products' => 'required|array',
            'products.*.quantity_produced' => 'required|integer|min:0',
            'products.*.quantity_rejected' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $production) {
            foreach ($request->products as $id => $data) {
                $prodProduct = ProductionProduct::find($id);
                if ($prodProduct && $prodProduct->production_id === $production->id) {
                    $prodProduct->update([
                        'quantity_produced' => $data['quantity_produced'],
                        'quantity_rejected' => $data['quantity_rejected'],
                    ]);

                    // Add product stock
                    $quantityGood = $data['quantity_produced'] - $data['quantity_rejected'];
                    if ($quantityGood > 0) {
                        $stock = Stock::getOrCreate($prodProduct->warehouse_id, $prodProduct->product);
                        $stock->addStock($quantityGood, Production::class, $production->id, "Hasil produksi {$production->number}");
                    }
                }
            }

            $production->complete();
            ActivityLog::log('update', "Menyelesaikan produksi {$production->number}", $production);
        });

        return back()->with('success', 'Produksi berhasil diselesaikan');
    }
}
