<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <button 
        @click="open = !open" 
        type="button"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800"
        style="color: rgb(var(--gray-700));"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span class="hidden sm:inline">{{ app()->getLocale() == 'tr' ? 'Türkçe' : 'English' }}</span>
        <span class="sm:hidden">{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>

    <div 
        x-show="open" 
        x-transition
        class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
        style="background-color: white;"
    >
        <div class="py-1">
            <a 
                href="{{ route('language.switch', 'tr') }}" 
                class="flex items-center px-4 py-2 text-sm transition-colors {{ app()->getLocale() == 'tr' ? 'bg-gray-100 dark:bg-gray-800 font-semibold' : '' }}"
                style="color: rgb(var(--gray-700));"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() == 'tr')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @endif
                </svg>
                Türkçe
            </a>
            <a 
                href="{{ route('language.switch', 'en') }}" 
                class="flex items-center px-4 py-2 text-sm transition-colors {{ app()->getLocale() == 'en' ? 'bg-gray-100 dark:bg-gray-800 font-semibold' : '' }}"
                style="color: rgb(var(--gray-700));"
            >
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
