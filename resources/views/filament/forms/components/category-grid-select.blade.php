<div class="w-full max-w-none">
    <input type="hidden" name="parent_category_id" id="parent_category_id" value="{{ old('parent_category_id', $getState()) }}">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full max-w-none">
        @foreach($categories as $category)
            <div class="category-block w-full cursor-pointer border-2 rounded-lg p-4 flex flex-col items-center transition-colors duration-200 relative @if($getState() == $category->id) border-primary-500 bg-primary-50 dark:bg-primary-900 selected-block @else border-gray-200 dark:border-gray-700 @endif"
                 data-category-id="{{ $category->id }}"
                 onclick="selectCategory(this, '{{ $category->id }}')">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-20" style="display: none;" id="tick-{{ $category->id }}">
                    <span class="inline-flex items-center justify-center rounded-full animate-bounce relative" style="">
                        <span class="absolute inset-0 rounded-full bg-yellow-400/20" style="box-shadow:0 0 24px 8px rgba(251,191,36,0.5);"></span>
                        <svg class="w-12 h-12 text-yellow-400 drop-shadow-lg relative z-10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
                <div class="text-gray-500 dark:text-gray-400 mb-2">
                    @if (str_contains($category->icon, 'heroicon-'))
                        @svg($category->icon, 'w-8 h-8')
                    @else
                        <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-8 h-8 object-contain">
                    @endif
                </div>
                <div class="text-sm font-medium text-center text-gray-900 dark:text-gray-100">
                    {{ $category->name }}
                </div>
            </div>
        @endforeach
    </div>
    <script>
    function selectCategory(el, id) {
        document.getElementById('parent_category_id').value = id;
        document.querySelectorAll('.category-block').forEach(e => {
            e.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900', 'selected-block');
            e.classList.add('border-gray-200', 'dark:border-gray-700');
            // Tik işaretini gizle
            let tick = e.querySelector('[id^="tick-"]');
            if (tick) tick.style.display = 'none';
        });
        el.classList.remove('border-gray-200', 'dark:border-gray-700');
        el.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900', 'selected-block');
        // Tik işaretini göster ve animasyon bitince gizle
        let tick = document.getElementById('tick-' + id);
        if (tick) {
            tick.style.display = 'flex';
            setTimeout(() => { tick.style.display = 'none'; }, 900);
        }
    }
    </script>
    @error('parent_category_id')
        <div class="text-danger-600 mt-2 text-xs">{{ $message }}</div>
    @enderror
</div>
