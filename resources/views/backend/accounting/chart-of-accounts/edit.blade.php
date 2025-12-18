@extends('layouts.app')

@section('title', 'Edit Akun')
@section('header', 'Edit Akun')
@section('subheader', 'Ubah data akun: ' . $chartOfAccount->code . ' - ' . $chartOfAccount->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('chart-of-accounts.update', $chartOfAccount) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Informasi Akun
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="form-label">Kode Akun <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="code" name="code" value="{{ old('code', $chartOfAccount->code) }}" class="form-input pl-9 font-mono @error('code') border-danger-500 @enderror" placeholder="1-100" required {{ $chartOfAccount->is_locked ? 'readonly' : '' }}>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    @error('code')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="name" class="form-label">Nama Akun <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name', $chartOfAccount->name) }}" class="form-input pl-9 @error('name') border-danger-500 @enderror" placeholder="Nama akun" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    @error('name')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="type" class="form-label">Tipe Akun <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <select id="type" name="type" class="form-select pl-9 @error('type') border-danger-500 @enderror" required {{ $chartOfAccount->is_locked ? 'disabled' : '' }}>
                            <option value="">Pilih tipe</option>
                            <option value="aset" {{ old('type', $chartOfAccount->type) === 'aset' ? 'selected' : '' }}>Aset</option>
                            <option value="liabilitas" {{ old('type', $chartOfAccount->type) === 'liabilitas' ? 'selected' : '' }}>Liabilitas</option>
                            <option value="ekuitas" {{ old('type', $chartOfAccount->type) === 'ekuitas' ? 'selected' : '' }}>Ekuitas</option>
                            <option value="pendapatan" {{ old('type', $chartOfAccount->type) === 'pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                            <option value="beban" {{ old('type', $chartOfAccount->type) === 'beban' ? 'selected' : '' }}>Beban</option>
                        </select>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    @if($chartOfAccount->is_locked)
                        <input type="hidden" name="type" value="{{ $chartOfAccount->type }}">
                    @endif
                    @error('type')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="normal_balance" class="form-label">Saldo Normal <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <select id="normal_balance" name="normal_balance" class="form-select pl-9 @error('normal_balance') border-danger-500 @enderror" required {{ $chartOfAccount->is_locked ? 'disabled' : '' }}>
                            <option value="">Pilih saldo normal</option>
                            <option value="debit" {{ old('normal_balance', $chartOfAccount->normal_balance) === 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="kredit" {{ old('normal_balance', $chartOfAccount->normal_balance) === 'kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    @if($chartOfAccount->is_locked)
                        <input type="hidden" name="normal_balance" value="{{ $chartOfAccount->normal_balance }}">
                    @endif
                    @error('normal_balance')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="parent_id" class="form-label">Akun Induk</label>
                    <div class="relative">
                        <select id="parent_id" name="parent_id" class="form-select pl-9">
                            <option value="">-- Tanpa Induk (Top Level) --</option>
                            @foreach($parentAccounts as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $chartOfAccount->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->code }} - {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="form-label">Deskripsi</label>
                    <div class="relative">
                        <textarea id="description" name="description" rows="2" class="form-input pl-9" placeholder="Deskripsi akun (opsional)">{{ old('description', $chartOfAccount->description) }}</textarea>
                        <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-dark-border bg-dark-card text-primary-500" {{ old('is_active', $chartOfAccount->is_active) ? 'checked' : '' }}>
                        <span class="text-sm text-neutral-300">Akun aktif</span>
                    </label>
                </div>
            </div>

            @if($chartOfAccount->is_locked)
            <div class="mt-4 p-3 rounded-lg bg-warning-500/10 border border-warning-500/20">
                <p class="text-xs text-warning-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Akun ini terkunci. Beberapa field tidak dapat diubah.
                </p>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Akun
            </button>
            <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
