@extends('layouts.app')

@section('title', 'Edit Satuan')
@section('header', 'Edit Satuan')
@section('subheader', 'Ubah satuan: ' . $unit->name)

@section('content')
<div class="max-w-md">
    <form action="{{ route('units.update', $unit) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="space-y-4">
                <div>
                    <label for="name" class="form-label">Nama Satuan <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name', $unit->name) }}" class="form-input pl-9 @error('name') border-danger-500 @enderror" placeholder="Contoh: Kilogram" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    @error('name')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="symbol" class="form-label">Simbol <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="text" id="symbol" name="symbol" value="{{ old('symbol', $unit->symbol) }}" class="form-input pl-9 @error('symbol') border-danger-500 @enderror" placeholder="Contoh: kg" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    @error('symbol')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Update
            </button>
            <a href="{{ route('units.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
