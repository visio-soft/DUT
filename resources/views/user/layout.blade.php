<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Proje Paneli')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Ensure CSS is loaded properly -->
    <style>
        /* Fallback styling in case CSS doesn't load immediately */
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .loading { opacity: 0.5; }
        /* Remove loading state when CSS is loaded */
        .loaded .loading { opacity: 1; }
    </style>

    <script>
        // Mark document as loaded when CSS is ready
        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.classList.add('loaded');
        });
    </script>
</head>
<body class="h-full">
    <!-- Header -->
    <header class="user-header">
        <nav class="user-container">
            <div class="user-nav">
                <div class="flex items-center">
                    <a href="{{ route('user.index') }}" class="user-logo">
                        🌱 Proje Paneli
                    </a>
                </div>

                <div class="user-nav-links">
                    <a href="{{ route('user.index') }}"
                       class="user-nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                        Ana Sayfa
                    </a>
                    <a href="{{ route('user.projects') }}"
                       class="user-nav-link {{ request()->routeIs('user.projects') ? 'active' : '' }}">
                        Projeler
                    </a>

                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-white/90">Merhaba, {{ Auth::user()->name }}</span>
                        </div>
                    @else
                        <a href="/admin/login" class="user-nav-link">
                            Giriş Yap
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="user-footer">
        <div class="user-container">
            <div class="user-footer-content">
                © 2025 Proje Paneli. Tüm hakları saklıdır.
            </div>
        </div>
    </footer>

    <!-- AJAX Setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>
