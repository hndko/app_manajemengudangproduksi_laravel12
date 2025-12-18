<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit', 'stocks'])
            ->latest()
            ->paginate(20);

        return view('warehouse.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::products()->active()->get();
        $units = Unit::active()->get();
        $priceTypes = PriceType::active()->get();
        return view('warehouse.products.create', compact('categories', 'units', 'priceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'base_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'prices' => 'nullable|array',
            'prices.*.price_type_id' => 'exists:price_types,id',
            'prices.*.price' => 'numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->except(['image', 'prices']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);

            if ($request->prices) {
                foreach ($request->prices as $priceData) {
                    if (!empty($priceData['price'])) {
                        ProductPrice::create([
                            'product_id' => $product->id,
                            'price_type_id' => $priceData['price_type_id'],
                            'price' => $priceData['price'],
                        ]);
                    }
                }
            }

            ActivityLog::log('create', "Menambah produk {$product->name}", $product);
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'unit', 'stocks.warehouse', 'prices.priceType']);
        return view('warehouse.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::products()->active()->get();
        $units = Unit::active()->get();
        $priceTypes = PriceType::active()->get();
        $product->load('prices');
        return view('warehouse.products.edit', compact('product', 'categories', 'units', 'priceTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'base_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $product) {
            $data = $request->except(['image', 'prices']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            if ($request->prices) {
                $product->prices()->delete();
                foreach ($request->prices as $priceData) {
                    if (!empty($priceData['price'])) {
                        ProductPrice::create([
                            'product_id' => $product->id,
                            'price_type_id' => $priceData['price_type_id'],
                            'price' => $priceData['price'],
                        ]);
                    }
                }
            }

            ActivityLog::log('update', "Mengubah produk {$product->name}", $product);
        });

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        ActivityLog::log('delete', "Menghapus produk {$product->name}", $product);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}
