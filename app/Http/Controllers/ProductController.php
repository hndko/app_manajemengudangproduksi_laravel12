<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Category;
use App\Models\Unit;
use App\Models\PriceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

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
            'products' => $query->latest()->paginate(15)->withQueryString(),
            'categories' => Category::where('type', 'product')->get(),
            'filters' => $request->only(['category_id', 'search', 'is_active']),
        ];

        return view('backend.warehouse.products.index', $data);
    }

    public function create()
    {
        $data = [
            'categories' => Category::where('type', 'product')->get(),
            'units' => Unit::all(),
            'priceTypes' => PriceType::where('is_active', true)->get(),
        ];

        return view('backend.warehouse.products.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:products,code|max:50',
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'minimum_stock' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'prices' => 'nullable|array',
            'prices.*.price_type_id' => 'required|exists:price_types,id',
            'prices.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $productData = $request->only([
                'code', 'name', 'category_id', 'unit_id',
                'minimum_stock', 'base_price', 'description'
            ]);
            $productData['is_active'] = $request->boolean('is_active', true);

            // Handle image upload
            if ($request->hasFile('image')) {
                $productData['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($productData);

            // Save prices
            if ($request->has('prices')) {
                foreach ($request->prices as $priceData) {
                    ProductPrice::create([
                        'product_id' => $product->id,
                        'price_type_id' => $priceData['price_type_id'],
                        'price' => $priceData['price'],
                    ]);
                }
            }

            ActivityLog::log('create', "Menambah produk {$product->name}", $product);
        });

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $data = [
            'product' => $product->load(['category', 'unit', 'prices.priceType', 'stocks.warehouse']),
        ];

        return view('backend.warehouse.products.show', $data);
    }

    public function edit(Product $product)
    {
        $data = [
            'product' => $product->load('prices'),
            'categories' => Category::where('type', 'product')->get(),
            'units' => Unit::all(),
            'priceTypes' => PriceType::where('is_active', true)->get(),
        ];

        return view('backend.warehouse.products.edit', $data);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|max:50|unique:products,code,' . $product->id,
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'minimum_stock' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'prices' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $product) {
            $productData = $request->only([
                'code', 'name', 'category_id', 'unit_id',
                'minimum_stock', 'base_price', 'description'
            ]);
            $productData['is_active'] = $request->boolean('is_active', true);

            // Handle image upload
            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $productData['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($productData);

            // Update prices
            $product->prices()->delete();
            if ($request->has('prices')) {
                foreach ($request->prices as $priceData) {
                    if (!empty($priceData['price_type_id']) && !empty($priceData['price'])) {
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

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        ActivityLog::log('delete', "Menghapus produk {$product->name}", $product);
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
