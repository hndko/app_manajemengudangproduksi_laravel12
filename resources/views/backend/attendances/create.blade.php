@extends('layouts.app')

@section('title', 'Absen Hari Ini')
@section('header', 'Absen Hari Ini')
@section('subheader', 'Catat kehadiran Anda')

@section('content')
<div class="max-w-lg mx-auto">
    <form action="{{ route('attendances.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Date & Time Display --}}
        <div class="card text-center">
            <div class="mb-4">
                <p class="text-sm text-neutral-400">{{ now()->translatedFormat('l') }}</p>
                <h2 class="text-3xl font-bold text-white" x-data x-text="new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></h2>
            </div>
            <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4" x-data="{ time: '' }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'}), 1000)">
                <span class="text-2xl font-bold text-white" x-text="time"></span>
            </div>
        </div>

        {{-- Attendance Form --}}
        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Form Absensi
            </h3>

            {{-- Status --}}
            <div class="mb-4">
                <label class="form-label">Status Kehadiran <span class="text-danger-400">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                    @php
                        $statuses = [
                            'hadir' => ['label' => 'Hadir', 'color' => 'success', 'icon' => 'M5 13l4 4L19 7'],
                            'izin' => ['label' => 'Izin', 'color' => 'warning', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                            'sakit' => ['label' => 'Sakit', 'color' => 'danger', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                            'cuti' => ['label' => 'Cuti', 'color' => 'primary', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            'alpha' => ['label' => 'Alpha', 'color' => 'neutral', 'icon' => 'M6 18L18 6M6 6l12 12'],
                        ];
                    @endphp
                    @foreach($statuses as $value => $status)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="{{ $value }}" class="peer sr-only" {{ old('status', 'hadir') === $value ? 'checked' : '' }}>
                        <div class="p-3 rounded-xl border-2 border-dark-border bg-dark-card text-center transition-all peer-checked:border-{{ $status['color'] }}-500 peer-checked:bg-{{ $status['color'] }}-500/10 hover:border-{{ $status['color'] }}-500/50">
                            <svg class="w-5 h-5 mx-auto mb-1 text-{{ $status['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $status['icon'] }}"></path>
                            </svg>
                            <span class="text-xs font-medium text-white">{{ $status['label'] }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('status')
                    <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Clock In/Out Times --}}
            <div class="grid grid-cols-2 gap-4 mb-4" x-data="{ status: '{{ old('status', 'hadir') }}' }" x-init="$watch('status', value => { if(value !== 'hadir') { document.getElementById('clock_in').value = ''; document.getElementById('clock_out').value = ''; } })">
                <div>
                    <label for="clock_in" class="form-label">Jam Masuk</label>
                    <div class="relative">
                        <input type="time" id="clock_in" name="clock_in" value="{{ old('clock_in', now()->format('H:i')) }}" class="form-input pl-9">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    @error('clock_in')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="clock_out" class="form-label">Jam Pulang</label>
                    <div class="relative">
                        <input type="time" id="clock_out" name="clock_out" value="{{ old('clock_out') }}" class="form-input pl-9">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    @error('clock_out')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label for="notes" class="form-label">Catatan</label>
                <div class="relative">
                    <textarea id="notes" name="notes" rows="2" class="form-input pl-9" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Absensi
                </button>
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>

    </form>
</div>
@endsection
