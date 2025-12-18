<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Consumer;
use App\Models\Material;
use App\Models\Product;
use App\Models\Production;
use App\Models\SalesTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        // Stats
        $stats = [
            'productions' => Production::count(),
            'materials' => Material::count(),
            'products' => Product::count(),
            'consumers' => Consumer::count(),
        ];

        // Sales data for chart (monthly)
        $salesData = SalesTransaction::selectRaw('MONTH(date) as month, SUM(total_amount) as total')
            ->whereYear('date', now()->year)
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $salesChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesChartData[] = $salesData[$i] ?? 0;
        }

        // Production data for chart (monthly)
        $productionData = Production::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->whereYear('date', now()->year)
            ->where('status', 'completed')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $productionChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $productionChartData[] = $productionData[$i] ?? 0;
        }

        // Recent transactions
        $recentTransactions = SalesTransaction::with('consumer')
            ->latest()
            ->take(5)
            ->get();

        // Low stock items (materials and products)
        $lowStockMaterials = Material::with('stocks')
            ->get()
            ->filter(fn($m) => $m->is_low_stock)
            ->take(5);

        $lowStockProducts = Product::with('stocks')
            ->get()
            ->filter(fn($p) => $p->is_low_stock)
            ->take(5);

        $lowStockItems = $lowStockMaterials->merge($lowStockProducts)->take(5);

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('backend.dashboard.index', compact(
            'stats',
            'salesChartData',
            'productionChartData',
            'recentTransactions',
            'lowStockItems',
            'recentActivities'
        ))->with([
            'salesData' => $salesChartData,
            'productionData' => $productionChartData,
        ]);
    }
}
