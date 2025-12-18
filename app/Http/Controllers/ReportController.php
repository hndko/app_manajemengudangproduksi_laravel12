<?php
namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('backend.accounting.reports.index');
    }

    private function getPeriodDates(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        if ($period === 'yearly') {
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate = Carbon::create($year, 12, 31)->endOfYear();
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period,
            'year' => $year,
            'month' => $month,
            'periodLabel' => $period === 'yearly'
                ? "Tahun $year"
                : Carbon::create($year, $month, 1)->translatedFormat('F Y'),
        ];
    }

    private function getAccountBalance($account, $startDate, $endDate)
    {
        $details = $account->journalDetails()
            ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted')
                ->whereBetween('date', [$startDate, $endDate]))
            ->get();

        $debit = $details->sum('debit');
        $credit = $details->sum('credit');

        return $account->normal_balance === 'debit' ? $debit - $credit : $credit - $debit;
    }

    public function balanceSheet(Request $request)
    {
        $periodData = $this->getPeriodDates($request);

        $assets = ChartOfAccount::ofType('aset')->active()->orderBy('code')->get();
        $liabilities = ChartOfAccount::ofType('liabilitas')->active()->orderBy('code')->get();
        $equity = ChartOfAccount::ofType('ekuitas')->active()->orderBy('code')->get();

        // Calculate balances
        foreach ($assets as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($liabilities as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($equity as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        // Comparison data
        $comparison = null;
        if ($request->compare) {
            $comparison = $this->getComparisonData($request, 'balance-sheet');
        }

        return view('backend.accounting.reports.balance-sheet', array_merge(
            compact('assets', 'liabilities', 'equity', 'comparison'),
            $periodData
        ));
    }

    public function incomeStatement(Request $request)
    {
        $periodData = $this->getPeriodDates($request);

        $revenues = ChartOfAccount::ofType('pendapatan')->active()->orderBy('code')->get();
        $expenses = ChartOfAccount::ofType('beban')->active()->orderBy('code')->get();

        // Calculate balances
        foreach ($revenues as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($expenses as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        // Comparison data
        $comparison = null;
        if ($request->compare) {
            $comparison = $this->getComparisonData($request, 'income-statement');
        }

        return view('backend.accounting.reports.income-statement', array_merge(
            compact('revenues', 'expenses', 'comparison'),
            $periodData
        ));
    }

    public function cashFlow(Request $request)
    {
        $periodData = $this->getPeriodDates($request);

        // Operating activities - revenues and expenses
        $revenues = ChartOfAccount::ofType('pendapatan')->active()->get();
        $expenses = ChartOfAccount::ofType('beban')->active()->get();

        $operatingIn = 0;
        $operatingOut = 0;

        foreach ($revenues as $account) {
            $operatingIn += $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($expenses as $account) {
            $operatingOut += $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        // Investing activities - asset changes
        $assets = ChartOfAccount::ofType('aset')->active()->where('code', 'like', '1-2%')->get();
        $investingFlow = 0;
        foreach ($assets as $account) {
            $investingFlow += $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        // Financing activities - liability and equity changes
        $liabilities = ChartOfAccount::ofType('liabilitas')->active()->get();
        $equity = ChartOfAccount::ofType('ekuitas')->active()->get();
        $financingFlow = 0;
        foreach ($liabilities as $account) {
            $financingFlow += $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($equity as $account) {
            $financingFlow += $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        $cashFlowData = [
            'operating' => [
                'inflow' => $operatingIn,
                'outflow' => $operatingOut,
                'net' => $operatingIn - $operatingOut,
            ],
            'investing' => [
                'net' => -$investingFlow, // Negative because asset increase = cash outflow
            ],
            'financing' => [
                'net' => $financingFlow,
            ],
            'total' => ($operatingIn - $operatingOut) - $investingFlow + $financingFlow,
        ];

        return view('backend.accounting.reports.cash-flow', array_merge(
            compact('cashFlowData'),
            $periodData
        ));
    }

    private function getComparisonData(Request $request, $type)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // Get previous period
        if ($period === 'yearly') {
            $prevYear = $year - 1;
            $prevStartDate = Carbon::create($prevYear, 1, 1)->startOfYear();
            $prevEndDate = Carbon::create($prevYear, 12, 31)->endOfYear();
            $prevLabel = "Tahun $prevYear";
        } else {
            $prevDate = Carbon::create($year, $month, 1)->subMonth();
            $prevStartDate = $prevDate->copy()->startOfMonth();
            $prevEndDate = $prevDate->copy()->endOfMonth();
            $prevLabel = $prevDate->translatedFormat('F Y');
        }

        $comparison = ['label' => $prevLabel, 'data' => []];

        if ($type === 'balance-sheet') {
            $accounts = ChartOfAccount::whereIn('type', ['aset', 'liabilitas', 'ekuitas'])->active()->get();
        } else {
            $accounts = ChartOfAccount::whereIn('type', ['pendapatan', 'beban'])->active()->get();
        }

        foreach ($accounts as $account) {
            $comparison['data'][$account->id] = $this->getAccountBalance($account, $prevStartDate, $prevEndDate);
        }

        return $comparison;
    }

    public function exportBalanceSheet(Request $request)
    {
        $periodData = $this->getPeriodDates($request);

        $assets = ChartOfAccount::ofType('aset')->active()->orderBy('code')->get();
        $liabilities = ChartOfAccount::ofType('liabilitas')->active()->orderBy('code')->get();
        $equity = ChartOfAccount::ofType('ekuitas')->active()->orderBy('code')->get();

        foreach ($assets as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($liabilities as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($equity as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        if ($request->format === 'csv') {
            return $this->exportCsv('neraca', $periodData['periodLabel'], function($file) use ($assets, $liabilities, $equity) {
                fputcsv($file, ['NERACA']);
                fputcsv($file, ['']);
                fputcsv($file, ['ASET']);
                foreach ($assets as $a) fputcsv($file, [$a->code, $a->name, $a->balance]);
                fputcsv($file, ['', 'Total Aset', $assets->sum('balance')]);
                fputcsv($file, ['']);
                fputcsv($file, ['LIABILITAS']);
                foreach ($liabilities as $l) fputcsv($file, [$l->code, $l->name, $l->balance]);
                fputcsv($file, ['', 'Total Liabilitas', $liabilities->sum('balance')]);
                fputcsv($file, ['']);
                fputcsv($file, ['EKUITAS']);
                foreach ($equity as $e) fputcsv($file, [$e->code, $e->name, $e->balance]);
                fputcsv($file, ['', 'Total Ekuitas', $equity->sum('balance')]);
            });
        }

        return view('backend.accounting.reports.print.balance-sheet', array_merge(
            compact('assets', 'liabilities', 'equity'),
            $periodData
        ));
    }

    public function exportIncomeStatement(Request $request)
    {
        $periodData = $this->getPeriodDates($request);

        $revenues = ChartOfAccount::ofType('pendapatan')->active()->orderBy('code')->get();
        $expenses = ChartOfAccount::ofType('beban')->active()->orderBy('code')->get();

        foreach ($revenues as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }
        foreach ($expenses as $account) {
            $account->balance = $this->getAccountBalance($account, $periodData['startDate'], $periodData['endDate']);
        }

        if ($request->format === 'csv') {
            return $this->exportCsv('laba-rugi', $periodData['periodLabel'], function($file) use ($revenues, $expenses) {
                fputcsv($file, ['LAPORAN LABA RUGI']);
                fputcsv($file, ['']);
                fputcsv($file, ['PENDAPATAN']);
                foreach ($revenues as $r) fputcsv($file, [$r->code, $r->name, $r->balance]);
                fputcsv($file, ['', 'Total Pendapatan', $revenues->sum('balance')]);
                fputcsv($file, ['']);
                fputcsv($file, ['BEBAN']);
                foreach ($expenses as $e) fputcsv($file, [$e->code, $e->name, $e->balance]);
                fputcsv($file, ['', 'Total Beban', $expenses->sum('balance')]);
                fputcsv($file, ['']);
                fputcsv($file, ['', 'LABA/RUGI BERSIH', $revenues->sum('balance') - $expenses->sum('balance')]);
            });
        }

        return view('backend.accounting.reports.print.income-statement', array_merge(
            compact('revenues', 'expenses'),
            $periodData
        ));
    }

    public function exportCashFlow(Request $request)
    {
        $periodData = $this->getPeriodDates($request);

        // Recalculate cash flow data
        $revenues = ChartOfAccount::ofType('pendapatan')->active()->get();
        $expenses = ChartOfAccount::ofType('beban')->active()->get();

        $operatingIn = $revenues->sum(fn($a) => $this->getAccountBalance($a, $periodData['startDate'], $periodData['endDate']));
        $operatingOut = $expenses->sum(fn($a) => $this->getAccountBalance($a, $periodData['startDate'], $periodData['endDate']));

        $cashFlowData = [
            'operating' => ['inflow' => $operatingIn, 'outflow' => $operatingOut, 'net' => $operatingIn - $operatingOut],
            'investing' => ['net' => 0],
            'financing' => ['net' => 0],
            'total' => $operatingIn - $operatingOut,
        ];

        if ($request->format === 'csv') {
            return $this->exportCsv('arus-kas', $periodData['periodLabel'], function($file) use ($cashFlowData) {
                fputcsv($file, ['LAPORAN ARUS KAS']);
                fputcsv($file, ['']);
                fputcsv($file, ['Aktivitas Operasi']);
                fputcsv($file, ['', 'Penerimaan', $cashFlowData['operating']['inflow']]);
                fputcsv($file, ['', 'Pengeluaran', $cashFlowData['operating']['outflow']]);
                fputcsv($file, ['', 'Arus Kas Bersih', $cashFlowData['operating']['net']]);
                fputcsv($file, ['']);
                fputcsv($file, ['Aktivitas Investasi', '', $cashFlowData['investing']['net']]);
                fputcsv($file, ['Aktivitas Pendanaan', '', $cashFlowData['financing']['net']]);
                fputcsv($file, ['']);
                fputcsv($file, ['TOTAL ARUS KAS', '', $cashFlowData['total']]);
            });
        }

        return view('backend.accounting.reports.print.cash-flow', array_merge(
            compact('cashFlowData'),
            $periodData
        ));
    }

    private function exportCsv($name, $period, $callback)
    {
        $filename = "$name-" . str_replace(' ', '-', strtolower($period)) . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function() use ($callback) {
            $file = fopen('php://output', 'w');
            $callback($file);
            fclose($file);
        }, 200, $headers);
    }
}
