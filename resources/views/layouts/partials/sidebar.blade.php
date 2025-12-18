<!-- Sidebar -->
<aside
    id="sidebar"
    class="sidebar"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
    x-cloak
>
    <!-- Logo -->
    <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
        <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-10 w-auto">
        <div>
            <h1 class="font-bold text-white text-lg leading-tight">Mari Partner</h1>
            <p class="text-xs text-neutral-400">Gudang Produksi</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 scrollbar-thin">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-feather="home" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- Kepegawaian -->
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Kepegawaian</p>
        </div>
        <a href="{{ route('attendances.index') }}" class="sidebar-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
            <i data-feather="clock" class="w-5 h-5"></i>
            <span>Absensi</span>
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('activity-logs.index') }}" class="sidebar-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
            <i data-feather="activity" class="w-5 h-5"></i>
            <span>Log Aktivitas</span>
        </a>
        @endif

        <!-- Akuntansi (Admin Only) -->
        @if(auth()->user()->isAdmin())
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Akuntansi</p>
        </div>
        <a href="{{ route('chart-of-accounts.index') }}" class="sidebar-item {{ request()->routeIs('chart-of-accounts.*') ? 'active' : '' }}">
            <i data-feather="book" class="w-5 h-5"></i>
            <span>Daftar Akun</span>
        </a>
        <a href="{{ route('journals.index') }}" class="sidebar-item {{ request()->routeIs('journals.*') ? 'active' : '' }}">
            <i data-feather="file-text" class="w-5 h-5"></i>
            <span>Jurnal Umum</span>
        </a>
        <a href="{{ route('ledger.index') }}" class="sidebar-item {{ request()->routeIs('ledger.*') ? 'active' : '' }}">
            <i data-feather="book-open" class="w-5 h-5"></i>
            <span>Buku Besar</span>
        </a>
        <a href="{{ route('reports.index') }}" class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i data-feather="bar-chart-2" class="w-5 h-5"></i>
            <span>Laporan Keuangan</span>
        </a>
        @endif

        <!-- Master Data -->
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Master Data</p>
        </div>
        <a href="{{ route('consumers.index') }}" class="sidebar-item {{ request()->routeIs('consumers.*') ? 'active' : '' }}">
            <i data-feather="users" class="w-5 h-5"></i>
            <span>Konsumen</span>
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('categories.index') }}" class="sidebar-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i data-feather="folder" class="w-5 h-5"></i>
            <span>Kategori</span>
        </a>
        <a href="{{ route('units.index') }}" class="sidebar-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
            <i data-feather="hash" class="w-5 h-5"></i>
            <span>Satuan</span>
        </a>
        <a href="{{ route('warehouses.index') }}" class="sidebar-item {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
            <i data-feather="package" class="w-5 h-5"></i>
            <span>Gudang</span>
        </a>
        @endif
        @endif

        <!-- Warehouse -->
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Warehouse</p>
        </div>
        <a href="{{ route('materials.index') }}" class="sidebar-item {{ request()->routeIs('materials.*') ? 'active' : '' }}">
            <i data-feather="box" class="w-5 h-5"></i>
            <span>Material</span>
        </a>
        <a href="{{ route('products.index') }}" class="sidebar-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i data-feather="shopping-bag" class="w-5 h-5"></i>
            <span>Produk</span>
        </a>
        <a href="{{ route('stocks.index') }}" class="sidebar-item {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
            <i data-feather="layers" class="w-5 h-5"></i>
            <span>Stok</span>
        </a>
        @endif

        <!-- Manufaktur -->
        @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Manufaktur</p>
        </div>
        <a href="{{ route('productions.index') }}" class="sidebar-item {{ request()->routeIs('productions.*') ? 'active' : '' }}">
            <i data-feather="tool" class="w-5 h-5"></i>
            <span>Produksi</span>
        </a>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isEkspedisi())
        <a href="{{ route('delivery-notes.index') }}" class="sidebar-item {{ request()->routeIs('delivery-notes.*') ? 'active' : '' }}">
            <i data-feather="truck" class="w-5 h-5"></i>
            <span>Surat Jalan</span>
        </a>
        <a href="{{ route('returns.index') }}" class="sidebar-item {{ request()->routeIs('returns.*') ? 'active' : '' }}">
            <i data-feather="rotate-ccw" class="w-5 h-5"></i>
            <span>Retur</span>
        </a>
        @endif

        <!-- Transaksi (Admin Only) -->
        @if(auth()->user()->isAdmin())
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Transaksi</p>
        </div>
        <a href="{{ route('sales.index') }}" class="sidebar-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i data-feather="shopping-cart" class="w-5 h-5"></i>
            <span>Penjualan</span>
        </a>
        <a href="{{ route('expenses.index') }}" class="sidebar-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i data-feather="credit-card" class="w-5 h-5"></i>
            <span>Pengeluaran</span>
        </a>
        @endif

        <!-- Perhitungan -->
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Perhitungan</p>
        </div>
        <a href="{{ route('calculator.pph21') }}" class="sidebar-item {{ request()->routeIs('calculator.*') ? 'active' : '' }}">
            <i data-feather="percent" class="w-5 h-5"></i>
            <span>Kalkulator PPh21</span>
        </a>

        <!-- Settings (Admin Only) -->
        @if(auth()->user()->isAdmin())
        <div class="px-4 mt-6 mb-2">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Pengaturan</p>
        </div>
        <a href="{{ route('settings.index') }}" class="sidebar-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i data-feather="settings" class="w-5 h-5"></i>
            <span>Pengaturan Web</span>
        </a>
        <a href="{{ route('users.index') }}" class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i data-feather="user-plus" class="w-5 h-5"></i>
            <span>Kelola User</span>
        </a>
        <a href="{{ route('backups.index') }}" class="sidebar-item {{ request()->routeIs('backups.*') ? 'active' : '' }}">
            <i data-feather="database" class="w-5 h-5"></i>
            <span>Backup Data</span>
        </a>
        @endif
    </nav>

    <!-- User Info -->
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-neutral-400 truncate">{{ auth()->user()->role->display_name }}</p>
            </div>
        </div>
    </div>
</aside>
