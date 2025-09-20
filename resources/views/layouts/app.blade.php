<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje Paneli</title>
    {{-- Tailwind should be available in your app build (vite). If not, include via CDN for demo. --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/css/filament-public.css" rel="stylesheet">
    {{-- Allow pages to push Filament styles here when needed --}}
    @stack('filament-styles')
</head>
<body>
    <header class="site-header bg-white shadow">
        <div class="max-w-7xl mx-auto flex items-center justify-between py-4 px-4">
            <a class="text-2xl font-semibold text-gray-900" href="/">DUT Voting</a>

            <nav class="hidden md:flex space-x-6">
                <a class="text-gray-600 hover:text-gray-900" href="{{ route('public.projects.index') }}">Projeler</a>
                <a class="text-gray-600 hover:text-gray-900" href="#features">Özellikler</a>
                <a class="text-gray-600 hover:text-gray-900" href="#pricing">Fiyatlar</a>
                <a class="text-gray-600 hover:text-gray-900" href="#contact">İletişim</a>
            </nav>

            <div class="flex items-center space-x-3">
                @auth
                    <!-- User is authenticated -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600">
                            Hoş geldin, <span class="font-medium text-gray-900">{{ auth()->user()->name }}</span>
                        </span>
                        <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 border border-red-600 text-red-600 rounded-md text-sm hover:bg-red-50 transition-colors">
                                Çıkış Yap
                            </button>
                        </form>
                    </div>
                @else
                    <!-- User is guest -->
                    <a class="px-3 py-1 border border-indigo-600 text-indigo-600 rounded-md text-sm hover:bg-indigo-50 transition-colors" 
                       href="{{ route('auth.login') }}">Giriş Yap</a>
                    <a class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 transition-colors" 
                       href="{{ route('auth.register') }}">Üye Ol</a>
                @endauth
            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    {{-- Allow pages to push Filament scripts here when needed --}}
    @stack('filament-scripts')
</body>
</html>
