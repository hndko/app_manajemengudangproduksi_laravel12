@extends('layouts.app')

@section('title', 'Detail Konsumen')
@section('header', $consumer->name)
@section('subheader', 'Kode: ' . $consumer->code)

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Status</p>
                    @if($consumer->is_active)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-success-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Total Transaksi</p>
                    <p class="text-lg font-bold text-white">{{ $consumer->salesTransactions->count() }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-secondary-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Terdaftar</p>
                    <p class="text-sm font-medium text-white">{{ $consumer->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-info-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-info-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            Informasi Detail
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-neutral-500">Nama Konsumen</p>
                    <p class="text-sm font-medium text-white">{{ $consumer->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Kode</p>
                    <p class="text-sm font-mono text-primary-400">{{ $consumer->code }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Telepon</p>
                    <p class="text-sm text-white">{{ $consumer->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Email</p>
                    <p class="text-sm text-white">{{ $consumer->email ?? '-' }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-neutral-500">Contact Person</p>
                    <p class="text-sm text-white">{{ $consumer->contact_person ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">NPWP</p>
                    <p class="text-sm font-mono text-white">{{ $consumer->npwp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">Alamat</p>
                    <p class="text-sm text-white">{{ $consumer->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    @if($consumer->salesTransactions->count() > 0)
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-success-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            Transaksi Terakhir
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Invoice</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Tanggal</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2">Total</th>
                        <th class="text-center text-xs font-medium text-neutral-500 uppercase py-2 px-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @foreach($consumer->salesTransactions as $transaction)
                    <tr>
                        <td class="py-2 px-2 text-sm font-mono text-primary-400">{{ $transaction->invoice_number }}</td>
                        <td class="py-2 px-2 text-sm text-neutral-400">{{ $transaction->date->format('d/m/Y') }}</td>
                        <td class="py-2 px-2 text-sm text-right text-white">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        <td class="py-2 px-2 text-center">
                            <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('consumers.edit', $consumer) }}" class="btn btn-warning">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        <a href="{{ route('consumers.index') }}" class="btn btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

</div>
@endsection
