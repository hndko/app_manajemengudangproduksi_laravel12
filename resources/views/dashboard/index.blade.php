@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Produksi -->
        <div class="stat-card">
            <div class="stat-icon bg-primary-500">
                <i data-feather="tool" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="stat-label">Total Produksi</p>
                <p class="stat-value">{{ number_format($stats['productions'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Total Material -->
        <div class="stat-card">
            <div class="stat-icon bg-success-500">
                <i data-feather="box" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="stat-label">Total Material</p>
                <p class="stat-value">{{ number_format($stats['materials'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-icon bg-warning-500">
                <i data-feather="shopping-bag" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="stat-label">Total Produk</p>
                <p class="stat-value">{{ number_format($stats['products'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Total Konsumen -->
        <div class="stat-card">
            <div class="stat-icon bg-info-500">
                <i data-feather="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="stat-label">Total Konsumen</p>
                <p class="stat-value">{{ number_format($stats['consumers'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Penjualan Bulanan</h3>
                <select class="form-select w-auto text-sm" id="salesChartYear">
                    <option value="2024">2024</option>
                    <option value="2025" selected>2025</option>
                </select>
            </div>
            <div class="h-72">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Production Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Produksi Bulanan</h3>
                <select class="form-select w-auto text-sm" id="productionChartYear">
                    <option value="2024">2024</option>
                    <option value="2025" selected>2025</option>
                </select>
            </div>
            <div class="h-72">
                <canvas id="productionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Transaksi Terbaru</h3>
                <a href="{{ route('sales.index') }}" class="text-sm text-primary-500 hover:text-primary-600">
                    Lihat Semua
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Konsumen</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recentTransactions ?? []) as $transaction)
                        <tr>
                            <td class="font-medium">{{ $transaction->number }}</td>
                            <td>{{ $transaction->consumer->name }}</td>
                            <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $transaction->status_color }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-neutral-500 py-4">
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
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Peringatan Stok Rendah</h3>
                <a href="{{ route('stocks.index') }}" class="text-sm text-primary-500 hover:text-primary-600">
                    Lihat Semua
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Stok</th>
                            <th>Min. Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($lowStockItems ?? []) as $item)
                        <tr>
                            <td class="font-medium">{{ $item->name }}</td>
                            <td>{{ $item->total_stock }}</td>
                            <td>{{ $item->minimum_stock }}</td>
                            <td>
                                <span class="badge badge-danger">Rendah</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-neutral-500 py-4">
                                Tidak ada stok rendah
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
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Aktivitas Terbaru</h3>
            <a href="{{ route('activity-logs.index') }}" class="text-sm text-primary-500 hover:text-primary-600">
                Lihat Semua
            </a>
        </div>
        <div class="space-y-4">
            @forelse(($recentActivities ?? []) as $activity)
            <div class="flex items-start gap-4 p-4 rounded-lg bg-neutral-50 dark:bg-dark-card">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-{{ $activity->action_color }}-100 flex items-center justify-center">
                        <i data-feather="{{ $activity->action_icon }}" class="w-5 h-5 text-{{ $activity->action_color }}-500"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-neutral-800 dark:text-neutral-200">
                        <span class="font-medium">{{ $activity->user->name }}</span>
                        {{ $activity->description }}
                    </p>
                    <p class="text-xs text-neutral-500 mt-1">
                        {{ $activity->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center text-neutral-500 py-8">
                Belum ada aktivitas
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($salesData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                backgroundColor: '#3b9dd4',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Production Chart
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Produksi',
                data: {!! json_encode($productionData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    feather.replace();
});
</script>
@endpush
@endsection
