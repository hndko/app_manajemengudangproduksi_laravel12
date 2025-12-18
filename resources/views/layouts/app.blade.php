<!DOCTYPE html>
<html lang="id" class="dark" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen" x-init="$store.app.init()">
    <!-- Sidebar Overlay (Mobile) -->
    <div
        x-show="$store.app.sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.app.closeSidebar()"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    ></div>

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content -->
    <div
        class="transition-all duration-300"
        :class="$store.app.sidebarOpen ? 'lg:ml-56 xl:ml-60' : 'ml-0'"
    >
        <!-- Navbar -->
        @include('layouts.partials.navbar')

        <!-- Page Content -->
        <main class="p-3 lg:p-5 min-h-[calc(100vh-60px)]">
            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <nav class="mb-3 text-xs">
                    @yield('breadcrumb')
                </nav>
            @endif

            <!-- Page Header -->
            @hasSection('header')
                <div class="mb-4">
                    <h1 class="text-lg lg:text-xl font-bold text-neutral-800 dark:text-white">
                        @yield('header')
                    </h1>
                    @hasSection('subheader')
                        <p class="text-xs lg:text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">
                            @yield('subheader')
                        </p>
                    @endif
                </div>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition
                    class="alert alert-success mb-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    class="alert alert-danger mb-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </main>
    </div>
    </div>

    <script>
        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace({ width: 16, height: 16 });
        });

        // Re-init feather after Alpine updates
        document.addEventListener('alpine:initialized', () => {
            feather.replace({ width: 16, height: 16 });
        });
    </script>

    @stack('scripts')
</body>
</html>
