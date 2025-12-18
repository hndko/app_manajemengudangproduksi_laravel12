<?php

namespace App\Http\Controllers;

use App\Models\PriceType;
use Illuminate\Http\Request;

class PriceTypeController extends Controller
{
    public function index()
    {
        $priceTypes = PriceType::latest()->paginate(20);
        return view('master-data.price-types.index', compact('priceTypes'));
    }

    public function create()
    {
        return view('master-data.price-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        PriceType::create($request->all());
        return redirect()->route('price-types.index')->with('success', 'Tipe Harga berhasil ditambahkan');
    }

    public function edit(PriceType $priceType)
    {
        return view('master-data.price-types.edit', compact('priceType'));
    }

    public function update(Request $request, PriceType $priceType)
    {
        $request->validate([
            'name' => 'required|max:255',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $priceType->update($request->all());
        return redirect()->route('price-types.index')->with('success', 'Tipe Harga berhasil diperbarui');
    }

    public function destroy(PriceType $priceType)
    {
        $priceType->delete();
        return redirect()->route('price-types.index')->with('success', 'Tipe Harga berhasil dihapus');
    }
}
