<?php
namespace App\Http\Controllers;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $types = ['aset', 'liabilitas', 'ekuitas', 'pendapatan', 'beban'];

        $accounts = ChartOfAccount::with(['journalDetails' => function($q) use ($request) {
            $q->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'));
            if ($request->start_date) $q->whereDate('created_at', '>=', $request->start_date);
            if ($request->end_date) $q->whereDate('created_at', '<=', $request->end_date);
        }])
        ->when($request->type, fn($q) => $q->where('type', $request->type))
        ->orderBy('code')
        ->get();

        return view('backend.accounting.ledger.index', compact('accounts', 'types'));
    }

    public function show(Request $request, ChartOfAccount $account)
    {
        $details = $account->journalDetails()
            ->with('journalEntry')
            ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'))
            ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $summary = [
            'total_debit' => $account->journalDetails()
                ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'))
                ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
                ->sum('debit'),
            'total_credit' => $account->journalDetails()
                ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'))
                ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
                ->sum('credit'),
        ];
        $summary['balance'] = $account->normal_balance === 'debit'
            ? $summary['total_debit'] - $summary['total_credit']
            : $summary['total_credit'] - $summary['total_debit'];

        return view('backend.accounting.ledger.show', compact('account', 'details', 'summary'));
    }

    public function exportExcel(Request $request)
    {
        $accounts = ChartOfAccount::with(['journalDetails' => function($q) use ($request) {
            $q->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'));
            if ($request->start_date) $q->whereDate('created_at', '>=', $request->start_date);
            if ($request->end_date) $q->whereDate('created_at', '<=', $request->end_date);
        }])
        ->when($request->type, fn($q) => $q->where('type', $request->type))
        ->orderBy('code')
        ->get();

        $filename = 'buku-besar-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($accounts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Akun', 'Nama Akun', 'Tanggal', 'No Jurnal', 'Keterangan', 'Debit', 'Kredit']);

            foreach ($accounts as $account) {
                foreach ($account->journalDetails as $detail) {
                    fputcsv($file, [
                        $account->code,
                        $account->name,
                        $detail->journalEntry->date->format('d/m/Y'),
                        $detail->journalEntry->number,
                        $detail->description ?? $detail->journalEntry->description,
                        $detail->debit,
                        $detail->credit,
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $accounts = ChartOfAccount::with(['journalDetails' => function($q) use ($request) {
            $q->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'));
            if ($request->start_date) $q->whereDate('created_at', '>=', $request->start_date);
            if ($request->end_date) $q->whereDate('created_at', '<=', $request->end_date);
        }])
        ->when($request->type, fn($q) => $q->where('type', $request->type))
        ->orderBy('code')
        ->get();

        return view('backend.accounting.ledger.print', compact('accounts'));
    }
}
