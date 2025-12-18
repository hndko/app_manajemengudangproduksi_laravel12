@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')
@section('subheader', 'Tambah kategori material atau produk baru')

@section('content')
<div class="max-w-lg">
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Informasi Kategori
            </h3>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="form-label">Nama Kategori <span class="text-danger-400">*</span></label>
                <div class="relative">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input pl-9 @error('name') border-danger-500 @enderror"
                        placeholder="Masukkan nama kategori"
                        required
                    >
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
                @error('name')
                    <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-4">
                <label for="type" class="form-label">Tipe <span class="text-danger-400">*</span></label>
                <div class="relative">
                    <select
                        id="type"
                        name="type"
                        class="form-select pl-9 @error('type') border-danger-500 @enderror"
                        required
                    >
                        <option value="">Pilih tipe</option>
                        <option value="material" {{ old('type') === 'material' ? 'selected' : '' }}>Material</option>
                        <option value="product" {{ old('type') === 'product' ? 'selected' : '' }}>Produk</option>
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                @error('type')
                    <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="form-label">Deskripsi</label>
                <div class="relative">
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="form-input pl-9 @error('description') border-danger-500 @enderror"
                        placeholder="Deskripsi kategori (opsional)"
                    >{{ old('description') }}</textarea>
                    <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </div>
                @error('description')
                    <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Kategori
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
