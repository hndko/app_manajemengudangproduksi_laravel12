@extends('layouts.app')

@section('title', 'Konsumen')
@section('header', 'Konsumen')
@section('subheader', 'Kelola data konsumen dan pelanggan')

@section('content')
<div class="space-y-4">
    <!-- Filter & Actions -->
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Filters -->
            <form action="{{ route('consumers.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Cari nama, kode, telepon..."
                        class="form-input pl-9 w-64"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select name="is_active" class="form-select pl-9 w-32">
                        <option value="">Semua</option>
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

                @if(!empty($filters['search']) || isset($filters['is_active']))
                    <a href="{{ route('consumers.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>

            <!-- Add Button -->
            <a href="{{ route('consumers.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Konsumen
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consumers as $consumer)
                    <tr>
                        <td>
                            <span class="text-primary-400 font-medium">{{ $consumer->code }}</span>
                        </td>
                        <td>
                            <div>
                                <p class="font-medium text-white">{{ $consumer->name }}</p>
                                @if($consumer->contact_person)
                                <p class="text-xs text-neutral-500">CP: {{ $consumer->contact_person }}</p>
                                @endif
                            </div>
                        </td>
                        <td>{{ $consumer->phone ?? '-' }}</td>
                        <td>{{ $consumer->email ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $consumer->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $consumer->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('consumers.show', $consumer) }}" class="p-1.5 rounded hover:bg-dark-card text-neutral-400 hover:text-white" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('consumers.edit', $consumer) }}" class="p-1.5 rounded hover:bg-dark-card text-neutral-400 hover:text-warning-400" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('consumers.destroy', $consumer) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus konsumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded hover:bg-dark-card text-neutral-400 hover:text-danger-400" title="Hapus">
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
                        <td colspan="6" class="text-center py-8">
                            <svg class="w-10 h-10 mx-auto text-neutral-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-neutral-500">Belum ada data konsumen</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($consumers->hasPages())
        <div class="p-4 border-t border-dark-border">
            {{ $consumers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
