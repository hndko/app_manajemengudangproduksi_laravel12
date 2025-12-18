@extends('layouts.app')

@section('title', 'Arus Kas')
@section('header', 'Laporan Arus Kas')
@section('subheader', 'Cash Flow Statement - ' . $periodLabel)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Period Filter --}}
    <div class="card">
        <form action="{{ route('reports.cash-flow') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <select name="period" class="form-select w-36" onchange="toggleMonth()">
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            <div id="monthSelect" class="{{ $period === 'yearly' ? 'hidden' : '' }}">
                <select name="month" class="form-select w-36">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <select name="year" class="form-select w-28">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('reports.cash-flow.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-secondary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    CSV
                </a>
                <a href="{{ route('reports.cash-flow.export', request()->query()) }}" target="_blank" class="btn btn-secondary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </a>
            </div>
        </form>
    </div>

    {{-- Operating Activities --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-primary-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            AKTIVITAS OPERASI
        </h3>

        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 rounded-lg bg-dark-card">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-success-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-white">Penerimaan dari Operasi</span>
                </div>
                <span class="text-sm font-semibold text-success-400">Rp {{ number_format($cashFlowData['operating']['inflow'], 0, ',', '.') }}</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg bg-dark-card">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-danger-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-white">Pengeluaran untuk Operasi</span>
                </div>
                <span class="text-sm font-semibold text-danger-400">(Rp {{ number_format($cashFlowData['operating']['outflow'], 0, ',', '.') }})</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-lg {{ $cashFlowData['operating']['net'] >= 0 ? 'bg-success-500/10' : 'bg-danger-500/10' }}">
                <span class="text-sm font-medium text-white">Arus Kas Bersih dari Aktivitas Operasi</span>
                <span class="text-sm font-bold {{ $cashFlowData['operating']['net'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                    Rp {{ number_format($cashFlowData['operating']['net'], 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Investing Activities --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-secondary-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            AKTIVITAS INVESTASI
        </h3>

        <div class="flex items-center justify-between p-3 rounded-lg {{ $cashFlowData['investing']['net'] >= 0 ? 'bg-success-500/10' : 'bg-danger-500/10' }}">
            <span class="text-sm font-medium text-white">Arus Kas Bersih dari Aktivitas Investasi</span>
            <span class="text-sm font-bold {{ $cashFlowData['investing']['net'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                {{ $cashFlowData['investing']['net'] < 0 ? '(' : '' }}Rp {{ number_format(abs($cashFlowData['investing']['net']), 0, ',', '.') }}{{ $cashFlowData['investing']['net'] < 0 ? ')' : '' }}
            </span>
        </div>
    </div>

    {{-- Financing Activities --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-warning-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            AKTIVITAS PENDANAAN
        </h3>

        <div class="flex items-center justify-between p-3 rounded-lg {{ $cashFlowData['financing']['net'] >= 0 ? 'bg-success-500/10' : 'bg-danger-500/10' }}">
            <span class="text-sm font-medium text-white">Arus Kas Bersih dari Aktivitas Pendanaan</span>
            <span class="text-sm font-bold {{ $cashFlowData['financing']['net'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                {{ $cashFlowData['financing']['net'] < 0 ? '(' : '' }}Rp {{ number_format(abs($cashFlowData['financing']['net']), 0, ',', '.') }}{{ $cashFlowData['financing']['net'] < 0 ? ')' : '' }}
            </span>
        </div>
    </div>

    {{-- Total Cash Flow --}}
    <div class="card {{ $cashFlowData['total'] >= 0 ? 'bg-gradient-to-r from-success-500/10 to-primary-500/10 border-success-500/20' : 'bg-gradient-to-r from-danger-500/10 to-warning-500/10 border-danger-500/20' }}">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl {{ $cashFlowData['total'] >= 0 ? 'bg-success-500/20' : 'bg-danger-500/20' }} flex items-center justify-center">
                    <svg class="w-7 h-7 {{ $cashFlowData['total'] >= 0 ? 'text-success-400' : 'text-danger-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-neutral-400">Total Perubahan Kas</p>
                    <p class="text-2xl font-bold {{ $cashFlowData['total'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                        {{ $cashFlowData['total'] < 0 ? '-' : '' }}Rp {{ number_format(abs($cashFlowData['total']), 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 text-center text-sm">
                <div>
                    <p class="text-neutral-500">Operasi</p>
                    <p class="font-medium {{ $cashFlowData['operating']['net'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                        Rp {{ number_format($cashFlowData['operating']['net'], 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-neutral-500">Investasi</p>
                    <p class="font-medium {{ $cashFlowData['investing']['net'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                        Rp {{ number_format($cashFlowData['investing']['net'], 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-neutral-500">Pendanaan</p>
                    <p class="font-medium {{ $cashFlowData['financing']['net'] >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                        Rp {{ number_format($cashFlowData['financing']['net'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>

</div>

@push('scripts')
<script>
function toggleMonth() {
    const period = document.querySelector('select[name="period"]').value;
    const monthSelect = document.getElementById('monthSelect');
    monthSelect.classList.toggle('hidden', period === 'yearly');
}
</script>
@endpush
@endsection
