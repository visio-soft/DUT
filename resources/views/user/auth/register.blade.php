@extends('user.layout')

@section('title', __('common.create_account') . ' - DUT Vote')

@section('content')
<div class="min-h-[calc(100vh-theme('spacing.16'))] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
        <!-- Header -->
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center gap-1.5 mb-2">
                <!-- DUT Logo -->
                <svg class="h-8 w-auto" viewBox="0 0 191 44" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#DutLogo_clip0_1357_1541)">
                        <path d="M77.4253 44H21.538C9.64333 44 0 34.1513 0 22C0 9.84867 9.64333 0 21.538 0H77.4253C89.32 0 98.9633 9.84867 98.9633 22C98.9633 34.1513 89.32 44 77.4253 44Z" fill="#1ABF6B"></path>
                        <path d="M30.7191 12.188C31.9878 12.7527 33.0951 13.508 34.0338 14.4687C34.9725 15.4294 35.7132 16.5587 36.2631 17.8567C36.8131 19.1547 37.0845 20.5334 37.0845 21.9927C37.0845 23.452 36.8131 24.8527 36.2631 26.136C35.7132 27.4267 34.9725 28.5487 34.0338 29.5094C33.0951 30.47 31.9878 31.2254 30.7191 31.79C29.4505 32.3474 28.1011 32.6334 26.6711 32.6334H18.3185V26.6347H26.1725C26.7958 26.6347 27.3898 26.5174 27.9325 26.2754C28.4825 26.0334 28.9665 25.7034 29.3845 25.278C29.8025 24.8527 30.1325 24.354 30.3598 23.782C30.5945 23.21 30.7118 22.6087 30.7118 21.9707C30.7118 21.3327 30.5945 20.7314 30.3598 20.1594C30.1251 19.5874 29.8025 19.096 29.3845 18.678C28.9665 18.26 28.4825 17.93 27.9325 17.6807C27.3825 17.4314 26.7958 17.3067 26.1725 17.3067H18.3185V11.3594H26.6711C28.0938 11.3594 29.4431 11.638 30.7191 12.1954" fill="#053640"></path>
                        <path d="M39.3643 21.9043V11.293H45.1869V21.3763C45.1869 22.0143 45.3043 22.623 45.5536 23.1876C45.7956 23.7596 46.1256 24.251 46.5289 24.6836C46.9396 25.109 47.4163 25.4463 47.9809 25.681C48.5383 25.923 49.1323 26.0403 49.7556 26.0403C50.3789 26.0403 50.9729 25.923 51.5303 25.681C52.0876 25.439 52.5716 25.109 52.9896 24.6836C53.4076 24.2583 53.7303 23.7596 53.9723 23.1876C54.2069 22.6156 54.3243 22.0143 54.3243 21.3763V11.293H60.1983V21.9043C60.1983 23.3783 59.9196 24.7643 59.3769 26.0476C58.8269 27.3383 58.0863 28.4603 57.1476 29.421C56.2089 30.3816 55.1016 31.137 53.8329 31.687C52.5643 32.237 51.2149 32.5156 49.7849 32.5156C48.3549 32.5156 46.9836 32.237 45.7296 31.687C44.4683 31.137 43.3683 30.3816 42.4296 29.421C41.4909 28.4603 40.7429 27.3383 40.1929 26.0476C39.6429 24.757 39.3716 23.3783 39.3716 21.9043" fill="#053640"></path>
                        <path d="M80.6442 11.4551V17.4537H74.4329V32.7071H68.5589V17.4537H62.4502V11.4551H80.6442Z" fill="#053640"></path>
                    </g>
                    <defs>
                        <clipPath id="DutLogo_clip0_1357_1541">
                            <rect width="191" height="44" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
                <h1 class="text-lg font-medium text-gray-500 uppercase tracking-widest">
                    {{ __('common.register') }}
                </h1>
            </div>
            <p class="mt-2 text-sm text-gray-400">
                {{ __('common.account_benefits') }}
            </p>
        </div>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                </svg>
                <div class="text-sm text-red-800">
                    <h3 class="font-semibold">{{ __('common.error') }}</h3>
                    <ul class="mt-1 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('user.register') }}" class="mt-8 space-y-6">
            @csrf

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        {{ __('common.name') }}
                    </label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" autocomplete="name" required
                            value="{{ old('name') }}"
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 sm:text-sm"
                            placeholder="Ad Soyad">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('common.email_address') }}
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email') }}"
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 sm:text-sm"
                            placeholder="ornek@email.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('common.password') }}
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        {{ __('common.password_confirmation') }}
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-green-500 group-hover:text-green-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </span>
                    {{ __('common.register') }}
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">
                        {{ __('common.already_have_account') }}
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('user.login') }}"
                    class="w-full flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('common.login') }}
                </a>
            </div>
        </div>

        
    </div>
</div>
@endsection
