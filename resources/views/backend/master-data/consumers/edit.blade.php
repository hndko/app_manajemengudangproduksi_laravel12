@extends('layouts.app')

@section('title', 'Edit Konsumen')
@section('header', 'Edit Konsumen')
@section('subheader', 'Ubah data konsumen ' . $consumer->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('consumers.update', $consumer) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <div class="w-6 h-6 rounded bg-warning-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                Edit Informasi Konsumen
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kode <span class="text-danger-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $consumer->code) }}" class="form-input @error('code') border-danger-500 @enderror" required>
                    @error('code')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Nama <span class="text-danger-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $consumer->name) }}" class="form-input @error('name') border-danger-500 @enderror" required>
                    @error('name')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $consumer->phone) }}" class="form-input @error('phone') border-danger-500 @enderror" placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $consumer->email) }}" class="form-input @error('email') border-danger-500 @enderror" placeholder="email@example.com">
                    @error('email')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" rows="2" class="form-input @error('address') border-danger-500 @enderror">{{ old('address', $consumer->address) }}</textarea>
                    @error('address')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" value="{{ old('npwp', $consumer->npwp) }}" class="form-input @error('npwp') border-danger-500 @enderror" placeholder="00.000.000.0-000.000">
                    @error('npwp')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $consumer->contact_person) }}" class="form-input @error('contact_person') border-danger-500 @enderror">
                    @error('contact_person')
                        <p class="text-xs text-danger-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $consumer->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
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
                Update
            </button>
            <a href="{{ route('consumers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
