<!-- Navbar -->
<header class="sticky top-0 z-20 bg-white dark:bg-dark-surface border-b border-neutral-200 dark:border-dark-border">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left: Mobile menu button & Search -->
        <div class="flex items-center gap-4">
            <!-- Mobile menu button -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-dark-card transition-colors"
            >
                <i data-feather="menu" class="w-5 h-5 text-neutral-600 dark:text-neutral-400"></i>
            </button>

            <!-- Search -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400"></i>
                    <input
                        type="text"
                        placeholder="Cari..."
                        class="pl-10 pr-4 py-2 bg-neutral-100 dark:bg-dark-card border-0 rounded-lg text-sm w-64 focus:ring-2 focus:ring-primary-400 focus:outline-none dark:text-neutral-100"
                    >
                </div>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
            <!-- Dark mode toggle -->
            <button
                onclick="toggleDarkMode()"
                class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-dark-card transition-colors"
                title="Toggle Dark Mode"
            >
                <i data-feather="moon" class="w-5 h-5 text-neutral-600 dark:text-neutral-400 dark:hidden"></i>
                <i data-feather="sun" class="w-5 h-5 text-neutral-400 hidden dark:block"></i>
            </button>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="relative p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-dark-card transition-colors"
                >
                    <i data-feather="bell" class="w-5 h-5 text-neutral-600 dark:text-neutral-400"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-danger-500 rounded-full"></span>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-dark-surface rounded-lg shadow-lg border border-neutral-200 dark:border-dark-border py-2 z-50"
                >
                    <div class="px-4 py-2 border-b border-neutral-200 dark:border-dark-border">
                        <h3 class="font-semibold text-neutral-800 dark:text-white">Notifikasi</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <div class="px-4 py-3 hover:bg-neutral-50 dark:hover:bg-dark-card">
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">Stok material rendah</p>
                            <p class="text-xs text-neutral-400 mt-1">5 menit lalu</p>
                        </div>
                        <div class="px-4 py-3 hover:bg-neutral-50 dark:hover:bg-dark-card">
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">Produksi PRD-2024-0001 selesai</p>
                            <p class="text-xs text-neutral-400 mt-1">1 jam lalu</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 border-t border-neutral-200 dark:border-dark-border">
                        <a href="#" class="text-sm text-primary-500 hover:text-primary-600">Lihat Semua</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-dark-card transition-colors"
                >
                    <img
                        src="{{ auth()->user()->avatar_url }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-8 h-8 rounded-full"
                    >
                    <span class="hidden md:block text-sm font-medium text-neutral-700 dark:text-neutral-200">
                        {{ auth()->user()->name }}
                    </span>
                    <i data-feather="chevron-down" class="w-4 h-4 text-neutral-400 hidden md:block"></i>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-surface rounded-lg shadow-lg border border-neutral-200 dark:border-dark-border py-2 z-50"
                >
                    <div class="px-4 py-2 border-b border-neutral-200 dark:border-dark-border">
                        <p class="text-sm font-medium text-neutral-800 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item flex items-center gap-2">
                        <i data-feather="user" class="w-4 h-4"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('settings.index') }}" class="dropdown-item flex items-center gap-2">
                        <i data-feather="settings" class="w-4 h-4"></i>
                        <span>Pengaturan</span>
                    </a>
                    <div class="border-t border-neutral-200 dark:border-dark-border my-2"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item flex items-center gap-2 w-full text-left text-danger-500">
                            <i data-feather="log-out" class="w-4 h-4"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
