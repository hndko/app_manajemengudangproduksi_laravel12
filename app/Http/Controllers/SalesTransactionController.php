<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Consumer;
use App\Models\DeliveryNote;
use App\Models\InstallmentType;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\SalesPayment;
use App\Models\SalesTransaction;
use App\Models\SalesTransactionItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesTransactionController extends Controller
{
    public function index()
    {
        $sales = SalesTransaction::with(['consumer', 'creator'])
            ->latest('date')
            ->paginate(20);

        return view('backend.transactions.sales.index', compact('sales'));
    }

    public function create()
    {
        $consumers = Consumer::active()->get();
        $warehouses = Warehouse::active()->get();
        $priceTypes = PriceType::active()->get();
        $installmentTypes = InstallmentType::active()->get();
        $products = Product::active()->with(['unit', 'prices'])->get();
        $deliveryNotes = DeliveryNote::where('status', 'delivered')
            ->whereDoesntHave('salesTransaction')
            ->get();

        return view('backend.transactions.sales.create', compact('consumers', 'warehouses', 'priceTypes', 'installmentTypes', 'products', 'deliveryNotes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'consumer_id' => 'required|exists:consumers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discountAmount = $request->discount_amount ?? 0;
            $taxAmount = $request->tax_amount ?? 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            $sale = SalesTransaction::create([
                'date' => $request->date,
                'consumer_id' => $request->consumer_id,
                'warehouse_id' => $request->warehouse_id,
                'price_type_id' => $request->price_type_id,
                'installment_type_id' => $request->installment_type_id,
                'delivery_note_id' => $request->delivery_note_id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                SalesTransactionItem::create([
                    'sales_transaction_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            ActivityLog::log('create', "Membuat transaksi penjualan {$sale->number}", $sale);
        });

        return redirect()->route('sales.index')->with('success', 'Transaksi Penjualan berhasil dibuat');
    }

    public function show(SalesTransaction $sale)
    {
        $sale->load(['consumer', 'warehouse', 'priceType', 'installmentType', 'creator', 'items.product.unit', 'payments.creator', 'deliveryNote']);
        return view('backend.transactions.sales.show', compact('sale'));
    }

    public function edit(SalesTransaction $sale)
    {
        if ($sale->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit');
        }

        $consumers = Consumer::active()->get();
        $warehouses = Warehouse::active()->get();
        $priceTypes = PriceType::active()->get();
        $installmentTypes = InstallmentType::active()->get();
        $products = Product::active()->with(['unit', 'prices'])->get();
        $sale->load('items');

        return view('backend.transactions.sales.edit', compact('sale', 'consumers', 'warehouses', 'priceTypes', 'installmentTypes', 'products'));
    }

    public function update(Request $request, SalesTransaction $sale)
    {
        if ($sale->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat diedit');
        }

        $request->validate([
            'date' => 'required|date',
            'consumer_id' => 'required|exists:consumers,id',
        ]);

        $sale->update($request->only(['date', 'consumer_id', 'discount_amount', 'tax_amount', 'notes']));
        $sale->calculateTotals();

        ActivityLog::log('update', "Mengubah transaksi penjualan {$sale->number}", $sale);

        return redirect()->route('sales.show', $sale)->with('success', 'Transaksi Penjualan berhasil diperbarui');
    }

    public function destroy(SalesTransaction $sale)
    {
        if ($sale->status !== 'draft') {
            return back()->with('error', 'Hanya transaksi draft yang dapat dihapus');
        }

        ActivityLog::log('delete', "Menghapus transaksi penjualan {$sale->number}", $sale);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Transaksi Penjualan berhasil dihapus');
    }

    public function confirm(SalesTransaction $sale)
    {
        if ($sale->status !== 'draft') {
            return back()->with('error', 'Transaksi tidak dapat dikonfirmasi');
        }

        $sale->confirm();
        ActivityLog::log('update', "Mengkonfirmasi transaksi penjualan {$sale->number}", $sale);

        return back()->with('success', 'Transaksi Penjualan berhasil dikonfirmasi');
    }

    public function addPayment(Request $request, SalesTransaction $sale)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $sale->remaining_amount,
            'method' => 'required|in:cash,transfer,giro,other',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment = $sale->addPayment(
            $request->amount,
            $request->method,
            $request->reference,
            $request->notes
        );

        ActivityLog::log('create', "Menambah pembayaran {$payment->number} untuk {$sale->number}", $payment);

        return back()->with('success', 'Pembayaran berhasil ditambahkan');
    }
}
