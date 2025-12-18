<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Consumer;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNoteController extends Controller
{
    public function index()
    {
        $deliveryNotes = DeliveryNote::with(['consumer', 'warehouse', 'creator'])
            ->latest('date')
            ->paginate(20);

        return view('expedition.delivery-notes.index', compact('deliveryNotes'));
    }

    public function create()
    {
        $consumers = Consumer::active()->get();
        $warehouses = Warehouse::active()->get();
        $products = Product::active()->with('unit')->get();
        return view('expedition.delivery-notes.create', compact('consumers', 'warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'consumer_id' => 'required|exists:consumers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'shipping_address' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $deliveryNote = DeliveryNote::create([
                'date' => $request->date,
                'consumer_id' => $request->consumer_id,
                'warehouse_id' => $request->warehouse_id,
                'shipping_address' => $request->shipping_address,
                'driver_name' => $request->driver_name,
                'vehicle_number' => $request->vehicle_number,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                DeliveryNoteItem::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            ActivityLog::log('create', "Membuat surat jalan {$deliveryNote->number}", $deliveryNote);
        });

        return redirect()->route('delivery-notes.index')->with('success', 'Surat Jalan berhasil dibuat');
    }

    public function show(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['consumer', 'warehouse', 'creator', 'items.product.unit']);
        return view('expedition.delivery-notes.show', compact('deliveryNote'));
    }

    public function edit(DeliveryNote $deliveryNote)
    {
        if ($deliveryNote->status !== 'draft') {
            return back()->with('error', 'Hanya surat jalan draft yang dapat diedit');
        }

        $consumers = Consumer::active()->get();
        $warehouses = Warehouse::active()->get();
        $products = Product::active()->with('unit')->get();
        $deliveryNote->load('items');

        return view('expedition.delivery-notes.edit', compact('deliveryNote', 'consumers', 'warehouses', 'products'));
    }

    public function update(Request $request, DeliveryNote $deliveryNote)
    {
        if ($deliveryNote->status !== 'draft') {
            return back()->with('error', 'Hanya surat jalan draft yang dapat diedit');
        }

        $request->validate([
            'date' => 'required|date',
            'consumer_id' => 'required|exists:consumers,id',
            'shipping_address' => 'required|string|max:500',
        ]);

        $deliveryNote->update($request->only(['date', 'consumer_id', 'shipping_address', 'driver_name', 'vehicle_number', 'notes']));
        ActivityLog::log('update', "Mengubah surat jalan {$deliveryNote->number}", $deliveryNote);

        return redirect()->route('delivery-notes.show', $deliveryNote)->with('success', 'Surat Jalan berhasil diperbarui');
    }

    public function destroy(DeliveryNote $deliveryNote)
    {
        if ($deliveryNote->status !== 'draft') {
            return back()->with('error', 'Hanya surat jalan draft yang dapat dihapus');
        }

        ActivityLog::log('delete', "Menghapus surat jalan {$deliveryNote->number}", $deliveryNote);
        $deliveryNote->delete();

        return redirect()->route('delivery-notes.index')->with('success', 'Surat Jalan berhasil dihapus');
    }

    public function ship(DeliveryNote $deliveryNote)
    {
        if ($deliveryNote->status !== 'draft') {
            return back()->with('error', 'Surat jalan tidak dapat dikirim');
        }

        // Check and reduce stock
        foreach ($deliveryNote->items as $item) {
            $stock = Stock::getOrCreate($deliveryNote->warehouse_id, $item->product);
            if ($stock->quantity < $item->quantity) {
                return back()->with('error', "Stok produk {$item->product->name} tidak cukup");
            }
        }

        DB::transaction(function () use ($deliveryNote) {
            foreach ($deliveryNote->items as $item) {
                $stock = Stock::getOrCreate($deliveryNote->warehouse_id, $item->product);
                $stock->reduceStock($item->quantity, DeliveryNote::class, $deliveryNote->id, "Pengiriman {$deliveryNote->number}");
            }

            $deliveryNote->ship();
            ActivityLog::log('update', "Mengirim surat jalan {$deliveryNote->number}", $deliveryNote);
        });

        return back()->with('success', 'Surat Jalan berhasil dikirim');
    }

    public function deliver(DeliveryNote $deliveryNote)
    {
        if ($deliveryNote->status !== 'shipped') {
            return back()->with('error', 'Surat jalan tidak dapat ditandai terkirim');
        }

        $deliveryNote->markDelivered();
        ActivityLog::log('update', "Menandai surat jalan {$deliveryNote->number} terkirim", $deliveryNote);

        return back()->with('success', 'Surat Jalan berhasil ditandai terkirim');
    }
}
