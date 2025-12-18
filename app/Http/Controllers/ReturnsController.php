<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Consumer;
use App\Models\DeliveryNote;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\Returns;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnsController extends Controller
{
    public function index()
    {
        $returns = Returns::with(['consumer', 'warehouse', 'creator'])
            ->latest('date')
            ->paginate(20);

        return view('backend.expedition.returns.index', compact('returns'));
    }

    public function create()
    {
        $consumers = Consumer::active()->get();
        $warehouses = Warehouse::active()->get();
        $deliveryNotes = DeliveryNote::where('status', 'delivered')->get();
        $products = Product::active()->with('unit')->get();
        return view('backend.expedition.returns.create', compact('consumers', 'warehouses', 'deliveryNotes', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'consumer_id' => 'required|exists:consumers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:customer_return,expedition_return',
            'reason' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:baik,rusak,cacat',
        ]);

        DB::transaction(function () use ($request) {
            $return = Returns::create([
                'date' => $request->date,
                'delivery_note_id' => $request->delivery_note_id,
                'consumer_id' => $request->consumer_id,
                'warehouse_id' => $request->warehouse_id,
                'type' => $request->type,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                ReturnItem::create([
                    'return_id' => $return->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'condition' => $item['condition'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            ActivityLog::log('create', "Membuat retur {$return->number}", $return);
        });

        return redirect()->route('returns.index')->with('success', 'Retur berhasil dibuat');
    }

    public function show(Returns $return)
    {
        $return->load(['consumer', 'warehouse', 'creator', 'approver', 'deliveryNote', 'items.product.unit']);
        return view('backend.expedition.returns.show', compact('return'));
    }

    public function edit(Returns $return)
    {
        if ($return->status !== 'draft') {
            return back()->with('error', 'Hanya retur draft yang dapat diedit');
        }

        $consumers = Consumer::active()->get();
        $warehouses = Warehouse::active()->get();
        $products = Product::active()->with('unit')->get();
        $return->load('items');

        return view('backend.expedition.returns.edit', compact('return', 'consumers', 'warehouses', 'products'));
    }

    public function update(Request $request, Returns $return)
    {
        if ($return->status !== 'draft') {
            return back()->with('error', 'Hanya retur draft yang dapat diedit');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $return->update($request->only(['reason', 'notes']));
        ActivityLog::log('update', "Mengubah retur {$return->number}", $return);

        return redirect()->route('returns.show', $return)->with('success', 'Retur berhasil diperbarui');
    }

    public function destroy(Returns $return)
    {
        if ($return->status !== 'draft') {
            return back()->with('error', 'Hanya retur draft yang dapat dihapus');
        }

        ActivityLog::log('delete', "Menghapus retur {$return->number}", $return);
        $return->delete();

        return redirect()->route('returns.index')->with('success', 'Retur berhasil dihapus');
    }

    public function approve(Returns $return)
    {
        if ($return->status !== 'draft') {
            return back()->with('error', 'Retur tidak dapat disetujui');
        }

        $return->approve();
        ActivityLog::log('update', "Menyetujui retur {$return->number}", $return);

        return back()->with('success', 'Retur berhasil disetujui');
    }

    public function process(Returns $return)
    {
        if ($return->status !== 'approved') {
            return back()->with('error', 'Retur tidak dapat diproses');
        }

        DB::transaction(function () use ($return) {
            foreach ($return->items as $item) {
                if ($item->condition === 'baik') {
                    $stock = Stock::getOrCreate($return->warehouse_id, $item->product);
                    $stock->addStock($item->quantity, Returns::class, $return->id, "Retur {$return->number}");
                }
            }

            $return->process();
            ActivityLog::log('update', "Memproses retur {$return->number}", $return);
        });

        return back()->with('success', 'Retur berhasil diproses');
    }
}
