@php
    // Eski formatı (project_designs tablosu) veya yeni formatı (projects.design_landscape) destekle
    $designData = null;
    $project = $getRecord()->project ?? null;
    
    // Önce projects.design_landscape alanını kontrol et (yeni format)
    if ($project && isset($project->design_landscape) && $project->design_landscape) {
        $designData = $project->design_landscape;
    } elseif ($getRecord()->design_data) {
        // Geriye dönük uyumluluk için project_designs tablosunu kontrol et (eski format)
        $designData = $getRecord()->design_data;
    }
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
    @if($designData && isset($designData['elements']) && count($designData['elements']) > 0)
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tasarım Detayları</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Element Sayısı:</span>
                    <span class="text-gray-900">{{ count($designData['elements']) }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Son Güncelleme:</span>
                    <span class="text-gray-900">{{ isset($designData['timestamp']) ? \Carbon\Carbon::parse($designData['timestamp'])->format('d.m.Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <h4 class="text-md font-medium text-gray-800 mb-2">Elementler:</h4>
            <div class="max-h-40 overflow-y-auto space-y-2">
                @foreach($designData['elements'] as $index => $element)
                    <div class="bg-gray-50 rounded p-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Element {{ $index + 1 }}</span>
                            @if(isset($element['obje_id']))
                                @php
                                    $obje = \App\Models\Obje::find($element['obje_id']);
                                @endphp
                                @if($obje)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ $obje->isim }}</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">Obje #{{ $element['obje_id'] }}</span>
                                @endif
                            @elseif(isset($element['type']))
                                {{-- Eski format desteği --}}
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $element['type'] }}</span>
                            @endif
                        </div>
                        
                        {{-- Yeni format: location ve scale --}}
                        @if(isset($element['location']))
                            <div class="text-gray-600 mt-1">
                                Pozisyon: ({{ round($element['location']['x']) }}, {{ round($element['location']['y']) }})
                                @if(isset($element['scale']))
                                    | Ölçek: {{ round($element['scale']['x'], 2) }}x{{ round($element['scale']['y'], 2) }}
                                @endif
                            </div>
                        @elseif(isset($element['x']) && isset($element['y']))
                            {{-- Eski format desteği --}}
                            <div class="text-gray-600 mt-1">
                                Pozisyon: ({{ $element['x'] }}, {{ $element['y'] }})
                                @if(isset($element['width']) && isset($element['height']))
                                    | Boyut: {{ $element['width'] }}x{{ $element['height'] }}
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-t border-gray-200">
            <a href="/admin/drag-drop-test?project_id={{ $project?->id }}&image={{ urlencode($project?->hasMedia('images') ? $project->getFirstMediaUrl('images') : '') }}" 
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ __('filament.resources.suggestion.actions.view_design') }}
            </a>
        </div>
    @else
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            <p class="text-gray-500">Henüz tasarım oluşturulmamış</p>
        </div>
    @endif
</div>
