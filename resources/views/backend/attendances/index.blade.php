@extends('layouts.app')

@section('title', 'Absensi')
@section('header', 'Absensi')
@section('subheader', 'Kelola data absensi karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Daftar Absensi</h3>
        <a href="{{ route('attendances.create') }}" class="btn btn-primary">
            <i data-feather="plus" class="w-4 h-4"></i>
            Absen Hari Ini
        </a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Status</th>
                    <th>Jam Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $attendance->status_color }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </td>
                    <td>{{ $attendance->working_hours ?? '-' }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('attendances.show', $attendance) }}" class="text-primary-500 hover:text-primary-600">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('attendances.edit', $attendance) }}" class="text-warning-500 hover:text-warning-600">
                                <i data-feather="edit" class="w-4 h-4"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-neutral-500 py-8">
                        Belum ada data absensi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendances->hasPages())
    <div class="p-4 border-t border-neutral-200 dark:border-dark-border">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection
