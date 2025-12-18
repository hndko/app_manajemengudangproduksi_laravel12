@extends('layouts.app')

@section('title', 'Konsumen')
@section('header', 'Data Konsumen')
@section('subheader', 'Kelola data pelanggan/konsumen')

@section('content')
<div class="space-y-4">

    {{-- Filter & Actions --}}
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('consumers.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari nama, kode, telepon..."
                           value="{{ $filters['search'] ?? '' }}"
                           class="form-input pl-9 w-64">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select name="is_active" class="form-select w-36">
                    <option value="">Semua Status</option>
                    <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(!empty($filters['search']) || isset($filters['is_active']))
                    <a href="{{ route('consumers.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>

            <a href="{{ route('consumers.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Konsumen
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-dark-border">
                        <th class="text-left text-xs font-semibold text-neutral-500 uppercase py-3 px-4">Kode</th>
                        <th class="text-left text-xs font-semibold text-neutral-500 uppercase py-3 px-4">Nama</th>
                        <th class="text-left text-xs font-semibold text-neutral-500 uppercase py-3 px-4">Kontak</th>
                        <th class="text-left text-xs font-semibold text-neutral-500 uppercase py-3 px-4">CP</th>
                        <th class="text-center text-xs font-semibold text-neutral-500 uppercase py-3 px-4">Status</th>
                        <th class="text-center text-xs font-semibold text-neutral-500 uppercase py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-dark-border">
                    @forelse($consumers as $consumer)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-dark-card/50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="font-mono text-xs text-primary-600">{{ $consumer->code }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div>
                                <p class="text-sm font-medium text-neutral-800 dark:text-white">{{ $consumer->name }}</p>
                                @if($consumer->address)
                                    <p class="text-xs text-neutral-500 truncate max-w-xs">{{ Str::limit($consumer->address, 40) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="space-y-1">
                                @if($consumer->phone)
                                    <p class="text-xs flex items-center gap-1 text-neutral-600 dark:text-neutral-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $consumer->phone }}
                                    </p>
                                @endif
                                @if($consumer->email)
                                    <p class="text-xs flex items-center gap-1 text-neutral-600 dark:text-neutral-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $consumer->email }}
                                    </p>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $consumer->contact_person ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($consumer->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('consumers.show', $consumer) }}"
                                   class="p-1.5 rounded-lg text-neutral-500 hover:text-info-600 hover:bg-info-50 dark:hover:bg-info-500/10 transition-colors"
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('consumers.edit', $consumer) }}"
                                   class="p-1.5 rounded-lg text-neutral-500 hover:text-warning-600 hover:bg-warning-50 dark:hover:bg-warning-500/10 transition-colors"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('consumers.destroy', $consumer) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus konsumen {{ $consumer->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-neutral-500 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-500/10 transition-colors"
                                            title="Hapus">
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
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-neutral-500 text-sm">Belum ada data konsumen</p>
                                <a href="{{ route('consumers.create') }}" class="btn btn-primary mt-3 text-sm">Tambah Konsumen</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($consumers->hasPages())
        <div class="border-t border-neutral-200 dark:border-dark-border px-4 py-3">
            {{ $consumers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
