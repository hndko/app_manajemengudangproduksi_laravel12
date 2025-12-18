<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Material;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with(['category', 'unit', 'stocks'])
            ->latest()
            ->paginate(20);

        return view('warehouse.materials.index', compact('materials'));
    }

    public function create()
    {
        $categories = Category::materials()->active()->get();
        $units = Unit::active()->get();
        return view('warehouse.materials.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        $material = Material::create($data);
        ActivityLog::log('create', "Menambah material {$material->name}", $material);

        return redirect()->route('materials.index')->with('success', 'Material berhasil ditambahkan');
    }

    public function show(Material $material)
    {
        $material->load(['category', 'unit', 'stocks.warehouse']);
        return view('warehouse.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $categories = Category::materials()->active()->get();
        $units = Unit::active()->get();
        return view('warehouse.materials.edit', compact('material', 'categories', 'units'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($data);
        ActivityLog::log('update', "Mengubah material {$material->name}", $material);

        return redirect()->route('materials.index')->with('success', 'Material berhasil diperbarui');
    }

    public function destroy(Material $material)
    {
        if ($material->image) {
            Storage::disk('public')->delete($material->image);
        }

        ActivityLog::log('delete', "Menghapus material {$material->name}", $material);
        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Material berhasil dihapus');
    }
}
