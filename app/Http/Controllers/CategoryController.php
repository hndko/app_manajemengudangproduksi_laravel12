<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() { return view('master-data.categories.index', ['categories' => Category::latest()->paginate(20)]); }
    public function create() { return view('master-data.categories.create'); }
    public function store(Request $request) {
        $request->validate(['code' => 'required|unique:categories', 'name' => 'required', 'type' => 'required|in:material,produk']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }
    public function edit(Category $category) { return view('master-data.categories.edit', compact('category')); }
    public function update(Request $request, Category $category) {
        $request->validate(['code' => 'required|unique:categories,code,'.$category->id, 'name' => 'required']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
