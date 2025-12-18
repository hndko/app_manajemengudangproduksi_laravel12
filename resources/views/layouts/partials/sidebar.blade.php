<!-- Sidebar -->
<aside
    class="sidebar scrollbar-thin overflow-hidden"
    :class="$store.app.sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <!-- Logo -->
    <div class="p-3 border-b border-white/10 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" class="h-8 w-auto">
            <div class="hidden lg:block">
                <h1 class="text-sm font-bold text-white leading-tight">Gudang Produksi</h1>
                <p class="text-[10px] text-neutral-400">Mari Partner</p>
            </div>
        </a>
        <!-- Close Button (Mobile Only) -->
        <button
            @click="$store.app.sidebarOpen = false"
            class="lg:hidden p-1.5 rounded-lg text-neutral-400 hover:text-white hover:bg-white/10 transition-colors"
            title="Tutup Sidebar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-2 space-y-1 overflow-y-auto h-[calc(100vh-180px)]">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-feather="home"></i>
            <span>Dashboard</span>
        </a>

        <!-- Kepegawaian -->
        @if(auth()->user()->isAdmin())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Kepegawaian</span>
        </div>
        <a href="{{ route('attendances.index') }}"
            class="sidebar-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
            <i data-feather="clock"></i>
            <span>Absensi</span>
        </a>
        <a href="{{ route('activity-logs.index') }}"
            class="sidebar-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
            <i data-feather="activity"></i>
            <span>Log Aktivitas</span>
        </a>
        @endif

        <!-- Akuntansi -->
        @if(auth()->user()->isAdmin())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Akuntansi</span>
        </div>
        <a href="{{ route('chart-of-accounts.index') }}"
            class="sidebar-item {{ request()->routeIs('chart-of-accounts.*') ? 'active' : '' }}">
            <i data-feather="list"></i>
            <span>Daftar Akun</span>
        </a>
        <a href="{{ route('journals.index') }}"
            class="sidebar-item {{ request()->routeIs('journals.*') ? 'active' : '' }}">
            <i data-feather="book"></i>
            <span>Jurnal Umum</span>
        </a>
        <a href="{{ route('ledger.index') }}"
            class="sidebar-item {{ request()->routeIs('ledger.*') ? 'active' : '' }}">
            <i data-feather="file-text"></i>
            <span>Buku Besar</span>
        </a>
        <a href="{{ route('reports.index') }}"
            class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i data-feather="pie-chart"></i>
            <span>Laporan</span>
        </a>
        @endif

        <!-- Master Data -->
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Master Data</span>
        </div>
        <a href="{{ route('consumers.index') }}"
            class="sidebar-item {{ request()->routeIs('consumers.*') ? 'active' : '' }}">
            <i data-feather="users"></i>
            <span>Konsumen</span>
        </a>
        <a href="{{ route('categories.index') }}"
            class="sidebar-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i data-feather="folder"></i>
            <span>Kategori</span>
        </a>
        <a href="{{ route('units.index') }}"
            class="sidebar-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
            <i data-feather="hash"></i>
            <span>Satuan</span>
        </a>
        <a href="{{ route('warehouses.index') }}"
            class="sidebar-item {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
            <i data-feather="database"></i>
            <span>Gudang</span>
        </a>
        @endif

        <!-- Warehouse -->
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Warehouse</span>
        </div>
        <a href="{{ route('materials.index') }}"
            class="sidebar-item {{ request()->routeIs('materials.*') ? 'active' : '' }}">
            <i data-feather="box"></i>
            <span>Material</span>
        </a>
        <a href="{{ route('products.index') }}"
            class="sidebar-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i data-feather="package"></i>
            <span>Produk</span>
        </a>
        <a href="{{ route('stocks.index') }}"
            class="sidebar-item {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
            <i data-feather="layers"></i>
            <span>Stok</span>
        </a>
        @endif

        <!-- Manufaktur -->
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Manufaktur</span>
        </div>
        <a href="{{ route('productions.index') }}"
            class="sidebar-item {{ request()->routeIs('productions.*') ? 'active' : '' }}">
            <i data-feather="tool"></i>
            <span>Produksi</span>
        </a>
        <a href="{{ route('production-teams.index') }}"
            class="sidebar-item {{ request()->routeIs('production-teams.*') ? 'active' : '' }}">
            <i data-feather="users"></i>
            <span>Tim Produksi</span>
        </a>
        @endif

        <!-- Ekspedisi -->
        @if(auth()->user()->isAdmin() || auth()->user()->isEkspedisi())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Ekspedisi</span>
        </div>
        <a href="{{ route('delivery-notes.index') }}"
            class="sidebar-item {{ request()->routeIs('delivery-notes.*') ? 'active' : '' }}">
            <i data-feather="truck"></i>
            <span>Surat Jalan</span>
        </a>
        <a href="{{ route('returns.index') }}"
            class="sidebar-item {{ request()->routeIs('returns.*') ? 'active' : '' }}">
            <i data-feather="rotate-ccw"></i>
            <span>Retur</span>
        </a>
        @endif

        <!-- Transaksi -->
        @if(auth()->user()->isAdmin())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Transaksi</span>
        </div>
        <a href="{{ route('sales.index') }}"
            class="sidebar-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i data-feather="shopping-cart"></i>
            <span>Penjualan</span>
        </a>
        <a href="{{ route('expenses.index') }}"
            class="sidebar-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i data-feather="credit-card"></i>
            <span>Pengeluaran</span>
        </a>
        @endif

        <!-- Tools -->
        @if(auth()->user()->isAdmin())
        <div class="pt-3 pb-1">
            <span class="px-3 text-[10px] font-semibold text-neutral-500 uppercase tracking-wider">Tools</span>
        </div>
        <a href="{{ route('calculator.pph21') }}"
            class="sidebar-item {{ request()->routeIs('calculator.*') ? 'active' : '' }}">
            <i data-feather="calculator"></i>
            <span>Kalkulator PPh21</span>
        </a>
        @endif
    </nav>

    <!-- User Info -->
    <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-white/10 bg-sidebar">
        <div class="flex items-center gap-2">
            <div class="avatar">
                {{ auth()->user()->initials }}
            </div>
            <div class="flex-1 min-w-0 hidden lg:block">
                <p class="text-xs font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-neutral-400 truncate">{{ auth()->user()->role?->display_name }}</p>
            </div>
        </div>
    </div>
</aside>
