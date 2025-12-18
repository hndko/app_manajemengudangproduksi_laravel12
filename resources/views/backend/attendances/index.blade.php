@extends('layouts.app')

@section('title', 'Absensi')
@section('header', 'Absensi')
@section('subheader', 'Kelola data absensi karyawan')

@section('content')
<div class="space-y-6">

    {{-- Today's Quick Attendance --}}
    @php
        $todayAttendance = auth()->user()->attendances()->whereDate('date', today())->first();
    @endphp

    <div class="card bg-gradient-to-r from-primary-500/10 to-secondary-500/10 border-primary-500/20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-primary-500/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-neutral-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <h3 class="text-lg font-bold text-white">Absensi Hari Ini</h3>
                    @if($todayAttendance)
                        <p class="text-sm text-neutral-300">
                            Status: <span class="font-medium text-{{ $todayAttendance->status_color }}-400">{{ ucfirst($todayAttendance->status) }}</span>
                            @if($todayAttendance->clock_in)
                                • Masuk: {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}
                            @endif
                            @if($todayAttendance->clock_out)
                                • Pulang: {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}
                            @endif
                        </p>
                    @else
                        <p class="text-sm text-neutral-400">Belum absen hari ini</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                @if(!$todayAttendance)
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Clock In
                    </a>
                @elseif(!$todayAttendance->clock_out && $todayAttendance->status === 'hadir')
                    <form action="{{ route('attendances.update', $todayAttendance) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="clock_out" value="{{ now()->format('H:i') }}">
                        <input type="hidden" name="status" value="hadir">
                        <button type="submit" class="btn btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Clock Out
                        </button>
                    </form>
                @else
                    <span class="badge badge-success text-sm px-4 py-2">✓ Sudah Absen</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Riwayat Absensi
            </h3>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('attendances.create') }}" class="btn btn-secondary text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Manual
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-border">
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Tanggal</th>
                        @if(auth()->user()->isAdmin())
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Nama</th>
                        @endif
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Masuk</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Pulang</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Status</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Durasi</th>
                        <th class="text-left text-xs font-medium text-neutral-500 uppercase py-3 px-2">Catatan</th>
                        <th class="text-right text-xs font-medium text-neutral-500 uppercase py-3 px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-dark-card/50">
                        <td class="py-3 px-2">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $attendance->date->format('d M Y') }}</p>
                                <p class="text-xs text-neutral-500">{{ $attendance->date->translatedFormat('l') }}</p>
                            </div>
                        </td>
                        @if(auth()->user()->isAdmin())
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-primary-500/20 flex items-center justify-center text-xs font-medium text-primary-400">
                                    {{ $attendance->user->initials ?? substr($attendance->user->name, 0, 2) }}
                                </div>
                                <span class="text-sm text-white">{{ $attendance->user->name }}</span>
                            </div>
                        </td>
                        @endif
                        <td class="py-3 px-2">
                            @if($attendance->clock_in)
                                <span class="text-sm text-white font-medium">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</span>
                            @else
                                <span class="text-sm text-neutral-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            @if($attendance->clock_out)
                                <span class="text-sm text-white font-medium">{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}</span>
                            @else
                                <span class="text-sm text-neutral-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <span class="badge badge-{{ $attendance->status_color }}">{{ ucfirst($attendance->status) }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-white">{{ $attendance->working_hours ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-sm text-neutral-400 truncate max-w-[150px] block">{{ $attendance->notes ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('attendances.edit', $attendance) }}" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-warning-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Hapus data absensi ini?')">
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
                        <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" class="py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-white mb-1">Belum Ada Data Absensi</h3>
                            <p class="text-xs text-neutral-500">Data absensi akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
        <div class="pt-4 border-t border-dark-border mt-4">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
