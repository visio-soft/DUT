<div x-data="{ open: false }" class="relative inline-block text-left language-selector" @click.outside="open = false" @keydown.escape.window="open = false">
    <button @click="open = !open" type="button" class="relative p-2 text-[#053640] hover:text-[#1ABF6B] transition-colors duration-200 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
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
        </div>
    </div>
</div>
