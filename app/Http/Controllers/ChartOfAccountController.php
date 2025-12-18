<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        $accounts = ChartOfAccount::with('parent', 'children')
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();

        return view('backend.accounting.chart-of-accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parentAccounts = ChartOfAccount::orderBy('code')->get();
        return view('backend.accounting.chart-of-accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:chart_of_accounts,code',
            'name' => 'required|max:255',
            'type' => 'required|in:aset,liabilitas,ekuitas,pendapatan,beban',
            'normal_balance' => 'required|in:debit,kredit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
        ]);

        $account = ChartOfAccount::create($request->all());

        ActivityLog::log('create', "Menambah akun {$account->code} - {$account->name}", $account);

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil ditambahkan');
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->load('journalDetails.journalEntry');
        return view('backend.accounting.chart-of-accounts.show', compact('chartOfAccount'));
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $parentAccounts = ChartOfAccount::where('id', '!=', $chartOfAccount->id)
            ->orderBy('code')
            ->get();
        return view('backend.accounting.chart-of-accounts.edit', compact('chartOfAccount', 'parentAccounts'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $request->validate([
            'code' => 'required|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name' => 'required|max:255',
            'type' => 'required|in:aset,liabilitas,ekuitas,pendapatan,beban',
            'normal_balance' => 'required|in:debit,kredit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
        ]);

        $old = $chartOfAccount->toArray();
        $chartOfAccount->update($request->all());

        ActivityLog::log('update', "Mengubah akun {$chartOfAccount->code}", $chartOfAccount, $old, $chartOfAccount->toArray());

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if ($chartOfAccount->is_locked) {
            return back()->with('error', 'Akun ini tidak dapat dihapus');
        }

        if ($chartOfAccount->journalDetails()->exists()) {
            return back()->with('error', 'Akun ini sudah digunakan dalam jurnal');
        }

        ActivityLog::log('delete', "Menghapus akun {$chartOfAccount->code} - {$chartOfAccount->name}", $chartOfAccount);
        $chartOfAccount->delete();

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil dihapus');
    }
}
