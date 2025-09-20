<!DOCTYPE html>
<html lang="tr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Proje Paneli')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="h-full">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('user.index') }}" class="text-xl font-bold text-gray-900">
                        Proje Paneli
                    </a>
                </div>
                
                <div class="flex items-center space-x-8">
                    <a href="{{ route('user.index') }}" 
                       class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                        Ana Sayfa
                    </a>
                    <a href="{{ route('user.projects') }}" 
                       class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.projects') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                        Projeler
                    </a>
                    
                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-700">Merhaba, {{ Auth::user()->name }}</span>
                        </div>
                    @else
                        <a href="/admin/login" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
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
    <footer class="bg-white border-t mt-12">
        <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
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