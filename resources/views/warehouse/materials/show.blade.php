@extends('layouts.app')

@section('title', 'Detail Material')
@section('header', $material->name)
@section('subheader', 'Detail informasi material')

@section('content')
<div class="space-y-4">
    <!-- Back Button -->
    <div>
        <a href="{{ route('materials.index') }}" class="btn btn-ghost text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-4">
            <div class="card">
                <div class="flex items-start gap-4">
                    <!-- Image -->
                    <div class="w-32 h-32 rounded-lg overflow-hidden bg-dark-card flex-shrink-0">
                        <img
                            src="{{ $material->image ? asset('storage/' . $material->image) : asset('assets/img/default.svg') }}"
                            alt="{{ $material->name }}"
                            class="w-full h-full object-cover"
                        >
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-primary-400 font-medium">{{ $material->code }}</p>
                                <h2 class="text-xl font-bold text-white">{{ $material->name }}</h2>
                            </div>
                            <span class="badge {{ $material->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $material->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-xs text-neutral-500">Kategori</p>
                                <p class="text-sm text-white font-medium">{{ $material->category?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500">Satuan</p>
                                <p class="text-sm text-white font-medium">{{ $material->unit?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500">Harga</p>
                                <p class="text-sm text-accent-400 font-bold">Rp {{ number_format($material->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500">Stok Minimum</p>
                                <p class="text-sm text-white font-medium">{{ $material->minimum_stock }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($material->description)
                <div class="mt-4 pt-4 border-t border-dark-border">
                    <p class="text-xs text-neutral-500 mb-1">Deskripsi</p>
                    <p class="text-sm text-neutral-300">{{ $material->description }}</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-4 pt-4 border-t border-dark-border flex items-center gap-2">
                    <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('materials.destroy', $material) }}" method="POST"
                          onsubmit="return confirm('Hapus material ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stock Info -->
        <div class="space-y-4">
            <div class="card">
                <h3 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    Stok per Gudang
                </h3>

                @if($material->stocks->count() > 0)
                <div class="space-y-2">
                    @foreach($material->stocks as $stock)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-dark-card">
                        <span class="text-sm text-neutral-300">{{ $stock->warehouse?->name }}</span>
                        <span class="text-sm font-bold {{ $stock->quantity <= $material->minimum_stock ? 'text-danger-400' : 'text-success-400' }}">
                            {{ $stock->quantity }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-6 text-neutral-500">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-xs">Belum ada stok</p>
                </div>
                @endif
            </div>

            <!-- Meta Info -->
            <div class="card">
                <h3 class="text-sm font-semibold text-white mb-3">Informasi Tambahan</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Dibuat</span>
                        <span class="text-neutral-300">{{ $material->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Diperbarui</span>
                        <span class="text-neutral-300">{{ $material->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
