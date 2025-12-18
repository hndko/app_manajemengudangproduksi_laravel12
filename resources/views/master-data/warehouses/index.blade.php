@extends('layouts.app')

@section('title', 'Gudang')
@section('header', 'Gudang')
@section('subheader', 'Kelola lokasi gudang penyimpanan')

@section('content')
<div class="space-y-4">
    <!-- Filter & Actions -->
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('warehouses.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari gudang..." class="form-input pl-9 w-48">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="relative">
                    <select name="is_active" class="form-select pl-9 w-32">
                        <option value="">Status</option>
                        <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(!empty($filters['search']) || isset($filters['is_active']))
                    <a href="{{ route('warehouses.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>
            <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Gudang
            </a>
        </div>
    </div>

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($warehouses as $warehouse)
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="badge {{ $warehouse->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $warehouse->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <p class="text-xs text-primary-400 font-medium">{{ $warehouse->code }}</p>
            <h3 class="font-semibold text-white mb-1">{{ $warehouse->name }}</h3>
            @if($warehouse->address)
            <p class="text-xs text-neutral-400 line-clamp-2 mb-2">{{ $warehouse->address }}</p>
            @endif

            <div class="flex items-center gap-4 text-xs text-neutral-500">
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    {{ $warehouse->stocks_count }} item
                </span>
                @if($warehouse->phone)
                <span>{{ $warehouse->phone }}</span>
                @endif
            </div>

            <div class="flex items-center gap-1 mt-3 pt-3 border-t border-dark-border">
                <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-ghost flex-1 text-xs py-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus gudang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost w-full text-xs py-1.5 text-danger-400 hover:text-danger-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="card text-center py-12">
                <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-lg font-medium text-white mb-1">Belum Ada Gudang</h3>
                <p class="text-sm text-neutral-400 mb-4">Mulai dengan menambahkan gudang baru</p>
                <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Tambah Gudang</a>
            </div>
        </div>
        @endforelse
    </div>

    @if($warehouses->hasPages())
    <div class="flex justify-center">{{ $warehouses->links() }}</div>
    @endif
</div>
@endsection
