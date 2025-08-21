<![CDATA[<div class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 transition-colors cursor-pointer">
    <div class="text-gray-500 dark:text-gray-400 mb-2">
        @if (str_contains($icon, 'heroicon-'))
            @svg($icon, 'w-8 h-8')
        @else
            <img src="{{ $icon }}" alt="{{ $name }}" class="w-8 h-8 object-contain">
        @endif
    </div>
    <div class="text-sm font-medium text-center text-gray-900 dark:text-gray-100">
        {{ $name }}
    </div>
</div>]]>
