<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $journals = JournalEntry::with(['creator', 'fiscalPeriod'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date, fn($q) => $q->whereDate('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('date', '<=', $request->end_date))
            ->latest('date')
            ->paginate(20);

        return view('accounting.journals.index', compact('journals'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::active()->orderBy('code')->get();
        $fiscalPeriod = FiscalPeriod::current();

        return view('accounting.journals.create', compact('accounts', 'fiscalPeriod'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:chart_of_accounts,id',
            'details.*.debit' => 'numeric|min:0',
            'details.*.credit' => 'numeric|min:0',
        ]);

        $fiscalPeriod = FiscalPeriod::current();
        if (!$fiscalPeriod) {
            return back()->with('error', 'Tidak ada periode fiskal aktif');
        }

        DB::transaction(function () use ($request, $fiscalPeriod) {
            $journal = JournalEntry::create([
                'date' => $request->date,
                'description' => $request->description,
                'fiscal_period_id' => $fiscalPeriod->id,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->details as $detail) {
                if (($detail['debit'] ?? 0) > 0 || ($detail['credit'] ?? 0) > 0) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journal->id,
                        'account_id' => $detail['account_id'],
                        'description' => $detail['description'] ?? null,
                        'debit' => $detail['debit'] ?? 0,
                        'credit' => $detail['credit'] ?? 0,
                    ]);
                }
            }

            ActivityLog::log('create', "Membuat jurnal {$journal->number}", $journal);
        });

        return redirect()->route('journals.index')
            ->with('success', 'Jurnal berhasil dibuat');
    }

    public function show(JournalEntry $journal)
    {
        $journal->load(['details.account', 'creator', 'poster', 'fiscalPeriod']);
        return view('accounting.journals.show', compact('journal'));
    }

    public function edit(JournalEntry $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Hanya jurnal draft yang dapat diedit');
        }

        $journal->load('details');
        $accounts = ChartOfAccount::active()->orderBy('code')->get();

        return view('accounting.journals.edit', compact('journal', 'accounts'));
    }

    public function update(Request $request, JournalEntry $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Hanya jurnal draft yang dapat diedit');
        }

        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:chart_of_accounts,id',
            'details.*.debit' => 'numeric|min:0',
            'details.*.credit' => 'numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $journal) {
            $journal->update([
                'date' => $request->date,
                'description' => $request->description,
            ]);

            $journal->details()->delete();

            foreach ($request->details as $detail) {
                if (($detail['debit'] ?? 0) > 0 || ($detail['credit'] ?? 0) > 0) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journal->id,
                        'account_id' => $detail['account_id'],
                        'description' => $detail['description'] ?? null,
                        'debit' => $detail['debit'] ?? 0,
                        'credit' => $detail['credit'] ?? 0,
                    ]);
                }
            }

            ActivityLog::log('update', "Mengubah jurnal {$journal->number}", $journal);
        });

        return redirect()->route('journals.show', $journal)
            ->with('success', 'Jurnal berhasil diperbarui');
    }

    public function destroy(JournalEntry $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Hanya jurnal draft yang dapat dihapus');
        }

        ActivityLog::log('delete', "Menghapus jurnal {$journal->number}", $journal);
        $journal->delete();

        return redirect()->route('journals.index')
            ->with('success', 'Jurnal berhasil dihapus');
    }

    public function post(JournalEntry $journal)
    {
        if ($journal->status !== 'draft') {
            return back()->with('error', 'Jurnal sudah diposting atau dibatalkan');
        }

        if (!$journal->isBalanced()) {
            return back()->with('error', 'Jurnal tidak balance. Total debit harus sama dengan total kredit.');
        }

        $journal->post();
        ActivityLog::log('update', "Memposting jurnal {$journal->number}", $journal);

        return back()->with('success', 'Jurnal berhasil diposting');
    }
}
