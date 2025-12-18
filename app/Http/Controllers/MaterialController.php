<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with(['category', 'unit']);

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
            'materials' => $query->latest()->paginate(15)->withQueryString(),
            'categories' => Category::where('type', 'material')->get(),
            'filters' => $request->only(['category_id', 'search', 'is_active']),
        ];

        return view('backend.warehouse.materials.index', $data);
    }

    public function create()
    {
        $data = [
            'categories' => Category::where('type', 'material')->get(),
            'units' => Unit::all(),
        ];

        return view('backend.warehouse.materials.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:materials,code|max:50',
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'minimum_stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $materialData = $request->only([
            'code', 'name', 'category_id', 'unit_id',
            'minimum_stock', 'price', 'description'
        ]);
        $materialData['is_active'] = $request->boolean('is_active', true);

        // Handle image upload
        if ($request->hasFile('image')) {
            $materialData['image'] = $request->file('image')->store('materials', 'public');
        }

        $material = Material::create($materialData);
        ActivityLog::log('create', "Menambah material {$material->name}", $material);

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil ditambahkan');
    }

    public function show(Material $material)
    {
        $data = [
            'material' => $material->load(['category', 'unit', 'stocks.warehouse']),
        ];

        return view('backend.warehouse.materials.show', $data);
    }

    public function edit(Material $material)
    {
        $data = [
            'material' => $material,
            'categories' => Category::where('type', 'material')->get(),
            'units' => Unit::all(),
        ];

        return view('backend.warehouse.materials.edit', $data);
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'code' => 'required|max:50|unique:materials,code,' . $material->id,
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'minimum_stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $materialData = $request->only([
            'code', 'name', 'category_id', 'unit_id',
            'minimum_stock', 'price', 'description'
        ]);
        $materialData['is_active'] = $request->boolean('is_active', true);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($material->image && Storage::disk('public')->exists($material->image)) {
                Storage::disk('public')->delete($material->image);
            }
            $materialData['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($materialData);
        ActivityLog::log('update', "Mengubah material {$material->name}", $material);

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil diperbarui');
    }

    public function destroy(Material $material)
    {
        // Delete image
        if ($material->image && Storage::disk('public')->exists($material->image)) {
            Storage::disk('public')->delete($material->image);
        }

        ActivityLog::log('delete', "Menghapus material {$material->name}", $material);
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material berhasil dihapus');
    }
}
