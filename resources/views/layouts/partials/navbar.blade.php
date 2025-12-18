<!-- Navbar -->
<header class="sticky top-0 z-20 bg-white/80 dark:bg-dark-surface/80 backdrop-blur-sm border-b border-neutral-200/50 dark:border-dark-border/50">
    <div class="flex items-center justify-between px-3 lg:px-5 h-12 lg:h-14">
        <!-- Left Side -->
        <div class="flex items-center gap-2">
            <!-- Menu Toggle -->
            <button
                @click="$store.app.toggleSidebar()"
                class="p-1.5 rounded-lg text-neutral-500 hover:bg-neutral-100 dark:hover:bg-dark-card lg:hidden"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Search (Desktop) -->
            <div class="hidden md:block relative">
                <input
                    type="text"
                    placeholder="Cari..."
                    class="w-48 lg:w-64 pl-8 pr-3 py-1.5 text-xs bg-neutral-100 dark:bg-dark-card border-0 rounded-lg focus:ring-1 focus:ring-primary-400 placeholder-neutral-400"
                >
                <svg class="w-4 h-4 absolute left-2.5 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-1 lg:gap-2">
            <!-- Notifications -->
            <div x-data="dropdown()" class="relative">
                <button
                    @click="toggle()"
                    class="p-1.5 rounded-lg text-neutral-500 hover:bg-neutral-100 dark:hover:bg-dark-card relative"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-danger-500 rounded-full"></span>
                </button>

                <div
                    x-show="open"
                    @click.away="close()"
                    x-transition
                    class="dropdown-menu w-64 p-0"
                >
                    <div class="p-2 border-b border-neutral-100 dark:border-dark-border">
                        <h3 class="text-xs font-semibold text-neutral-800 dark:text-white">Notifikasi</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <div class="p-3 text-center text-xs text-neutral-500">
                            Tidak ada notifikasi baru
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="dropdown()" class="relative">
                <button
                    @click="toggle()"
                    class="flex items-center gap-2 p-1 rounded-lg hover:bg-neutral-100 dark:hover:bg-dark-card"
                >
                    <div class="avatar text-xs">
                        {{ auth()->user()->initials }}
                    </div>
                    <span class="hidden sm:block text-xs font-medium text-neutral-700 dark:text-neutral-200 max-w-[80px] truncate">
                        {{ auth()->user()->name }}
                    </span>
                    <svg class="w-3 h-3 text-neutral-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.away="close()"
                    x-transition
                    class="dropdown-menu"
                >
                    <div class="px-3 py-2 border-b border-neutral-100 dark:border-dark-border">
                        <p class="text-xs font-medium text-neutral-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-neutral-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('settings.index') }}" class="dropdown-item flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan
                    </a>
                    @endif
                    <div class="border-t border-neutral-100 dark:border-dark-border my-1"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item flex items-center gap-2 w-full text-left text-danger-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
