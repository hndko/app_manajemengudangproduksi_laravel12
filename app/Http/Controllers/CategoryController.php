<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount(['materials', 'products']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data = [
            'categories' => $query->orderBy('type')->orderBy('name')->paginate(15)->withQueryString(),
            'filters' => $request->only(['type', 'search']),
        ];

        return view('backend.master-data.categories.index', $data);
    }

    public function create()
    {
        return view('backend.master-data.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:material,product',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->only(['name', 'type', 'description']));
        ActivityLog::log('create', "Menambah kategori {$category->name}", $category);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        $data = [
            'category' => $category,
        ];

        return view('backend.master-data.categories.edit', $data);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:material,product',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'type', 'description']));
        ActivityLog::log('update', "Mengubah kategori {$category->name}", $category);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        if ($category->materials()->count() > 0 || $category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori masih memiliki material/produk terkait');
        }

        ActivityLog::log('delete', "Menghapus kategori {$category->name}", $category);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
