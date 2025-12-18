@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Profil Saya')
@section('subheader', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Profile Overview Card -->
    <div class="card">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <!-- Avatar -->
            <div class="relative" x-data="{ imagePreview: '{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}' }">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-4 border-dark-border shadow-xl">
                    <img
                        x-show="imagePreview"
                        :src="imagePreview"
                        class="w-full h-full object-cover"
                        alt="{{ $user->name }}"
                    >
                    <span
                        x-show="!imagePreview"
                        class="text-3xl font-bold text-white"
                    >
                        {{ $user->initials }}
                    </span>
                </div>
                <div class="absolute -bottom-2 -right-2">
                    <label class="w-8 h-8 rounded-full bg-primary-500 hover:bg-primary-600 flex items-center justify-center cursor-pointer transition-colors shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input
                            type="file"
                            name="avatar_quick"
                            accept="image/*"
                            class="hidden"
                            form="profile-form"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => imagePreview = e.target.result;
                                    reader.readAsDataURL(file);
                                    document.getElementById('avatar-input').files = $event.target.files;
                                }
                            "
                        >
                    </label>
                </div>
            </div>

            <!-- Info -->
            <div class="text-center sm:text-left flex-1">
                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-neutral-400">{{ $user->email }}</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                    <span class="badge badge-primary">{{ $user->role?->display_name ?? 'User' }}</span>
                    <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="px-4 py-2 rounded-lg bg-dark-card">
                    <p class="text-2xl font-bold text-primary-400">{{ $user->activityLogs()->count() }}</p>
                    <p class="text-xs text-neutral-500">Aktivitas</p>
                </div>
                <div class="px-4 py-2 rounded-lg bg-dark-card">
                    <p class="text-2xl font-bold text-secondary-400">{{ $user->attendances()->count() }}</p>
                    <p class="text-xs text-neutral-500">Absensi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Update Profile Form -->
        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Informasi Profil
            </h3>

            <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Hidden file input for avatar -->
                <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*">

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="form-input pl-9 @error('name') border-danger-500 @enderror"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    @error('name')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="form-input pl-9 @error('email') border-danger-500 @enderror"
                            placeholder="email@example.com"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    @error('email')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="form-label">Telepon</label>
                    <div class="relative">
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="form-input pl-9 @error('phone') border-danger-500 @enderror"
                            placeholder="08xxxxxxxxxx"
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    @error('phone')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password Form -->
        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Ubah Password
            </h3>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            class="form-input pl-9 @error('current_password') border-danger-500 @enderror"
                            placeholder="Masukkan password saat ini"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    @error('current_password')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="form-label">Password Baru <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input pl-9 @error('password') border-danger-500 @enderror"
                            placeholder="Minimal 8 karakter"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    @error('password')
                        <p class="text-xs text-danger-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input pl-9"
                            placeholder="Ulangi password baru"
                            required
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-warning w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Info -->
    <div class="card">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informasi Akun
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-3 rounded-lg bg-dark-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-neutral-500">Role</p>
                        <p class="text-sm font-medium text-white">{{ $user->role?->display_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded-lg bg-dark-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-secondary-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-neutral-500">Bergabung</p>
                        <p class="text-sm font-medium text-white">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded-lg bg-dark-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-accent-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-neutral-500">Login Terakhir</p>
                        <p class="text-sm font-medium text-white">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded-lg bg-dark-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-success-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-neutral-500">Status</p>
                        <p class="text-sm font-medium {{ $user->is_active ? 'text-success-400' : 'text-danger-400' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Aktivitas Terakhir
            </h3>
            <a href="{{ route('activity-logs.index') }}" class="text-xs text-primary-400 hover:text-primary-300">
                Lihat Semua →
            </a>
        </div>

        @php
            $recentActivities = $user->activityLogs()->latest()->take(5)->get();
        @endphp

        @if($recentActivities->count() > 0)
        <div class="space-y-3">
            @foreach($recentActivities as $activity)
            <div class="flex items-start gap-3 p-3 rounded-lg bg-dark-card">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                    @switch($activity->action)
                        @case('create') bg-success-500/20 @break
                        @case('update') bg-warning-500/20 @break
                        @case('delete') bg-danger-500/20 @break
                        @default bg-primary-500/20
                    @endswitch
                ">
                    <svg class="w-4 h-4
                        @switch($activity->action)
                            @case('create') text-success-400 @break
                            @case('update') text-warning-400 @break
                            @case('delete') text-danger-400 @break
                            @default text-primary-400
                        @endswitch
                    " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @switch($activity->action)
                            @case('create')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                @break
                            @case('update')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                @break
                            @case('delete')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                @break
                            @default
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endswitch
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white">{{ $activity->description }}</p>
                    <p class="text-xs text-neutral-500 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <svg class="w-12 h-12 mx-auto text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-neutral-400">Belum ada aktivitas tercatat</p>
        </div>
        @endif
    </div>

</div>
@endsection
