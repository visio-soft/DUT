<header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
    <div class="flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <span class="font-medium text-[13px] dark:text-[#EDEDEC] text-[#1b1b18]">DUT</span>
        </a>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                    >
                        {{ __('app.dashboard') }}
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                    >
                        {{ __('app.log_in') }}
                    </a>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            {{ __('app.register') }}
                        </a>
                    @endif
                @endauth
                
                <!-- Language Switcher -->
                <x-language-switcher />
            </nav>
        @endif
    </div>
</header>
