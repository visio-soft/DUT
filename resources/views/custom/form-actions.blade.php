<div class="mt-6 flex flex-wrap gap-3 justify-center items-center w-full">
    @php
        $currentRoute = request()->route()->getName();
        $isCreatePage = str_contains($currentRoute, '.create');
        $isEditPage = str_contains($currentRoute, '.edit');
        
        // Resource tipini belirle
        $resourceType = 'general';
        if (str_contains($currentRoute, 'projects')) {
            $resourceType = 'projects';
        } elseif (str_contains($currentRoute, 'categories')) {
            $resourceType = 'categories';
        } elseif (str_contains($currentRoute, 'objes')) {
            $resourceType = 'objes';
        }
        
        // Index route'unu belirle
        $indexRoute = match($resourceType) {
            'projects' => 'filament.admin.resources.projects.index',
            'categories' => 'filament.admin.resources.categories.index',
            'objes' => 'filament.admin.resources.objes.index',
            default => 'filament.admin.pages.dashboard'
        };
        
        // Wire click methodlarını belirle
        $createMethod = match($resourceType) {
            'projects' => 'createProject',
            'categories' => 'create',
            'objes' => 'create',
            default => 'create'
        };
        
        $updateMethod = match($resourceType) {
            'projects' => 'updateProject',
            'categories' => 'save',
            'objes' => 'save',
            default => 'save'
        };
    @endphp

    @if($isCreatePage)
        {{-- Create sayfası butonları --}}
        <button 
            type="button"
            wire:click="{{ $createMethod }}"
            class="fi-btn fi-btn-size-md fi-color-primary fi-btn-color-primary fi-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-filled gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);">
            <svg class="fi-btn-icon transition duration-75 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path>
            </svg>
            <span class="fi-btn-label">
                Oluştur
            </span>
        </button>

        @if($resourceType === 'projects')
            <button 
                type="button"
                wire:click="createProjectAnother"
                class="fi-btn fi-btn-size-md fi-color-success fi-btn-color-success fi-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-filled gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-green-600 text-white hover:bg-green-500 focus-visible:ring-green-500/50 dark:bg-green-500 dark:hover:bg-green-400 dark:focus-visible:ring-green-400/50 fi-ac-action fi-ac-btn-action">
                <svg class="fi-btn-icon transition duration-75 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"></path>
                </svg>
                <span class="fi-btn-label">
                    Oluştur ve Yeni
                </span>
            </button>
        @endif

    @elseif($isEditPage)
        {{-- Edit sayfası butonları --}}
        <button 
            type="button"
            wire:click="{{ $updateMethod }}"
            class="fi-btn fi-btn-size-md fi-color-primary fi-btn-color-primary fi-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-filled gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);">
            <svg class="fi-btn-icon transition duration-75 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"></path>
            </svg>
            <span class="fi-btn-label">
                Güncelle
            </span>
        </button>
    @endif

    <a 
        href="{{ route($indexRoute) }}"
        class="fi-btn fi-btn-size-md fi-color-gray fi-btn-color-gray fi-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-outlined gap-1.5 px-3 py-2 text-sm inline-grid border border-gray-300 text-gray-950 hover:bg-gray-50 focus-visible:ring-gray-500/50 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:focus-visible:ring-gray-400/50 fi-ac-action fi-ac-btn-action">
        <svg class="fi-btn-icon transition duration-75 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"></path>
        </svg>
        <span class="fi-btn-label">
            İptal
        </span>
    </a>
</div>

