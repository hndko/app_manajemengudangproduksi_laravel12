@extends('layouts.app')

@section('title', 'Edit Konsumen')
@section('header', 'Edit Konsumen')
@section('subheader', 'Ubah data konsumen: ' . $consumer->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('consumers.update', $consumer) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Informasi Konsumen
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Code -->
                <div>
                    <label for="code" class="form-label">Kode Konsumen <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code', $consumer->code) }}"
                            class="form-input pl-9 @error('code') border-danger-500 @enderror"
                            placeholder="CST-001"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    @error('code')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Nama Konsumen <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $consumer->name) }}"
                            class="form-input pl-9 @error('name') border-danger-500 @enderror"
                            placeholder="Nama lengkap / perusahaan"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    @error('name')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="form-label">Telepon</label>
                    <div class="relative">
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $consumer->phone) }}"
                            class="form-input pl-9 @error('phone') border-danger-500 @enderror"
                            placeholder="08xxxxxxxxxx"
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    @error('phone')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email</label>
                    <div class="relative">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $consumer->email) }}"
                            class="form-input pl-9 @error('email') border-danger-500 @enderror"
                            placeholder="email@contoh.com"
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    @error('email')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Person -->
                <div>
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="contact_person"
                            name="contact_person"
                            value="{{ old('contact_person', $consumer->contact_person) }}"
                            class="form-input pl-9 @error('contact_person') border-danger-500 @enderror"
                            placeholder="Nama kontak person"
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    @error('contact_person')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NPWP -->
                <div>
                    <label for="npwp" class="form-label">NPWP</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="npwp"
                            name="npwp"
                            value="{{ old('npwp', $consumer->npwp) }}"
                            class="form-input pl-9 @error('npwp') border-danger-500 @enderror"
                            placeholder="00.000.000.0-000.000"
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    @error('npwp')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div class="mt-4">
                <label for="address" class="form-label">Alamat</label>
                <div class="relative">
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="form-input pl-9 @error('address') border-danger-500 @enderror"
                        placeholder="Alamat lengkap konsumen"
                    >{{ old('address', $consumer->address) }}</textarea>
                    <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                @error('address')
                    <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="w-4 h-4 rounded border-dark-border bg-dark-card text-primary-500"
                        {{ old('is_active', $consumer->is_active) ? 'checked' : '' }}
                    >
                    <span class="text-sm text-neutral-300">Konsumen aktif</span>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Konsumen
            </button>
            <a href="{{ route('consumers.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
