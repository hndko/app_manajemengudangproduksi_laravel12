@extends('layouts.app')

@section('title', 'Laba Rugi')
@section('header', 'Laporan Laba Rugi')
@section('subheader', 'Income Statement - ' . now()->translatedFormat('d F Y'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Revenues --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-success-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            PENDAPATAN
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalRevenue = 0; @endphp
                    @foreach($revenues as $account)
                        @php
                            $balance = $account->getBalance();
                            $totalRevenue += $balance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-success-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-success-400">Total Pendapatan</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-success-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Expenses --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-warning-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            BEBAN
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalExpense = 0; @endphp
                    @foreach($expenses as $account)
                        @php
                            $balance = $account->getBalance();
                            $totalExpense += $balance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-warning-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-warning-400">Total Beban</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-warning-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Net Income --}}
    @php $netIncome = $totalRevenue - $totalExpense; @endphp
    <div class="card {{ $netIncome >= 0 ? 'bg-gradient-to-r from-success-500/10 to-primary-500/10 border-success-500/20' : 'bg-gradient-to-r from-danger-500/10 to-warning-500/10 border-danger-500/20' }}">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl {{ $netIncome >= 0 ? 'bg-success-500/20' : 'bg-danger-500/20' }} flex items-center justify-center">
                    <svg class="w-7 h-7 {{ $netIncome >= 0 ? 'text-success-400' : 'text-danger-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($netIncome >= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-neutral-400">{{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                    <p class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                        Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-neutral-500">Pendapatan</p>
                        <p class="font-medium text-success-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-neutral-500">Beban</p>
                        <p class="font-medium text-warning-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak
        </button>
    </div>

</div>
@endsection
