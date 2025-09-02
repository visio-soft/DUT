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

<div class="text-center">
    @if($designData && isset($designData['elements']) && count($designData['elements']) > 0)
        <div class="inline-flex items-center space-x-2">
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            <span class="text-sm font-medium text-green-700">{{ count($designData['elements']) }} Element</span>
        </div>
        <div class="text-xs text-gray-500 mt-1">
            @if(isset($designData['timestamp']))
                {{ \Carbon\Carbon::parse($designData['timestamp'])->format('d.m.Y H:i') }}
            @endif
        </div>
    @else
        <div class="inline-flex items-center space-x-2">
            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
            <span class="text-sm text-gray-500">Tasarım Yok</span>
        </div>
    @endif
</div>
