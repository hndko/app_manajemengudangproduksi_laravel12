@extends('layouts.app')

@section('title', 'Buku Besar')
@section('header', 'Buku Besar')
@section('subheader', 'General Ledger per akun')

@section('content')
<div class="space-y-6">

    {{-- Filter & Export --}}
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('ledger.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <select name="type" class="form-select pl-9 w-40">
                        <option value="">Semua Tipe</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <input type="date" name="start_date" value="{{ request('start_date', now()->format('Y-m-d')) }}" class="form-input w-40">
                <span class="text-neutral-500">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" class="form-input w-40">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->hasAny(['start_date', 'end_date', 'type']))
                    <a href="{{ route('ledger.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('ledger.export.excel', request()->query()) }}" class="btn btn-secondary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('ledger.export.pdf', request()->query()) }}" target="_blank" class="btn btn-secondary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Ledger per Account --}}
    @forelse($accounts as $account)
        @if($account->journalDetails->count() > 0)
        <div class="card" x-data="{ open: false }">
            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-500/20 flex items-center justify-center">
                        <span class="text-xs font-bold text-primary-400">{{ $account->code }}</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-white">{{ $account->name }}</h4>
                        <p class="text-xs text-neutral-500">{{ $account->journalDetails->count() }} transaksi • {{ ucfirst($account->type) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @php
                        $totalDebit = $account->journalDetails->sum('debit');
                        $totalCredit = $account->journalDetails->sum('credit');
                        $balance = $account->normal_balance === 'debit' ? $totalDebit - $totalCredit : $totalCredit - $totalDebit;
                    @endphp
                    <div class="text-right">
                        <p class="text-xs text-neutral-500">Saldo</p>
                        <p class="text-sm font-bold {{ $balance >= 0 ? 'text-success-400' : 'text-danger-400' }}">
                            Rp {{ number_format(abs($balance), 0, ',', '.') }}
                            <span class="text-xs">({{ $balance >= 0 ? 'D' : 'K' }})</span>
                        </p>
                    </div>
                    <a href="{{ route('ledger.show', $account) }}" @click.stop class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-primary-400" title="Lihat Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    <svg class="w-5 h-5 text-neutral-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <div x-show="open" x-collapse class="mt-4 pt-4 border-t border-dark-border">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-dark-border">
                                <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Tanggal</th>
                                <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">No. Jurnal</th>
                                <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Keterangan</th>
                                <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Debit</th>
                                <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Kredit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border">
                            @foreach($account->journalDetails->take(5) as $detail)
                            <tr>
                                <td class="py-2 px-2 text-sm text-white">{{ $detail->journalEntry->date->format('d/m/Y') }}</td>
                                <td class="py-2 px-2">
                                    <a href="{{ route('journals.show', $detail->journalEntry) }}" class="text-sm text-primary-400 hover:text-primary-300">
                                        {{ $detail->journalEntry->number }}
                                    </a>
                                </td>
                                <td class="py-2 px-2 text-sm text-neutral-300">{{ $detail->description ?? $detail->journalEntry->description }}</td>
                                <td class="py-2 px-2 text-sm text-right {{ $detail->debit > 0 ? 'text-white font-medium' : 'text-neutral-500' }}">
                                    {{ $detail->debit > 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-2 px-2 text-sm text-right {{ $detail->credit > 0 ? 'text-white font-medium' : 'text-neutral-500' }}">
                                    {{ $detail->credit > 0 ? 'Rp ' . number_format($detail->credit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-dark-card font-semibold">
                                <td colspan="3" class="py-2 px-2 text-sm text-right text-white">Total</td>
                                <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                                <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($totalCredit, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    @if($account->journalDetails->count() > 5)
                    <div class="text-center pt-3">
                        <a href="{{ route('ledger.show', $account) }}" class="text-xs text-primary-400 hover:text-primary-300">
                            Lihat semua {{ $account->journalDetails->count() }} transaksi →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @empty
    <div class="card text-center py-12">
        <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <h3 class="text-sm font-medium text-white mb-1">Belum Ada Data Buku Besar</h3>
        <p class="text-xs text-neutral-500">Posting jurnal untuk melihat data buku besar</p>
    </div>
    @endforelse

    {{-- No Transactions Message --}}
    @if($accounts->count() > 0 && $accounts->every(fn($a) => $a->journalDetails->count() === 0))
    <div class="card text-center py-12">
        <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-sm font-medium text-white mb-1">Tidak Ada Transaksi</h3>
        <p class="text-xs text-neutral-500">Tidak ada transaksi untuk filter yang dipilih</p>
    </div>
    @endif

</div>
@endsection
