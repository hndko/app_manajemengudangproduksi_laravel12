@extends('layouts.app')

@section('title', 'Pengaturan')
@section('header', 'Pengaturan')
@section('subheader', 'Kelola pengaturan sistem dan website')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left Column - Main Settings --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Company Settings --}}
                <div class="card">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-dark-border">
                        <div class="w-8 h-8 rounded-lg bg-primary-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Informasi Perusahaan</h3>
                            <p class="text-xs text-neutral-500">Data perusahaan untuk invoice dan dokumen</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @php
                            $companySettings = $settings['company'] ?? collect();
                        @endphp

                        @foreach($companySettings as $setting)
                            @if($setting->type !== 'image')
                            <div>
                                <label class="form-label">{{ $setting->description ?? ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                                @if($setting->type === 'textarea')
                                <textarea name="settings[{{ $setting->key }}]" rows="2" class="form-input" placeholder="Masukkan {{ strtolower($setting->description ?? '') }}">{{ $setting->value }}</textarea>
                                @else
                                <input type="{{ $setting->type === 'email' ? 'email' : 'text' }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-input" placeholder="Masukkan {{ strtolower($setting->description ?? '') }}">
                                @endif
                            </div>
                            @endif
                        @endforeach

                        @if($companySettings->isEmpty())
                        <p class="text-sm text-neutral-500 text-center py-4">Belum ada pengaturan perusahaan</p>
                        @endif
                    </div>
                </div>

                {{-- Invoice Settings --}}
                @if(isset($settings['invoice']) && $settings['invoice']->count() > 0)
                <div class="card">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-dark-border">
                        <div class="w-8 h-8 rounded-lg bg-secondary-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Pengaturan Invoice</h3>
                            <p class="text-xs text-neutral-500">Konfigurasi format dan template invoice</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($settings['invoice'] as $setting)
                        <div class="{{ $setting->type === 'textarea' ? 'md:col-span-2' : '' }}">
                            <label class="form-label">{{ $setting->description ?? ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                            @if($setting->type === 'textarea')
                            <textarea name="settings[{{ $setting->key }}]" rows="2" class="form-input">{{ $setting->value }}</textarea>
                            @else
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-input">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- General Settings --}}
                @if(isset($settings['general']) && $settings['general']->count() > 0)
                <div class="card">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-dark-border">
                        <div class="w-8 h-8 rounded-lg bg-accent-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Pengaturan Umum</h3>
                            <p class="text-xs text-neutral-500">Konfigurasi dasar sistem</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($settings['general'] as $setting)
                        <div>
                            <label class="form-label">{{ $setting->description ?? ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                            @if($setting->type === 'boolean')
                            <label class="flex items-center gap-2 cursor-pointer mt-2">
                                <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                <input type="checkbox" name="settings[{{ $setting->key }}]" value="1" class="w-4 h-4 rounded border-dark-border bg-dark-card text-primary-500" {{ $setting->value ? 'checked' : '' }}>
                                <span class="text-sm text-neutral-300">Aktifkan</span>
                            </label>
                            @elseif($setting->type === 'number')
                            <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-input">
                            @else
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="form-input">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Save Button --}}
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pengaturan
                </button>

            </div>

            {{-- Right Column - Logo & Quick Links --}}
            <div class="space-y-6">

                {{-- Company Logo --}}
                <div class="card" x-data="{
                    imagePreview: '{{ ($settings['company'] ?? collect())->firstWhere('type', 'image')?->value ? asset('storage/' . ($settings['company'] ?? collect())->firstWhere('type', 'image')->value) : asset('assets/img/default.svg') }}'
                }">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-dark-border">
                        <div class="w-8 h-8 rounded-lg bg-warning-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Logo Perusahaan</h3>
                            <p class="text-xs text-neutral-500">Untuk header invoice</p>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto rounded-xl overflow-hidden bg-dark-card border-2 border-dashed border-dark-border flex items-center justify-center mb-4">
                            <img :src="imagePreview" class="w-full h-full object-contain p-2" onerror="this.src='{{ asset('assets/img/default.svg') }}'">
                        </div>
                        <label class="btn btn-secondary cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Logo
                            <input type="file" name="company_logo" accept="image/*" class="hidden"
                                @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">
                        </label>
                        <p class="text-xs text-neutral-500 mt-2">PNG, JPG max 2MB</p>
                    </div>
                </div>

                {{-- System Info --}}
                <div class="card">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-dark-border">
                        <div class="w-8 h-8 rounded-lg bg-success-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Info Sistem</h3>
                            <p class="text-xs text-neutral-500">Status & versi</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-dark-border">
                            <span class="text-xs text-neutral-500">Laravel</span>
                            <span class="text-xs font-medium text-white">{{ app()->version() }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-dark-border">
                            <span class="text-xs text-neutral-500">PHP</span>
                            <span class="text-xs font-medium text-white">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-dark-border">
                            <span class="text-xs text-neutral-500">Environment</span>
                            <span class="badge badge-{{ app()->environment('production') ? 'success' : 'warning' }} text-[10px]">{{ app()->environment() }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-xs text-neutral-500">Timezone</span>
                            <span class="text-xs font-medium text-white">{{ config('app.timezone') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

    {{-- Menu Navigasi --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-dark-border">
            <div class="w-8 h-8 rounded-lg bg-primary-500/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">Menu Pengaturan Lainnya</h3>
                <p class="text-xs text-neutral-500">Akses cepat ke pengaturan terkait</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-dark-card hover:bg-dark-border/50 transition-colors text-center group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-white">User</span>
            </a>

            <a href="{{ route('backups.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-dark-card hover:bg-dark-border/50 transition-colors text-center group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-white">Backup</span>
            </a>

            <a href="{{ route('activity-logs.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-dark-card hover:bg-dark-border/50 transition-colors text-center group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-white">Log</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-dark-card hover:bg-dark-border/50 transition-colors text-center group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-white">Profil</span>
            </a>
        </div>
    </div>

</div>
@endsection
