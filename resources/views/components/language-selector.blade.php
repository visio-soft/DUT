<div x-data="{ open: false }" class="relative inline-block text-left language-selector" @click.outside="open = false" @keydown.escape.window="open = false">
    <button @click="open = !open" type="button" class="relative p-2 text-[#053640] hover:text-[#1ABF6B] transition-colors duration-200 focus:outline-none group">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path>
        </svg>
        <span class="absolute bottom-1 right-0 text-[10px] font-bold bg-white text-[#053640] group-hover:text-[#1ABF6B] rounded-full px-1 py-0.5 border border-gray-100 shadow-sm leading-none transition-colors duration-200">
            {{ strtoupper(app()->getLocale()) }}
        </span>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute left-0 md:left-auto md:right-0 top-full z-50 w-48 mt-2 origin-top-left md:origin-top-right bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-100"
         style="display: none;">
        <div class="py-1">
            <a href="{{ route('language.switch', 'tr') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-[#f0f9f4] hover:text-[#1ABF6B] transition-colors {{ app()->getLocale() == 'tr' ? 'bg-[#f0f9f4] text-[#1ABF6B] font-semibold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() == 'tr')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @endif
                </svg>
                Türkçe
            </a>
            <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-[#f0f9f4] hover:text-[#1ABF6B] transition-colors {{ app()->getLocale() == 'en' ? 'bg-[#f0f9f4] text-[#1ABF6B] font-semibold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() == 'en')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @endif
                </svg>
                English
            </a>
            <a href="{{ route('language.switch', 'fr') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-[#f0f9f4] hover:text-[#1ABF6B] transition-colors {{ app()->getLocale() == 'fr' ? 'bg-[#f0f9f4] text-[#1ABF6B] font-semibold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() == 'fr')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @endif
                </svg>
                Français
            </a>
            <a href="{{ route('language.switch', 'de') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-[#f0f9f4] hover:text-[#1ABF6B] transition-colors {{ app()->getLocale() == 'de' ? 'bg-[#f0f9f4] text-[#1ABF6B] font-semibold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() == 'de')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @endif
                </svg>
                Deutsch
            </a>
            <a href="{{ route('language.switch', 'sv') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-[#f0f9f4] hover:text-[#1ABF6B] transition-colors {{ app()->getLocale() == 'sv' ? 'bg-[#f0f9f4] text-[#1ABF6B] font-semibold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() == 'sv')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @endif
                </svg>
                Svenska
            </a>
        </div>
    </div>
</div>
