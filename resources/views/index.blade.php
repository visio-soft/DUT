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
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900">

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Hero Section -->
        <div class="bg-white shadow dark:bg-gray-800">
            <div class="px-4 py-24 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white sm:text-6xl">
                        DUT Tasarım Platformu
                    </h1>
                    <p class="max-w-2xl mx-auto mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                        Laravel ve Filament ile güçlendirilmiş modern tasarım yönetim platformuna hoş geldiniz.
                        Projelerinizi organize edin, ekibinizle işbirliği yapın.
                    </p>
                    <div class="flex items-center justify-center mt-10 gap-x-6">
                        <a href="/admin"
                           class="rounded-md bg-orange-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                            Yönetim Paneli
                        </a>
                        <a href="#features" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">
                            Özellikleri Keşfet <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-24 sm:py-32">
            <div class="px-6 mx-auto max-w-7xl lg:px-8">
                <div class="max-w-2xl mx-auto lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-orange-600">Platform Özellikleri</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        İhtiyacınız olan her şey burada
                    </p>
                </div>
                <div class="max-w-2xl mx-auto mt-16 sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">

                        <!-- Feature 1 -->
                        <div class="flex flex-col">
                            <dt class="flex items-center text-base font-semibold leading-7 text-gray-900 gap-x-3 dark:text-white">
                                <div class="flex items-center justify-center w-10 h-10 bg-orange-600 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                Proje Yönetimi
                            </dt>
                            <dd class="flex flex-col flex-auto mt-4 text-base leading-7 text-gray-600 dark:text-gray-300">
                                <p class="flex-auto">Tasarım projelerinizi organize edin, ilerlemeyi takip edin ve ekibinizle kolayca paylaşın.</p>
                            </dd>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex flex-col">
                            <dt class="flex items-center text-base font-semibold leading-7 text-gray-900 gap-x-3 dark:text-white">
                                <div class="flex items-center justify-center w-10 h-10 bg-orange-600 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                                Medya Galerisi
                            </dt>
                            <dd class="flex flex-col flex-auto mt-4 text-base leading-7 text-gray-600 dark:text-gray-300">
                                <p class="flex-auto">Tasarım dosyalarınızı güvenli bir şekilde depolayın ve kategorilere ayırın.</p>
                            </dd>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex flex-col">
                            <dt class="flex items-center text-base font-semibold leading-7 text-gray-900 gap-x-3 dark:text-white">
                                <div class="flex items-center justify-center w-10 h-10 bg-orange-600 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                </div>
                                Takım Çalışması
                            </dt>
                            <dd class="flex flex-col flex-auto mt-4 text-base leading-7 text-gray-600 dark:text-gray-300">
                                <p class="flex-auto">Ekip üyelerinizle gerçek zamanlı olarak çalışın ve geri bildirim alın.</p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="py-24 bg-white dark:bg-gray-800 sm:py-32">
            <div class="px-6 mx-auto max-w-7xl lg:px-8">
                <dl class="grid grid-cols-1 text-center gap-x-8 gap-y-16 lg:grid-cols-3">
                    <div class="flex flex-col max-w-xs mx-auto gap-y-4">
                        <dt class="text-base leading-7 text-gray-600 dark:text-gray-300">Tamamlanan Proje</dt>
                        <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-5xl">100+</dd>
                    </div>
                    <div class="flex flex-col max-w-xs mx-auto gap-y-4">
                        <dt class="text-base leading-7 text-gray-600 dark:text-gray-300">Aktif Kullanıcı</dt>
                        <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-5xl">50+</dd>
                    </div>
                    <div class="flex flex-col max-w-xs mx-auto gap-y-4">
                        <dt class="text-base leading-7 text-gray-600 dark:text-gray-300">Müşteri Memnuniyeti</dt>
                        <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-5xl">99%</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-orange-600">
            <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
                <div class="max-w-2xl mx-auto text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Hemen Başlayın
                    </h2>
                    <p class="max-w-xl mx-auto mt-6 text-lg leading-8 text-orange-100">
                        Tasarım projelerinizi yönetmek için gereken her şey burada. Platform keşfetmeye başlayın.
                    </p>
                    <div class="flex items-center justify-center mt-10 gap-x-6">
                        <a href="/admin" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-orange-600 shadow-sm hover:bg-orange-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                            Platformu Keşfedin
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
