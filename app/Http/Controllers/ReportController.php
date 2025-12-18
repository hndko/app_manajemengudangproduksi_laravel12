<?php
namespace App\Http\Controllers;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() { return view('accounting.reports.index'); }
    public function balanceSheet() {
        $assets = ChartOfAccount::ofType('aset')->active()->get();
        $liabilities = ChartOfAccount::ofType('liabilitas')->active()->get();
        $equity = ChartOfAccount::ofType('ekuitas')->active()->get();
        return view('accounting.reports.balance-sheet', compact('assets', 'liabilities', 'equity'));
    }
    public function incomeStatement() {
        $revenues = ChartOfAccount::ofType('pendapatan')->active()->get();
        $expenses = ChartOfAccount::ofType('beban')->active()->get();
        return view('accounting.reports.income-statement', compact('revenues', 'expenses'));
    }
}
