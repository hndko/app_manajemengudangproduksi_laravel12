@extends('layouts.app')

@section('title', 'Kategori')
@section('header', 'Kategori')
@section('subheader', 'Kelola kategori material dan produk')

@section('content')
<div class="space-y-4">
    <!-- Filter & Actions -->
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Filters -->
            <form action="{{ route('categories.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Cari kategori..."
                        class="form-input pl-9 w-48"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Type Filter -->
                <div class="relative">
                    <select name="type" class="form-select pl-9 w-36">
                        <option value="">Semua Tipe</option>
                        <option value="material" {{ ($filters['type'] ?? '') === 'material' ? 'selected' : '' }}>Material</option>
                        <option value="product" {{ ($filters['type'] ?? '') === 'product' ? 'selected' : '' }}>Produk</option>
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter
                </button>

                @if(!empty($filters['search']) || !empty($filters['type']))
                    <a href="{{ route('categories.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>

            <!-- Add Button -->
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($categories as $category)
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg {{ $category->type === 'material' ? 'bg-gradient-to-br from-accent-500 to-accent-600' : 'bg-gradient-to-br from-secondary-500 to-secondary-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($category->type === 'material')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        @endif
                    </svg>
                </div>
                <span class="badge {{ $category->type === 'material' ? 'badge-accent' : 'badge-secondary' }}">
                    {{ $category->type === 'material' ? 'Material' : 'Produk' }}
                </span>
            </div>

            <h3 class="font-semibold text-white mb-1">{{ $category->name }}</h3>
            @if($category->description)
            <p class="text-xs text-neutral-400 line-clamp-2 mb-3">{{ $category->description }}</p>
            @endif

            <div class="flex items-center gap-4 text-xs text-neutral-500 mt-2">
                @if($category->type === 'material')
                <span>{{ $category->materials_count }} material</span>
                @else
                <span>{{ $category->products_count }} produk</span>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-1 mt-3 pt-3 border-t border-dark-border">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-ghost flex-1 text-xs py-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Hapus kategori ini?')">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-white mb-1">Belum Ada Kategori</h3>
                <p class="text-sm text-neutral-400 mb-4">Mulai dengan menambahkan kategori baru</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kategori
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="flex justify-center">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
