<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-50 dark:bg-dark-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-white">Gudang Produksi</h1>
            <p class="text-neutral-500">Mari Partner</p>
        </div>

        <!-- Login Card -->
        <div class="card">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-white mb-6">Masuk ke Akun Anda</h2>

            @if(session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input @error('email') border-danger-500 @enderror"
                        placeholder="nama@email.com"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input pr-10 @error('password') border-danger-500 @enderror"
                            placeholder="••••••••"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600"
                        >
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-neutral-300 text-primary-500 focus:ring-primary-400">
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-neutral-500 mt-6">
            &copy; {{ date('Y') }} <span class="font-semibold text-primary-500">Mari Partner</span>. All rights reserved.
        </p>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                password.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</body>
</html>
