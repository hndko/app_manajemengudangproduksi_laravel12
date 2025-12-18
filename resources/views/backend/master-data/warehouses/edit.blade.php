@extends('layouts.app')

@section('title', 'Edit Gudang')
@section('header', 'Edit Gudang')
@section('subheader', 'Ubah data gudang: ' . $warehouse->name)

@section('content')
<div class="max-w-lg">
    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Informasi Gudang
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="form-label">Kode Gudang <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="code" name="code" value="{{ old('code', $warehouse->code) }}" class="form-input pl-9 @error('code') border-danger-500 @enderror" placeholder="GDG-01" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    @error('code')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="name" class="form-label">Nama Gudang <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name', $warehouse->name) }}" class="form-input pl-9 @error('name') border-danger-500 @enderror" placeholder="Gudang Utama" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    @error('name')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Telepon</label>
                    <div class="relative">
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $warehouse->phone) }}" class="form-input pl-9 @error('phone') border-danger-500 @enderror" placeholder="08xxxxxxxxxx">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    @error('phone')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer h-10">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-dark-border bg-dark-card text-primary-500" {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                        <span class="text-sm text-neutral-300">Gudang aktif</span>
                    </label>
                </div>
            </div>
            <div class="mt-4">
                <label for="address" class="form-label">Alamat</label>
                <div class="relative">
                    <textarea id="address" name="address" rows="3" class="form-input pl-9 @error('address') border-danger-500 @enderror" placeholder="Alamat lengkap gudang">{{ old('address', $warehouse->address) }}</textarea>
                    <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                @error('address')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Update
            </button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
