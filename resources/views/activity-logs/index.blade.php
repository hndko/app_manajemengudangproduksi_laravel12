@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('header', 'Log Aktivitas')
@section('subheader', 'Riwayat aktivitas pengguna sistem')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Daftar Log Aktivitas</h3>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-sm">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user?->name ?? 'System' }}</td>
                    <td>
                        <span class="badge badge-{{ $log->action_color }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td class="text-sm text-neutral-500">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-neutral-500 py-8">
                        Belum ada log aktivitas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="p-4 border-t border-neutral-200 dark:border-dark-border">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
