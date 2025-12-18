<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChartOfAccount;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['account', 'creator'])
            ->latest('date')
            ->paginate(20);

        return view('transactions.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::ofType('beban')->active()->orderBy('code')->get();
        return view('transactions.expenses.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:chart_of_accounts,id',
            'amount' => 'required|numeric|min:0',
            'title' => 'required|max:255',
            'payment_method' => 'required|in:cash,transfer,other',
            'receipt_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('receipt_image');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('receipt_image')) {
            $data['receipt_image'] = $request->file('receipt_image')->store('expenses', 'public');
        }

        $expense = Expense::create($data);
        ActivityLog::log('create', "Menambah pengeluaran {$expense->title}", $expense);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function show(Expense $expense)
    {
        $expense->load(['account', 'creator', 'approver']);
        return view('transactions.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Hanya pengeluaran draft yang dapat diedit');
        }

        $accounts = ChartOfAccount::ofType('beban')->active()->orderBy('code')->get();
        return view('transactions.expenses.edit', compact('expense', 'accounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Hanya pengeluaran draft yang dapat diedit');
        }

        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:chart_of_accounts,id',
            'amount' => 'required|numeric|min:0',
            'title' => 'required|max:255',
            'payment_method' => 'required|in:cash,transfer,other',
            'receipt_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('receipt_image');

        if ($request->hasFile('receipt_image')) {
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
            $data['receipt_image'] = $request->file('receipt_image')->store('expenses', 'public');
        }

        $expense->update($data);
        ActivityLog::log('update', "Mengubah pengeluaran {$expense->title}", $expense);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Hanya pengeluaran draft yang dapat dihapus');
        }

        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        ActivityLog::log('delete', "Menghapus pengeluaran {$expense->title}", $expense);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus');
    }

    public function approve(Expense $expense)
    {
        if ($expense->status !== 'draft') {
            return back()->with('error', 'Pengeluaran tidak dapat disetujui');
        }

        $expense->approve();
        ActivityLog::log('update', "Menyetujui pengeluaran {$expense->title}", $expense);

        return back()->with('success', 'Pengeluaran berhasil disetujui');
    }
}
