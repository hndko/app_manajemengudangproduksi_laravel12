@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Selamat datang kembali, ' . auth()->user()->name)

@section('content')
<div class="space-y-4">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Total Produksi -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-primary-500 to-primary-600 shadow-primary-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Produksi</p>
                <p class="stat-value">{{ number_format($stats['productions'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Total Material -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-accent-500 to-accent-600 shadow-accent-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Material</p>
                <p class="stat-value">{{ number_format($stats['materials'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-secondary-500 to-secondary-600 shadow-secondary-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Produk</p>
                <p class="stat-value">{{ number_format($stats['products'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Total Konsumen -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-purple-500 to-purple-600 shadow-purple-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Konsumen</p>
                <p class="stat-value">{{ number_format($stats['consumers'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Sales Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-neutral-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    Penjualan Bulanan
                </h3>
                <select class="form-select w-auto text-xs py-1 px-2" id="salesChartYear">
                    <option value="2024">2024</option>
                    <option value="2025" selected>2025</option>
                </select>
            </div>
            <div class="h-48 lg:h-56">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Production Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-neutral-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                    Produksi Bulanan
                </h3>
                <select class="form-select w-auto text-xs py-1 px-2" id="productionChartYear">
                    <option value="2024">2024</option>
                    <option value="2025" selected>2025</option>
                </select>
            </div>
            <div class="h-48 lg:h-56">
                <canvas id="productionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-neutral-800 dark:text-white">Transaksi Terbaru</h3>
                <a href="{{ route('sales.index') }}" class="text-xs text-primary-500 hover:text-primary-600 font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Konsumen</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recentTransactions ?? []) as $transaction)
                        <tr>
                            <td class="font-medium text-primary-600">{{ $transaction->number }}</td>
                            <td class="truncate max-w-[100px]">{{ $transaction->consumer->name }}</td>
                            <td class="text-right font-medium">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->status_color }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-neutral-400 py-6">
                                <svg class="w-8 h-8 mx-auto mb-2 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-neutral-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-danger-500 animate-pulse"></span>
                    Stok Rendah
                </h3>
                <a href="{{ route('stocks.index') }}" class="text-xs text-primary-500 hover:text-primary-600 font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-right">Stok</th>
                            <th class="text-right">Min</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($lowStockItems ?? []) as $item)
                        <tr>
                            <td class="font-medium truncate max-w-[120px]">{{ $item->name }}</td>
                            <td class="text-right text-danger-600 font-medium">{{ $item->total_stock }}</td>
                            <td class="text-right text-neutral-500">{{ $item->minimum_stock }}</td>
                            <td>
                                <span class="badge badge-danger">Rendah</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-neutral-400 py-6">
                                <svg class="w-8 h-8 mx-auto mb-2 text-success-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Semua stok aman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-semibold text-neutral-800 dark:text-white">Aktivitas Terbaru</h3>
            <a href="{{ route('activity-logs.index') }}" class="text-xs text-primary-500 hover:text-primary-600 font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="space-y-2">
            @forelse(($recentActivities ?? []) as $activity)
            <div class="flex items-start gap-3 p-3 rounded-lg bg-neutral-50/50 dark:bg-dark-card/50 hover:bg-neutral-100/50 dark:hover:bg-dark-card transition-colors">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-{{ $activity->action_color ?? 'primary' }}-400 to-{{ $activity->action_color ?? 'primary' }}-500 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium">{{ $activity->user->name ?? 'System' }}</span>
                        <span class="text-neutral-500">{{ $activity->description }}</span>
                    </p>
                    <p class="text-[10px] text-neutral-400 mt-0.5">
                        {{ $activity->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center text-neutral-400 py-8">
                <svg class="w-10 h-10 mx-auto mb-2 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs">Belum ada aktivitas</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($salesData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: 'rgba(59, 157, 212, 0.8)',
                    borderColor: 'rgba(59, 157, 212, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Production Chart
    const productionCtx = document.getElementById('productionChart');
    if (productionCtx) {
        new Chart(productionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Produksi',
                    data: {!! json_encode($productionData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                    borderColor: '#14b8a6',
                    backgroundColor: 'rgba(20, 184, 166, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#14b8a6',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
