@extends('layouts.app')

@section('title', 'Neraca')
@section('header', 'Laporan Neraca')
@section('subheader', 'Balance Sheet - ' . now()->translatedFormat('d F Y'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Assets --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-primary-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
            </div>
            ASET
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalAssets = 0; @endphp
                    @foreach($assets as $account)
                        @php
                            $balance = $account->getBalance();
                            $totalAssets += $balance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-primary-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-primary-400">Total Aset</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-primary-400">Rp {{ number_format($totalAssets, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Liabilities --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-danger-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                </svg>
            </div>
            LIABILITAS
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalLiabilities = 0; @endphp
                    @foreach($liabilities as $account)
                        @php
                            $balance = $account->getBalance();
                            $totalLiabilities += $balance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-danger-400">Total Liabilitas</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-danger-400">Rp {{ number_format($totalLiabilities, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Equity --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-secondary-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            EKUITAS
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Nama Akun</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $totalEquity = 0; @endphp
                    @foreach($equity as $account)
                        @php
                            $balance = $account->getBalance();
                            $totalEquity += $balance;
                        @endphp
                        <tr>
                            <td class="py-2 px-2 text-sm font-mono text-neutral-400">{{ $account->code }}</td>
                            <td class="py-2 px-2 text-sm text-white">{{ $account->name }}</td>
                            <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-secondary-500/10">
                        <td colspan="2" class="py-3 px-2 text-sm font-semibold text-right text-secondary-400">Total Ekuitas</td>
                        <td class="py-3 px-2 text-sm font-bold text-right text-secondary-400">Rp {{ number_format($totalEquity, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Summary --}}
    <div class="card bg-gradient-to-r from-primary-500/10 to-secondary-500/10 border-primary-500/20">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-xs text-neutral-400">Total Aset</p>
                <p class="text-lg font-bold text-primary-400">Rp {{ number_format($totalAssets, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs text-neutral-400">Liabilitas + Ekuitas</p>
                <p class="text-lg font-bold text-secondary-400">Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs text-neutral-400">Selisih</p>
                @php $difference = $totalAssets - ($totalLiabilities + $totalEquity); @endphp
                <p class="text-lg font-bold {{ $difference == 0 ? 'text-success-400' : 'text-danger-400' }}">
                    Rp {{ number_format(abs($difference), 0, ',', '.') }}
                    @if($difference == 0) ✓ @endif
                </p>
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
