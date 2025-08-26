<div class="space-y-4">
    <!-- Proje Bilgileri -->
    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Proje Bilgileri</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">ID:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ $project->id }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Başlık:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ $project->title }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Kategori:</span>
                <span class="text-gray-900 dark:text-white ml-2">{{ $project->category->name ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Durum:</span>
                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ml-2
                    {{ $project->design_completed 
                        ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400' 
                        : 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400' 
                    }}">
                    {{ $project->design_completed ? 'Tamamlandı' : 'Devam Ediyor' }}
                </span>
            </div>
        </div>
    </div>

    <!-- JSON Verisi -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Tasarım Verisi (JSON)</h4>
            <div class="flex gap-2">
                <button 
                    type="button"
                    onclick="copyToClipboard()"
                    class="inline-flex items-center rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                    </svg>
                    Kopyala
                </button>
            </div>
        </div>
        
        @if(empty($designData))
            <div class="rounded-md bg-gray-50 p-4 dark:bg-gray-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tasarım verisi mevcut değil.</p>
            </div>
        @else
            <div class="rounded-md border border-gray-300 dark:border-gray-600">
                <pre id="json-content" class="max-h-96 overflow-auto p-4 text-xs text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900"><code>{{ json_encode($designData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        @endif
    </div>

    <!-- Element Özeti -->
    @if(!empty($designData) && is_array($designData))
        <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">Veri Özeti</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-blue-600 dark:text-blue-400">Toplam Anahtar:</span>
                    <span class="text-blue-900 dark:text-blue-100 ml-2">{{ count($designData) }}</span>
                </div>
                @if(isset($designData['elements']))
                    {{-- Yeni format: elements anahtarı --}}
                    <div>
                        <span class="font-medium text-blue-600 dark:text-blue-400">Elementler:</span>
                        <span class="text-blue-900 dark:text-blue-100 ml-2">{{ count($designData['elements']) }}</span>
                    </div>
                @elseif(isset($designData['objects']))
                    {{-- Eski format: objects anahtarı (geriye dönük uyumluluk) --}}
                    <div>
                        <span class="font-medium text-blue-600 dark:text-blue-400">Objeler:</span>
                        <span class="text-blue-900 dark:text-blue-100 ml-2">{{ count($designData['objects']) }}</span>
                    </div>
                @endif
                <div>
                    <span class="font-medium text-blue-600 dark:text-blue-400">Veri Boyutu:</span>
                    <span class="text-blue-900 dark:text-blue-100 ml-2">{{ round(strlen(json_encode($designData)) / 1024, 2) }} KB</span>
                </div>
                <div>
                    <span class="font-medium text-blue-600 dark:text-blue-400">Son Güncelleme:</span>
                    <span class="text-blue-900 dark:text-blue-100 ml-2">{{ $project->updated_at?->format('d.m.Y H:i') }}</span>
                </div>
            </div>
            
            {{-- Yeni format için detaylı element bilgileri --}}
            @if(isset($designData['elements']) && count($designData['elements']) > 0)
                <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-700">
                    <h5 class="text-xs font-medium text-blue-700 dark:text-blue-300 mb-2">Element Detayları (Yeni Format):</h5>
                    <div class="space-y-1 text-xs">
                        @foreach($designData['elements'] as $index => $element)
                            <div class="text-blue-600 dark:text-blue-400">
                                Element {{ $index + 1 }}: 
                                @if(isset($element['obje_id']))
                                    @php
                                        $obje = \App\Models\Obje::find($element['obje_id']);
                                    @endphp
                                    @if($obje)
                                        <span class="font-medium">{{ $obje->isim }}</span>
                                    @else
                                        <span>Obje #{{ $element['obje_id'] }}</span>
                                    @endif
                                @else
                                    <span>Bilinmeyen Element</span>
                                @endif
                                
                                @if(isset($element['scale']))
                                    | Ölçek: {{ $element['scale']['x'] ?? 1 }}x{{ $element['scale']['y'] ?? 1 }}
                                @endif
                                
                                @if(isset($element['location']))
                                    | Konum: ({{ $element['location']['x'] ?? 0 }}, {{ $element['location']['y'] ?? 0 }})
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
function copyToClipboard() {
    const jsonContent = document.getElementById('json-content');
    const text = jsonContent.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        // Success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = `
            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            Kopyalandı!
        `;
        button.classList.add('bg-green-600');
        button.classList.remove('bg-blue-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600');
        }, 2000);
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
