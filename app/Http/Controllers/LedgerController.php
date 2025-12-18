<?php
namespace App\Http\Controllers;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $accounts = ChartOfAccount::with(['journalDetails' => function($q) use ($request) {
            $q->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'));
            if ($request->start_date) $q->whereDate('created_at', '>=', $request->start_date);
            if ($request->end_date) $q->whereDate('created_at', '<=', $request->end_date);
        }])->orderBy('code')->get();
        return view('backend.accounting.ledger.index', compact('accounts'));
    }
}
