@extends('layouts.app')

@section('title', 'Detail Buku Besar - ' . $account->code)
@section('header', 'Detail Buku Besar')
@section('subheader', $account->code . ' - ' . $account->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Account Info Card --}}
    <div class="card bg-gradient-to-r from-primary-500/10 to-secondary-500/10 border-primary-500/20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-primary-500/20 flex items-center justify-center">
                    <span class="text-lg font-bold text-primary-400">{{ $account->code }}</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">{{ $account->name }}</h2>
                    <div class="flex items-center gap-2 text-sm text-neutral-400">
                        <span class="badge badge-{{ ['aset'=>'primary','liabilitas'=>'danger','ekuitas'=>'secondary','pendapatan'=>'success','beban'=>'warning'][$account->type] ?? 'neutral' }}">{{ ucfirst($account->type) }}</span>
                        <span>•</span>
                        <span>Saldo Normal: {{ ucfirst($account->normal_balance) }}</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-neutral-500">Total Debit</p>
                    <p class="text-sm font-bold text-success-400">Rp {{ number_format($summary['total_debit'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Total Kredit</p>
                    <p class="text-sm font-bold text-danger-400">Rp {{ number_format($summary['total_credit'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Saldo</p>
                    <p class="text-sm font-bold {{ $summary['balance'] >= 0 ? 'text-white' : 'text-danger-400' }}">
                        Rp {{ number_format(abs($summary['balance']), 0, ',', '.') }}
                        <span class="text-xs">({{ $summary['balance'] >= 0 ? 'D' : 'K' }})</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card">
        <form action="{{ route('ledger.show', $account) }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input pl-9 w-40">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <span class="text-neutral-500">s/d</span>
            <div class="relative">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input pl-9 w-40">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['start_date', 'end_date']))
                <a href="{{ route('ledger.show', $account) }}" class="btn btn-ghost text-xs">Reset</a>
            @endif
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Riwayat Transaksi
            <span class="text-xs text-neutral-500 font-normal">({{ $details->total() }} transaksi)</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Tanggal</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">No. Jurnal</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Keterangan</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Debit</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Kredit</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @php $runningBalance = 0; @endphp
                    @forelse($details as $detail)
                    @php
                        if ($account->normal_balance === 'debit') {
                            $runningBalance += $detail->debit - $detail->credit;
                        } else {
                            $runningBalance += $detail->credit - $detail->debit;
                        }
                    @endphp
                    <tr class="hover:bg-dark-card/50">
                        <td class="py-3 px-2">
                            <span class="text-sm text-white">{{ $detail->journalEntry->date->format('d M Y') }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <a href="{{ route('journals.show', $detail->journalEntry) }}" class="text-sm text-primary-400 hover:text-primary-300">
                                {{ $detail->journalEntry->number }}
                            </a>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-neutral-300">{{ $detail->description ?? $detail->journalEntry->description }}</span>
                        </td>
                        <td class="py-3 px-2 text-right">
                            @if($detail->debit > 0)
                                <span class="text-sm font-medium text-white">Rp {{ number_format($detail->debit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm text-neutral-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-2 text-right">
                            @if($detail->credit > 0)
                                <span class="text-sm font-medium text-white">Rp {{ number_format($detail->credit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm text-neutral-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-2 text-right">
                            <span class="text-sm font-medium {{ $runningBalance >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                                Rp {{ number_format(abs($runningBalance), 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-white mb-1">Tidak Ada Transaksi</h3>
                            <p class="text-xs text-neutral-500">Belum ada transaksi untuk akun ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($details->hasPages())
        <div class="pt-4 border-t border-dark-border mt-4">
            {{ $details->links() }}
        </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('ledger.index') }}" class="btn btn-secondary">
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
