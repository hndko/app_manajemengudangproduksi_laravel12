@extends('layouts.app')

@section('title', 'Edit Produk')
@section('header', 'Edit Produk')
@section('subheader', 'Ubah data produk: ' . $product->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Informasi Produk
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="form-label">Kode Produk <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="code" name="code" value="{{ old('code', $product->code) }}" class="form-input pl-9 @error('code') border-danger-500 @enderror" placeholder="PRD-001" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    @error('code')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="name" class="form-label">Nama Produk <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-input pl-9 @error('name') border-danger-500 @enderror" placeholder="Nama produk" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    @error('name')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="category_id" class="form-label">Kategori <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <select id="category_id" name="category_id" class="form-select pl-9 @error('category_id') border-danger-500 @enderror" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    @error('category_id')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="unit_id" class="form-label">Satuan <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <select id="unit_id" name="unit_id" class="form-select pl-9 @error('unit_id') border-danger-500 @enderror" required>
                            <option value="">Pilih satuan</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->symbol }})</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    @error('unit_id')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="minimum_stock" class="form-label">Stok Minimum <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" class="form-input pl-9 @error('minimum_stock') border-danger-500 @enderror" min="0" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    @error('minimum_stock')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="base_price" class="form-label">Harga Dasar <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="number" id="base_price" name="base_price" value="{{ old('base_price', $product->base_price) }}" class="form-input pl-9 @error('base_price') border-danger-500 @enderror" min="0" required>
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-neutral-500">Rp</span>
                    </div>
                    @error('base_price')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-4">
                <label for="description" class="form-label">Deskripsi</label>
                <div class="relative">
                    <textarea id="description" name="description" rows="3" class="form-input pl-9 @error('description') border-danger-500 @enderror" placeholder="Deskripsi produk (opsional)">{{ old('description', $product->description) }}</textarea>
                    <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-dark-border bg-dark-card text-primary-500" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <span class="text-sm text-neutral-300">Produk aktif</span>
                </label>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="card" x-data="{ imagePreview: '{{ $product->image ? asset('storage/' . $product->image) : '' }}' }">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Gambar Produk
            </h3>
            <div class="flex items-start gap-4">
                <div class="w-32 h-32 rounded-lg overflow-hidden bg-dark-card border-2 border-dashed border-dark-border">
                    <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                    <img x-show="!imagePreview" src="{{ asset('assets/img/default.svg') }}" class="w-full h-full object-cover opacity-50">
                </div>
                <div class="flex-1">
                    <label class="block">
                        <input type="file" name="image" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">
                        <span class="btn btn-secondary cursor-pointer">Ganti Gambar</span>
                    </label>
                    <p class="text-xs text-neutral-500 mt-2">Format: JPG, PNG, WebP. Maksimal 2MB</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Update Produk
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
