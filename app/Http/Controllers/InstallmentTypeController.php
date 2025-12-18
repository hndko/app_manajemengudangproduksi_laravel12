<?php

namespace App\Http\Controllers;

use App\Models\InstallmentType;
use Illuminate\Http\Request;

class InstallmentTypeController extends Controller
{
    public function index()
    {
        $installmentTypes = InstallmentType::latest()->paginate(20);
        return view('master-data.installment-types.index', compact('installmentTypes'));
    }

    public function create()
    {
        return view('master-data.installment-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tenor' => 'required|integer|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
        ]);

        InstallmentType::create($request->all());
        return redirect()->route('installment-types.index')->with('success', 'Tipe Cicilan berhasil ditambahkan');
    }

    public function edit(InstallmentType $installmentType)
    {
        return view('master-data.installment-types.edit', compact('installmentType'));
    }

    public function update(Request $request, InstallmentType $installmentType)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tenor' => 'required|integer|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
        ]);

        $installmentType->update($request->all());
        return redirect()->route('installment-types.index')->with('success', 'Tipe Cicilan berhasil diperbarui');
    }

    public function destroy(InstallmentType $installmentType)
    {
        $installmentType->delete();
        return redirect()->route('installment-types.index')->with('success', 'Tipe Cicilan berhasil dihapus');
    }
}
