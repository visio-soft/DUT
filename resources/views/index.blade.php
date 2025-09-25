<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DUT - Anasayfa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        /* Custom responsive utilities */
        @media (max-width: 640px) {
            .hero-title {
                font-size: 2.5rem !important;
                line-height: 1.1 !important;
            }
            .hero-subtitle {
                font-size: 1rem !important;
                line-height: 1.5 !important;
            }
        }
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        /* Dark mode improvements */
        @media (prefers-color-scheme: dark) {
            html {
                color-scheme: dark;
            }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900 antialiased">

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-white via-orange-50 to-orange-100 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 shadow-lg">
            <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 sm:py-24 lg:px-8 lg:py-32">
                <div class="text-center">
                    <h1 class="hero-title text-3xl font-bold text-gray-900 dark:text-white sm:text-4xl lg:text-6xl">
                        DUT Tasarım Platformu
                    </h1>
                    <p class="hero-subtitle max-w-2xl mx-auto mt-4 sm:mt-6 text-base sm:text-lg leading-6 sm:leading-8 text-gray-600 dark:text-gray-300 px-4">
                        Laravel ve Filament ile güçlendirilmiş modern tasarım yönetim platformuna hoş geldiniz.
                        Projelerinizi organize edin, ekibinizle işbirliği yapın.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center mt-8 sm:mt-10 gap-4 sm:gap-6 px-4">
                        <a href="/admin"
                           class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 transition-all duration-200 hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            Yönetim Paneli
                        </a>
                        <a href="#features" class="inline-flex items-center text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                            Özellikleri Keşfet
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16 sm:py-24 lg:py-32 bg-white dark:bg-gray-800">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-sm font-semibold leading-7 text-orange-600 uppercase tracking-wide">Platform Özellikleri</h2>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl lg:text-4xl">
                        İhtiyacınız olan her şey burada
                    </p>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                        Modern ve kullanıcı dostu tasarımla projelerinizi yönetin
                    </p>
                </div>
                <div class="max-w-2xl mx-auto mt-12 sm:mt-16 lg:mt-20 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-8 lg:max-w-none lg:grid-cols-3 lg:gap-12">

                        <!-- Feature 1 -->
                        <div class="relative group">
                            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-2xl hover:bg-white dark:hover:bg-gray-600 transition-all duration-300 hover:shadow-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center mb-4">
                                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-4 text-xl font-semibold text-gray-900 dark:text-white">Proje Yönetimi</h3>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Tasarım projelerinizi organize edin, ilerlemeyi takip edin ve ekibinizle kolayca paylaşın.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="relative group">
                            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-2xl hover:bg-white dark:hover:bg-gray-600 transition-all duration-300 hover:shadow-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center mb-4">
                                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-4 text-xl font-semibold text-gray-900 dark:text-white">Medya Galerisi</h3>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Tasarım dosyalarınızı güvenli bir şekilde depolayın ve kategorilere ayırın.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="relative group">
                            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-2xl hover:bg-white dark:hover:bg-gray-600 transition-all duration-300 hover:shadow-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center mb-4">
                                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-4 text-xl font-semibold text-gray-900 dark:text-white">Takım Çalışması</h3>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Ekip üyelerinizle gerçek zamanlı olarak çalışın ve geri bildirim alın.
                                </p>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="py-16 sm:py-24 lg:py-32 bg-gradient-to-r from-gray-50 to-orange-50 dark:from-gray-700 dark:to-gray-800">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                        Rakamlarla DUT Platformu
                    </h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                        Güvenilir, kullanışlı ve etkili çözümler sunuyoruz
                    </p>
                </div>
                <dl class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-600">
                        <dt class="text-base font-medium text-gray-600 dark:text-gray-300 mb-2">Tamamlanan Proje</dt>
                        <dd class="text-4xl font-bold text-orange-600 dark:text-orange-400 lg:text-5xl">100+</dd>
                        <div class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +12% bu ay
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-600">
                        <dt class="text-base font-medium text-gray-600 dark:text-gray-300 mb-2">Aktif Kullanıcı</dt>
                        <dd class="text-4xl font-bold text-orange-600 dark:text-orange-400 lg:text-5xl">50+</dd>
                        <div class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +8% bu ay
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-600 sm:col-span-2 lg:col-span-1">
                        <dt class="text-base font-medium text-gray-600 dark:text-gray-300 mb-2">Müşteri Memnuniyeti</dt>
                        <dd class="text-4xl font-bold text-orange-600 dark:text-orange-400 lg:text-5xl">99%</dd>
                        <div class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Mükemmel puan
                        </div>
                    </div>
                </dl>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="relative bg-gradient-to-br from-orange-600 via-orange-700 to-orange-800 overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-600/20 to-transparent"></div>
                <svg class="absolute bottom-0 left-0 transform translate-y-1/2 -translate-x-1/2 lg:translate-x-0" width="404" height="404" fill="none" viewBox="0 0 404 404" aria-hidden="true">
                    <defs>
                        <pattern id="85737c0e-0916-41d7-917f-596dc7edfa27" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-orange-500" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="404" fill="url(#85737c0e-0916-41d7-917f-596dc7edfa27)" />
                </svg>
            </div>

            <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-32">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">
                        Hemen Başlayın
                    </h2>
                    <p class="max-w-2xl mx-auto mt-4 sm:mt-6 text-base sm:text-lg leading-6 sm:leading-8 text-orange-100 px-4">
                        Tasarım projelerinizi yönetmek için gereken her şey burada. Platform keşfetmeye başlayın ve ekibinizle daha verimli çalışın.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center mt-8 sm:mt-10 gap-4 sm:gap-6 px-4">
                        <a href="/admin" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-white px-8 py-3 text-base font-semibold text-orange-600 shadow-lg hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200 hover:shadow-xl hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Platformu Keşfedin
                        </a>
                        <a href="#features" class="inline-flex items-center text-base font-medium text-white hover:text-orange-200 transition-colors">
                            Daha Fazla Bilgi
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
