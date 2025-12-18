@extends('layouts.app')

@section('title', 'Produk')
@section('header', 'Produk')
@section('subheader', 'Kelola data produk jadi')

@section('content')
<div class="space-y-4">
    <!-- Filter & Actions -->
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari produk..." class="form-input pl-9 w-48">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="relative">
                    <select name="category_id" class="form-select pl-9 w-40">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
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
                @if(!empty($filters['search']) || !empty($filters['category_id']) || isset($filters['is_active']))
                    <a href="{{ route('products.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($products as $product)
        <div class="card hover:shadow-lg transition-shadow group">
            <div class="aspect-square rounded-lg overflow-hidden bg-dark-card mb-3">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/default.svg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-xs text-secondary-400 font-medium">{{ $product->code }}</p>
                        <h3 class="font-semibold text-white line-clamp-1">{{ $product->name }}</h3>
                    </div>
                    <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">{{ $product->is_active ? 'Aktif' : 'Off' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs text-neutral-400">
                    <span>{{ $product->category?->name ?? '-' }}</span>
                    <span>{{ $product->unit?->symbol ?? '-' }}</span>
                </div>
                <div class="pt-2 border-t border-dark-border">
                    <p class="text-sm font-semibold text-secondary-400">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-1 pt-2">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-ghost flex-1 text-xs py-1.5">Detail</a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost flex-1 text-xs py-1.5">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost w-full text-xs py-1.5 text-danger-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="card text-center py-12">
                <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <h3 class="text-lg font-medium text-white mb-1">Belum Ada Produk</h3>
                <p class="text-sm text-neutral-400 mb-4">Mulai dengan menambahkan produk baru</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
            </div>
        </div>
        @endforelse
    </div>

    @if($products->hasPages())
    <div class="flex justify-center">{{ $products->links() }}</div>
    @endif
</div>
@endsection
