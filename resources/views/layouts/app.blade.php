<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
    <script src="https://unpkg.com/feather-icons"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen bg-neutral-50 dark:bg-dark-bg scrollbar-thin" x-data="{ sidebarOpen: false }">
    <!-- Sidebar Overlay (Mobile) -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        id="sidebar-overlay"
    ></div>

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        <!-- Navbar -->
        @include('layouts.partials.navbar')

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-6">
            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <nav class="mb-4 text-sm">
                    @yield('breadcrumb')
                </nav>
            @endif

            <!-- Page Header -->
            @hasSection('header')
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-neutral-800 dark:text-white">
                        @yield('header')
                    </h1>
                    @hasSection('subheader')
                        <p class="mt-1 text-neutral-500">@yield('subheader')</p>
                    @endif
                </div>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-4 animate-fade-in">
                    <i data-feather="check-circle" class="w-5 h-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-4 animate-fade-in">
                    <i data-feather="alert-circle" class="w-5 h-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning mb-4 animate-fade-in">
                    <i data-feather="alert-triangle" class="w-5 h-5"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>

    <!-- Initialize Feather Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>

    @stack('scripts')
</body>
</html>
