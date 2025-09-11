<div class="language-switcher flex items-center gap-2">
    <form method="POST" action="{{ route('language.switch') }}" class="inline">
        @csrf
        <input type="hidden" name="locale" value="{{ app()->getLocale() === 'en' ? 'tr' : 'en' }}">
        <button type="submit" 
                class="flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 dark:hover:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 transition-colors">
            @if(app()->getLocale() === 'en')
                <span>🇹🇷</span>
                <span>TR</span>
            @else
                <span>🇺🇸</span>
                <span>EN</span>
            @endif
        </button>
    </form>
</div>