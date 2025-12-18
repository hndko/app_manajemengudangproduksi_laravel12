@extends('layouts.app')

@section('title', 'Daftar Akun')
@section('header', 'Daftar Akun (COA)')
@section('subheader', 'Kelola chart of accounts perusahaan')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        @php
            $types = [
                'aset' => ['label' => 'Aset', 'color' => 'primary', 'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4'],
                'liabilitas' => ['label' => 'Liabilitas', 'color' => 'danger', 'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z'],
                'ekuitas' => ['label' => 'Ekuitas', 'color' => 'secondary', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                'pendapatan' => ['label' => 'Pendapatan', 'color' => 'success', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                'beban' => ['label' => 'Beban', 'color' => 'warning', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ];
        @endphp
        @foreach($types as $type => $info)
        <div class="card">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-{{ $info['color'] }}-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-{{ $info['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-neutral-500">{{ $info['label'] }}</p>
                    <p class="text-lg font-bold text-white">{{ \App\Models\ChartOfAccount::where('type', $type)->count() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Accounts List --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Struktur Akun
            </h3>
            <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Akun
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Kode</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Nama Akun</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Tipe</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Saldo Normal</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Status</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($accounts as $account)
                        @include('backend.accounting.chart-of-accounts._account-row', ['account' => $account, 'level' => 0])
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-white mb-1">Belum Ada Akun</h3>
                            <p class="text-xs text-neutral-500 mb-3">Mulai dengan menambahkan akun baru</p>
                            <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">Tambah Akun</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
