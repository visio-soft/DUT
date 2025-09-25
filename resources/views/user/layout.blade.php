<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DUT Vote')</title>

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
    @if($hasBackgroundImages ?? false)
        <!-- Background Image Data -->
        <script>
            window.backgroundImageData = {
                hasImages: {{ $hasBackgroundImages ? 'true' : 'false' }},
                randomImage: @json($randomBackgroundImage)
            };
        </script>
    @endif
    <!-- Header -->
    <header class="user-header">
        <nav class="user-container">
            <div class="user-nav">
                <div class="flex items-center">
                    <a href="{{ route('user.index') }}" class="user-logo" style="border: 2px solid white; padding: 0.1rem 0.5rem 0.1rem 0.5rem; border-radius: 50px;">
                        DUT Vote
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="mobile-menu-button">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>

                <!-- Desktop navigation -->
                <div class="user-nav-links hidden md:flex">
                    <a href="{{ route('user.index') }}"
                       class="user-nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        Ana Sayfa
                    </a>
                    <a href="{{ route('user.projects') }}"
                       class="user-nav-link {{ request()->routeIs('user.projects') ? 'active' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                        </svg>
                        Projeler
                    </a>

                    @auth
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <span class="text-sm text-white/90">Merhaba, {{ Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('user.logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="user-nav-link logout-btn" style="background: none; border: none; cursor: pointer; display: flex; align-items: center;">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                                    </svg>
                                    Çıkış
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="auth-buttons-container">
                            <a href="{{ route('user.login') }}" class="auth-btn auth-btn-login">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                                </svg>
                                Giriş
                            </a>
                            <a href="{{ route('user.register') }}" class="auth-btn auth-btn-register">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                                </svg>
                                Kayıt
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile navigation menu -->
                <div id="mobile-menu" class="mobile-menu">
                    <div class="mobile-menu-content">
                        <a href="{{ route('user.index') }}"
                           class="mobile-nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                            </svg>
                            Ana Sayfa
                        </a>
                        <a href="{{ route('user.projects') }}"
                           class="mobile-nav-link {{ request()->routeIs('user.projects') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                            </svg>
                            Projeler
                        </a>

                        @auth
                            <div class="mobile-auth-section">
                                <div class="mobile-user-info">
                                    <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 1-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <span class="text-sm text-white/90">Merhaba, {{ Auth::user()->name }}</span>
                                </div>
                                <form method="POST" action="{{ route('user.logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="mobile-nav-link logout-btn w-full text-left">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                                        </svg>
                                        Çıkış
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mobile-auth-buttons">
                                <a href="{{ route('user.login') }}" class="mobile-auth-btn mobile-auth-btn-login">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                                    </svg>
                                    Giriş
                                </a>
                                <a href="{{ route('user.register') }}" class="mobile-auth-btn mobile-auth-btn-register">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                                    </svg>
                                    Kayıt
                                </a>
                            </div>
                        @endauth
                    </div>
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
                <div class="footer-main">
                    <div class="footer-brand">
                        <div class="footer-logo" style="border: 1px solid rgba(255, 255, 255, 0.3); padding: 0.1rem 0.5rem 0.1rem 0.5rem; border-radius: 0.375rem; display: inline-block;">
                            DUT Vote
                        </div>
                        <p class="footer-description">
                            Şehrimizi birlikte dönüştürmek için oluşturulmuş katılımcı demokrasi platformu
                        </p>
                    </div>

                    <div class="footer-links">
                        <div class="footer-section">
                            <h4>
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                Platform
                            </h4>
                            <ul>
                                <li>
                                    <a href="{{ route('user.index') }}">
                                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                        </svg>
                                        Ana Sayfa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.projects') }}">
                                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                        </svg>
                                        Projeler
                                    </a>
                                </li>
                                <li>
                                    <a href="#hakkinda">
                                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                        </svg>
                                        Hakkında
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="footer-section">
                            <h4>
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                                İletişim
                            </h4>
                            <ul>
                                <li>
                                    <a href="mailto:info@visiosoft.com.tr">
                                        <svg class="icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                        </svg>
                                        info@visiosoft.com.tr
                                    </a>
                                </li>
                                <li>
                                    <a href="https://visiosoft.com.tr" target="_blank">
                                        <svg class="icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                                        </svg>
                                        visiosoft.com.tr
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="footer-section">
                            <h4>
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
                                </svg>
                                Sosyal Medya
                            </h4>
                            <div class="social-links">
                                <a href="#" title="LinkedIn">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                                <a href="#" title="Twitter">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>
                                <a href="#" title="Instagram">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C8.396 0 7.929.01 7.075.048 6.223.085 5.65.204 5.14.388a6.578 6.578 0 0 0-2.37 1.543A6.578 6.578 0 0 0 .227 4.301C.043 4.812-.076 5.385-.113 6.237-.151 7.091-.161 7.558-.161 11.179c0 3.621.01 4.088.048 4.942.037.852.156 1.425.34 1.935a6.578 6.578 0 0 0 1.543 2.37 6.578 6.578 0 0 0 2.37 1.543c.51.184 1.083.303 1.935.34.854.038 1.321.048 4.942.048 3.621 0 4.088-.01 4.942-.048.852-.037 1.425-.156 1.935-.34a6.578 6.578 0 0 0 2.37-1.543 6.578 6.578 0 0 0 1.543-2.37c.184-.51.303-1.083.34-1.935.038-.854.048-1.321.048-4.942 0-3.621-.01-4.088-.048-4.942-.037-.852-.156-1.425-.34-1.935A6.578 6.578 0 0 0 19.69 1.931 6.578 6.578 0 0 0 17.32.388C16.81.204 16.237.085 15.385.048 14.531.01 14.064 0 10.443 0h1.574zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12.017 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm7.846-10.405a1.441 1.441 0 0 1-2.88 0 1.441 1.441 0 0 1 2.88 0z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="footer-credits">
                        <p>© 2025 DUT Vote. Tüm hakları saklıdır.</p>
                        <p class="developed-by">
                            <svg class="icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                            </svg>
                            <a href="https://visiosoft.com.tr" target="_blank">VisioSoft</a> tarafından geliştirildi
                        </p>
                    </div>
                </div>
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

        // Mobile Menu Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            let isMenuOpen = false;

            function toggleMenu() {
                isMenuOpen = !isMenuOpen;

                if (isMenuOpen) {
                    mobileMenuButton.classList.add('active');
                    mobileMenu.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileMenuButton.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }

            function closeMenu() {
                if (isMenuOpen) {
                    isMenuOpen = false;
                    mobileMenuButton.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }

            // Toggle menu on button click
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', toggleMenu);
            }

            // Close menu when clicking on mobile nav links
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Only close for navigation links, not the logout button
                    if (this.tagName === 'A') {
                        closeMenu();
                    }
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideMenu = mobileMenu && mobileMenu.contains(event.target);
                const isClickOnButton = mobileMenuButton && mobileMenuButton.contains(event.target);

                if (!isClickInsideMenu && !isClickOnButton && isMenuOpen) {
                    closeMenu();
                }
            });

            // Close menu on window resize to desktop size
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && isMenuOpen) {
                    closeMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && isMenuOpen) {
                    closeMenu();
                }
            });
        });
    </script>
</body>
</html>
