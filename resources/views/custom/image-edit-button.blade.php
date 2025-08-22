@php
    // Image yüklenip yüklenmediğini kontrol et
    $hasImage = false;
    
    if (isset($this->data['image_path']) && !empty($this->data['image_path'])) {
        $hasImage = true;
    }
@endphp

<div class="h-full min-h-[12rem] flex items-center justify-center mt-auto relative">
    <button
        type="button"
        wire:click="{{ $hasImage ? 'openImageEditor' : '$emit(\'noop\')' }}"
        {{ $hasImage ? '' : 'disabled' }}
        class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-outlined gap-1.5 px-3 py-2 text-sm inline-grid border
        {{ $hasImage 
            ? 'fi-color-primary fi-btn-color-primary border-primary-600 text-primary-600 hover:bg-primary-50 focus-visible:ring-primary-500/50 dark:border-primary-500 dark:text-primary-400 dark:hover:bg-primary-950/50 dark:focus-visible:ring-primary-400/50 shadow-sm hover:shadow-md transform hover:scale-105' 
            : 'fi-color-gray fi-btn-color-gray border-gray-300 text-gray-400 cursor-not-allowed opacity-50 dark:border-gray-600 dark:text-gray-500'
        }} fi-ac-action fi-ac-btn-action">
        <svg class="fi-btn-icon transition duration-75 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
        {{ $hasImage ? 'Düzenle' : 'Önce Resim Yükleyin' }}
    </button>
    
</div>

<!-- Modal for Image Editor -->
<div x-data="{ showModal: @entangle('showImageEditor') }" 
     x-show="showModal" 
     x-cloak
     class="fixed inset-0 z-50 bg-black bg-opacity-50"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <!-- popup page - TAM EKRAN -->
    <div @click.away="showModal = false" 
         class="absolute inset-4 bg-white dark:bg-gray-800 rounded-lg shadow-2xl overflow-hidden"
         style="width: calc(95vw - 2rem); height: calc(100vh - 2rem); top: 1rem; left: 3rem;"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Peyzaj Tasarım Stüdyosu
            </h3>
            <button @click="showModal = false" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Content - Iframe for drag_droptest page -->
        <div class="p-0 overflow-hidden" style="height: calc(100vh - 6rem);">
            @php
                $imageUrl = '';
                if ($hasImage && isset($this->data['image_path'])) {
                    if (is_array($this->data['image_path']) && !empty($this->data['image_path'])) {
                        $imageUrl = $this->data['image_path'][0] ?? '';
                    } else {
                        $imageUrl = $this->data['image_path'];
                    }
                }
            @endphp
            <iframe :src="'/admin/drag-droptest?image=' + encodeURIComponent('{{ $imageUrl }}')" 
                    class="w-full h-full border-0" 
                    frameborder="0"
                    style="width: 100%; height: 100%;">
            </iframe>
        </div>
    </div>
</div>

<style>
.fi-btn.fi-color-primary:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.103);
}
.fi-btn.fi-color-success {
    box-shadow: 0 2px 8px rgba(159, 255, 194, 0.2);
}
.fi-btn:disabled {
    pointer-events: none;
}
</style>
