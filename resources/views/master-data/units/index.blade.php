@extends('layouts.app')

@section('title', 'Satuan')
@section('header', 'Satuan')
@section('subheader', 'Kelola satuan pengukuran')

@section('content')
<div class="space-y-4">
    <!-- Filter & Actions -->
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('units.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari satuan..." class="form-input pl-9 w-56">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(!empty($filters['search']))
                    <a href="{{ route('units.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>
            <a href="{{ route('units.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Satuan
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Simbol</th>
                        <th class="w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td class="font-medium text-white">{{ $unit->name }}</td>
                        <td><span class="badge badge-primary">{{ $unit->symbol }}</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('units.edit', $unit) }}" class="p-1.5 rounded hover:bg-dark-card text-neutral-400 hover:text-warning-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus satuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded hover:bg-dark-card text-neutral-400 hover:text-danger-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-neutral-500">Belum ada data satuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($units->hasPages())
        <div class="p-4 border-t border-dark-border">{{ $units->links() }}</div>
        @endif
    </div>
</div>
@endsection
