@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('header', 'Laporan Keuangan')
@section('subheader', 'Pilih jenis laporan yang ingin dilihat')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <a href="{{ route('reports.balance-sheet') }}" class="card hover:shadow-lg transition-all group">
            <div class="text-center py-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-1">Neraca</h3>
                <p class="text-sm text-neutral-400">Balance Sheet</p>
                <p class="text-xs text-neutral-500 mt-2">Aset, Liabilitas, Ekuitas</p>
            </div>
        </a>

        <a href="{{ route('reports.income-statement') }}" class="card hover:shadow-lg transition-all group">
            <div class="text-center py-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-1">Laba Rugi</h3>
                <p class="text-sm text-neutral-400">Income Statement</p>
                <p class="text-xs text-neutral-500 mt-2">Pendapatan, Beban</p>
            </div>
        </a>

        <a href="{{ route('reports.cash-flow') }}" class="card hover:shadow-lg transition-all group">
            <div class="text-center py-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-1">Arus Kas</h3>
                <p class="text-sm text-neutral-400">Cash Flow Statement</p>
                <p class="text-xs text-neutral-500 mt-2">Operasi, Investasi, Pendanaan</p>
            </div>
        </a>

    </div>
</div>
@endsection
