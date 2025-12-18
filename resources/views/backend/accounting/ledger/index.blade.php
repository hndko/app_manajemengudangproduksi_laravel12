@extends('layouts.app')

@section('title', 'Buku Besar')
@section('header', 'Buku Besar')
@section('subheader', 'General Ledger per akun')

@section('content')
<div class="space-y-6">

    {{-- Filter --}}
    <div class="card">
        <form action="{{ route('ledger.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
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
                <a href="{{ route('ledger.index') }}" class="btn btn-ghost text-xs">Reset</a>
            @endif
        </form>
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
                        <p class="text-xs text-neutral-500">{{ $account->journalDetails->count() }} transaksi</p>
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
                            @foreach($account->journalDetails as $detail)
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

</div>
@endsection
