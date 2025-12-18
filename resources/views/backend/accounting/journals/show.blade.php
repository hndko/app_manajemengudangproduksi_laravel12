@extends('layouts.app')

@section('title', 'Detail Jurnal')
@section('header', 'Detail Jurnal')
@section('subheader', $journal->number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header Info --}}
    <div class="card">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-4 border-b border-dark-border">
            <div>
                <h2 class="text-xl font-bold text-white">{{ $journal->number }}</h2>
                <p class="text-sm text-neutral-400">{{ $journal->date->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @switch($journal->status)
                    @case('draft')
                        <span class="badge badge-warning text-sm px-4 py-2">Draft</span>
                        @break
                    @case('posted')
                        <span class="badge badge-success text-sm px-4 py-2">Posted</span>
                        @break
                    @case('void')
                        <span class="badge badge-danger text-sm px-4 py-2">Void</span>
                        @break
                @endswitch
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-xs text-neutral-500">Periode Fiskal</p>
                <p class="text-sm font-medium text-white">{{ $journal->fiscalPeriod?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-neutral-500">Dibuat Oleh</p>
                <p class="text-sm font-medium text-white">{{ $journal->creator?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-neutral-500">Diposting Oleh</p>
                <p class="text-sm font-medium text-white">{{ $journal->poster?->name ?? '-' }}</p>
            </div>
        </div>

        <div class="p-3 rounded-lg bg-dark-card">
            <p class="text-xs text-neutral-500 mb-1">Deskripsi</p>
            <p class="text-sm text-white">{{ $journal->description }}</p>
        </div>
    </div>

    {{-- Journal Details --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Detail Transaksi
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Nama Akun</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Keterangan</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Debit</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @foreach($journal->details as $detail)
                    <tr>
                        <td class="py-3 px-2">
                            <span class="text-sm font-mono text-neutral-400">{{ $detail->account->code }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-white">{{ $detail->account->name }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-neutral-400">{{ $detail->description ?? '-' }}</span>
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
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark-card font-semibold">
                        <td colspan="3" class="py-3 px-2 text-right text-sm text-white">Total</td>
                        <td class="py-3 px-2 text-right text-sm text-white">Rp {{ number_format($journal->total_debit, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-right text-sm text-white">Rp {{ number_format($journal->total_credit, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-2">
        @if($journal->status === 'draft')
            <form action="{{ route('journals.post', $journal) }}" method="POST" onsubmit="return confirm('Posting jurnal ini? Jurnal yang sudah diposting tidak dapat diedit.')">
                @csrf
                <button type="submit" class="btn btn-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Posting Jurnal
                </button>
            </form>
            <a href="{{ route('journals.edit', $journal) }}" class="btn btn-warning">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Hapus jurnal ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        @endif
        <a href="{{ route('journals.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

</div>
@endsection
