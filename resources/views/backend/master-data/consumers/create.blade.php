@extends('layouts.app')

@section('title', 'Tambah Konsumen')
@section('header', 'Tambah Konsumen')
@section('subheader', 'Tambah data pelanggan baru')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('consumers.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <div class="w-6 h-6 rounded bg-primary-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                Informasi Konsumen
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kode <span class="text-danger-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $lastCode) }}" class="form-input @error('code') border-danger-500 @enderror" required>
                    @error('code')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Nama <span class="text-danger-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-danger-500 @enderror" required>
                    @error('name')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input @error('phone') border-danger-500 @enderror" placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-danger-500 @enderror" placeholder="email@example.com">
                    @error('email')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" rows="2" class="form-input @error('address') border-danger-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" value="{{ old('npwp') }}" class="form-input @error('npwp') border-danger-500 @enderror" placeholder="00.000.000.0-000.000">
                    @error('npwp')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-input @error('contact_person') border-danger-500 @enderror">
                    @error('contact_person')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-neutral-700 dark:text-neutral-300">Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan
            </button>
            <a href="{{ route('consumers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
