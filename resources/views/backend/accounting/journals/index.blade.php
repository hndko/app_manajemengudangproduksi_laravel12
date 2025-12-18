@extends('layouts.app')

@section('title', 'Jurnal Umum')
@section('header', 'Jurnal Umum')
@section('subheader', 'Kelola entri jurnal akuntansi')

@section('content')
<div class="space-y-6">

    {{-- Filter & Actions --}}
    <div class="card">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('journals.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <select name="status" class="form-select pl-9 w-36">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="posted" {{ request('status') === 'posted' ? 'selected' : '' }}>Posted</option>
                        <option value="void" {{ request('status') === 'void' ? 'selected' : '' }}>Void</option>
                    </select>
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="relative">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input pl-9 w-40">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-neutral-500">-</span>
                <div class="relative">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input pl-9 w-40">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->hasAny(['status', 'start_date', 'end_date']))
                    <a href="{{ route('journals.index') }}" class="btn btn-ghost text-xs">Reset</a>
                @endif
            </form>
            <a href="{{ route('journals.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Jurnal
            </a>
        </div>
    </div>

    {{-- Journals Table --}}
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">No. Jurnal</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Tanggal</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Deskripsi</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Debit</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Kredit</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Status</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($journals as $journal)
                    <tr class="hover:bg-dark-card/50">
                        <td class="py-3 px-2">
                            <a href="{{ route('journals.show', $journal) }}" class="text-sm font-medium text-primary-400 hover:text-primary-300">
                                {{ $journal->number }}
                            </a>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-white">{{ $journal->date->format('d M Y') }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-neutral-300 truncate max-w-[250px] block">{{ $journal->description }}</span>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <span class="text-sm font-medium text-white">Rp {{ number_format($journal->total_debit, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <span class="text-sm font-medium text-white">Rp {{ number_format($journal->total_credit, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 px-2">
                            @switch($journal->status)
                                @case('draft')
                                    <span class="badge badge-warning">Draft</span>
                                    @break
                                @case('posted')
                                    <span class="badge badge-success">Posted</span>
                                    @break
                                @case('void')
                                    <span class="badge badge-danger">Void</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="py-3 px-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('journals.show', $journal) }}" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-primary-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @if($journal->status === 'draft')
                                <a href="{{ route('journals.edit', $journal) }}" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-warning-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Hapus jurnal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-danger-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-white mb-1">Belum Ada Jurnal</h3>
                            <p class="text-xs text-neutral-500 mb-3">Mulai dengan membuat jurnal baru</p>
                            <a href="{{ route('journals.create') }}" class="btn btn-primary">Buat Jurnal</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($journals->hasPages())
        <div class="pt-4 border-t border-dark-border mt-4">
            {{ $journals->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
