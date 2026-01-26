<div class="flex items-center gap-4">
    <!-- Site Link -->
    <a href="{{ url('/') }}" target="_blank"
        class="flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
        </svg>
        <span class="hidden lg:block">Siteye Git</span>
    </a>

    <!-- Language Selector -->
    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open" type="button"
            class="relative p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 group text-gray-700 dark:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path>
            </svg>
            <span
                class="absolute bottom-1 right-0 text-[10px] font-bold bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 group-hover:text-emerald-600 rounded-full px-1 py-0.5 border border-gray-200 dark:border-gray-600 shadow-sm leading-none transition-colors duration-200">
                {{ strtoupper(app()->getLocale()) }}
            </span>
        </button>

        <div x-show="open" x-transition
            class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden"
            style="display: none;">
            <div class="py-1">
                @foreach (['tr' => 'Türkçe', 'en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch', 'sv' => 'Svenska'] as $code => $label)
                    <a href="{{ route('language.switch', $code) }}"
                        class="flex items-center px-4 py-2 text-sm transition-colors text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() == $code ? 'bg-gray-50 dark:bg-gray-700 font-semibold text-emerald-600 dark:text-emerald-400' : '' }}">
                        <svg class="w-5 h-5 mr-3 {{ app()->getLocale() == $code ? 'text-emerald-600 dark:text-emerald-400' : 'text-transparent' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>