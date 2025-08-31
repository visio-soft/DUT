<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>DUT Sayfa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        /* Filament-like theme variables with sensible defaults */
        :root{
            --filament-background: #f8fafc;
            --filament-panel: #ffffff;
            --filament-border: #e5e7eb;
            --filament-text: #374151;
            --filament-primary: #10b981;
            --filament-accent: #64614eff;
        }
        .dark{
            --filament-background: #0b1220;
            --filament-panel: #0f1724;
            --filament-border: #1f2937;
            --filament-text: #e6eef6;
            --filament-primary: #10b981;
            --filament-accent: #10b981;
        }

        /* Utility to use variables in this layout */
        .bg-panel{ background-color: var(--filament-panel); }
        .text-panel{ color: var(--filament-text); }
        .border-panel{ border-color: var(--filament-border); }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-panel text-panel">
<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/" class="text-lg font-bold">DUT</a>
        </div>
        <div class="flex items-center gap-4">
            <nav class="space-x-4 hidden sm:inline">
                <a href="/dut" class="text-sm">Home</a>
                <a href="/signin" class="text-sm">Sign in</a>
                <a href="/signup" class="text-sm">Sign up</a>
            </nav>
            <button id="themeToggle" class="px-3 py-1 border rounded text-sm border-panel">Karanlık/Açık</button>
        </div>
    </div>
</header>

<main class="flex-1 container mx-auto px-4 py-8">
    @yield('content')
</main>

<footer class="bg-white border-t">
    <div class="container mx-auto px-4 py-6 text-sm text-gray-600">
        <div class="flex items-center justify-between">
            <div>© {{ date('Y') }} DUT</div>
            <div>Adres · İletişim · Gizlilik</div>
        </div>
    </div>
</footer>
<script>
    // Theme toggle persisted in localStorage, mirrors Filament 'dark' class usage
    (function(){
        const key = 'dut-theme-dark';
        const btn = document.getElementById('themeToggle');
        function setDark(d){
            if(d) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
            localStorage.setItem(key, d ? '1' : '0');
        }
        const stored = localStorage.getItem(key);
        if(stored === '1') setDark(true);
        btn && btn.addEventListener('click', ()=> setDark(!document.documentElement.classList.contains('dark')));
    })();
</script>
</body>
</html>
