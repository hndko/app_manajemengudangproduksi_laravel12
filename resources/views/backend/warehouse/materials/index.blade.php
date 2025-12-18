@extends('layouts.app')

@section('title', 'Material')
@section('header', 'Material')
@section('subheader', 'Kelola data material bahan baku')

@section('content')
<div class="space-y-4">
    <!-- Filter & Actions -->
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Filters -->
            <form action="{{ route('materials.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Cari material..."
                        class="form-input pl-9 w-48"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Category Filter -->
                <div class="relative">
                    <select name="category_id" class="form-select pl-9 w-40">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select name="is_active" class="form-select pl-9 w-32">
                        <option value="">Semua Status</option>
                        <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter
                </button>

                @if(!empty($filters['search']) || !empty($filters['category_id']) || isset($filters['is_active']))
                    <a href="{{ route('materials.index') }}" class="btn btn-ghost text-xs">
                        Reset
                    </a>
                @endif
            </form>

            <!-- Add Button -->
            <a href="{{ route('materials.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Material
            </a>
        </div>
    </div>

    <!-- Materials Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($materials as $material)
        <div class="card hover:shadow-lg transition-shadow group">
            <!-- Image -->
            <div class="aspect-square rounded-lg overflow-hidden bg-dark-card mb-3">
                <img
                    src="{{ $material->image ? asset('storage/' . $material->image) : asset('assets/img/default.svg') }}"
                    alt="{{ $material->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                >
            </div>

            <!-- Info -->
            <div class="space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-xs text-primary-400 font-medium">{{ $material->code }}</p>
                        <h3 class="font-semibold text-white line-clamp-1">{{ $material->name }}</h3>
                    </div>
                    <span class="badge {{ $material->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $material->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-xs text-neutral-400">
                    <span>{{ $material->category?->name ?? '-' }}</span>
                    <span>{{ $material->unit?->name ?? '-' }}</span>
                </div>

                <div class="pt-2 border-t border-dark-border">
                    <p class="text-sm font-semibold text-accent-400">
                        Rp {{ number_format($material->price, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-1 pt-2">
                    <a href="{{ route('materials.show', $material) }}" class="btn btn-ghost flex-1 text-xs py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </a>
                    <a href="{{ route('materials.edit', $material) }}" class="btn btn-ghost flex-1 text-xs py-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('materials.destroy', $material) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Hapus material ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost w-full text-xs py-1.5 text-danger-400 hover:text-danger-300">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-lg font-medium text-white mb-1">Belum Ada Material</h3>
                <p class="text-sm text-neutral-400 mb-4">Mulai dengan menambahkan material baru</p>
                <a href="{{ route('materials.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Material
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($materials->hasPages())
    <div class="flex justify-center">
        {{ $materials->links() }}
    </div>
    @endif
</div>
@endsection
