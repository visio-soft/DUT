<div id="filament-viewer-root">
<x-filament-panels::page>
    <div>
        <style>
            body {
                font-family: inherit;
                background-color: transparent;
                padding: 0;
                color: inherit;
                margin: 0;
            }

            :root {
                --fd-bg: var(--filament-background, #f8fafc);
                --fd-panel-bg: var(--filament-panel, #ffffff);
                --fd-border: var(--filament-border, #e5e7eb);
                --fd-text: var(--filament-text, #374151);
                --fd-accent: var(--filament-primary, #10b981);
                --fd-accent-2: var(--filament-accent, #64614eff);
                --fd-muted-white: rgba(255,255,255,0.95);
                --fd-bg-image-opacity: 1;
                --fd-element-bg: rgba(0, 0, 0, 0);
                --fd-name-bg: rgba(255,255,255,0.85);
                --fd-name-text: #333333;
                --fd-danger: var(--filament-danger, #dc2626);
                --fd-danger-bg: var(--filament-danger-100, #fee2e2);
                --fd-info: var(--filament-info, #0ea5e9);
            }

            .dark {
                --fd-bg: #0b1220;
                --fd-panel-bg: #0f1724;
                --fd-border: #1f2937;
                --fd-text: #e6eef6;
                --fd-accent: #34d399;
                --fd-accent-2: #60a5fa;
                --fd-muted-white: rgba(255,255,255,0.03);
                --fd-bg-image-opacity: 1;
                --fd-element-bg: rgba(255,255,255,0.03);
                --fd-name-bg: rgba(0,0,0,0.6);
                --fd-name-text: var(--fd-text);
                --fd-danger: #f87171;
                --fd-danger-bg: rgba(248,113,113,0.06);
                --fd-info: #38bdf8;
            }

            .landscape-designer-wrapper {
                width: 100%;
                max-width: 1400px;
                margin: auto;
            }

            .landscape-studio {
                display: flex;
                gap: 20px;
                height: 80vh;
            }

            .element-info {
                width: 300px;
                background: var(--fd-panel-bg);
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
                border: 1px solid var(--fd-border);
                overflow-y: auto;
            }

            .design-area {
                flex: 1;
                border-radius: 12px;
                position: relative;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
                overflow: hidden;
                background: var(--fd-panel-bg);
            }

            .property-boundary {
                position: absolute;
                top: 50px;
                left: 50px;
                right: 50px;
                bottom: 50px;
                border: 4px dashed var(--fd-accent);
                border-radius: 16px;
                overflow: hidden;
            }

            .background-image-container {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 2;
            }

            .background-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: var(--fd-bg-image-opacity, 1);
                border-radius: 12px;
                filter: none;
                -webkit-filter: none;
            }

            .boundary-label {
                position: absolute;
                top: 10px;
                left: 20px;
                background: rgba(255, 255, 255, 0.9);
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                color: var(--fd-text);
                z-index: 10;
                border: 2px solid var(--fd-accent);
            }

            .design-element {
                position: absolute;
                z-index: 20;
                pointer-events: none;
            }

            .design-element img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            }

            .element-badge {
                position: absolute;
                top: -8px;
                left: 50%;
                transform: translateX(-50%);
                background: var(--fd-name-bg);
                color: var(--fd-name-text);
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                border: 1px solid var(--fd-border);
            }

            .info-section {
                margin-bottom: 20px;
                padding: 15px;
                background: var(--fd-bg);
                border-radius: 8px;
                border: 1px solid var(--fd-border);
            }

            .info-section h3 {
                margin: 0 0 10px 0;
                color: var(--fd-text);
                font-size: 16px;
                font-weight: 600;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                margin: 8px 0;
                color: var(--fd-text);
                font-size: 14px;
            }

            .info-label {
                font-weight: 500;
                color: color-mix(in srgb, var(--fd-text) 70%, transparent);
            }

            .info-value {
                font-weight: 600;
                color: var(--fd-text);
            }

            .element-list {
                max-height: 300px;
                overflow-y: auto;
            }

            .element-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px;
                margin: 4px 0;
                background: var(--fd-panel-bg);
                border-radius: 6px;
                border: 1px solid var(--fd-border);
                font-size: 12px;
            }

            .element-item img {
                width: 30px;
                height: 30px;
                border-radius: 4px;
                object-fit: cover;
            }

            .element-item-info {
                flex: 1;
            }

            .element-item-name {
                font-weight: 600;
                color: var(--fd-text);
            }

            .element-item-pos {
                color: color-mix(in srgb, var(--fd-text) 60%, transparent);
                font-size: 11px;
            }

            .viewer-badge {
                position: absolute;
                top: 20px;
                right: 20px;
                background: var(--fd-info);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                z-index: 10;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            }
        </style>

        <div class="landscape-designer-wrapper">
            <div class="landscape-studio">
                <!-- Sol Panel - Bilgi Paneli -->
                <div class="element-info">
                    <div class="info-section">
                        <h3>📊 Tasarım Bilgileri</h3>
                        @if($existing_design && isset($existing_design['elements']))
                            <div class="info-item">
                                <span class="info-label">Element Sayısı:</span>
                                <span class="info-value">{{ count($existing_design['elements']) }}</span>
                            </div>
                            @if(isset($existing_design['timestamp']))
                                <div class="info-item">
                                    <span class="info-label">Oluşturulma:</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($existing_design['timestamp'])->format('d.m.Y H:i') }}</span>
                                </div>
                            @endif
                        @endif
                        <div class="info-item">
                            <span class="info-label">Proje:</span>
                            <span class="info-value">{{ $project->title }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Konum:</span>
                            <span class="info-value">{{ $project->district }}, {{ $project->neighborhood }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Bütçe:</span>
                            <span class="info-value">₺{{ number_format($project->budget, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($existing_design && isset($existing_design['elements']) && count($existing_design['elements']) > 0)
                        <div class="info-section">
                            <h3>🌿 Kullanılan Elementler</h3>
                            <div class="element-list">
                                @foreach($existing_design['elements'] as $index => $element)
                                    @php
                                        $obje = $objeler->firstWhere('id', $element['obje_id'] ?? null);
                                    @endphp
                                    <div class="element-item">
                                        @if($obje && $obje['image_url'])
                                            <img src="{{ $obje['image_url'] }}" alt="{{ $obje['isim'] }}">
                                        @else
                                            <div style="width: 30px; height: 30px; background: var(--fd-accent); border-radius: 4px;"></div>
                                        @endif
                                        <div class="element-item-info">
                                            <div class="element-item-name">
                                                {{ $obje['isim'] ?? 'Element ' . ($index + 1) }}
                                            </div>
                                            @if(isset($element['x']) && isset($element['y']))
                                                <div class="element-item-pos">
                                                    ({{ round($element['x']) }}, {{ round($element['y']) }})
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Ana Tasarım Alanı -->
                <div class="design-area">
                    <div class="viewer-badge">
                        👁️ Görüntüleme Modu
                    </div>

                    <!-- Proje sınırları -->
                    <div class="property-boundary">
                        <div class="boundary-label">
                            🏡 {{ $project->title }} - Proje Alanı
                        </div>

                        <!-- Arka plan resmi -->
                        @if($project_image)
                            <div class="background-image-container">
                                <img src="{{ $project_image }}" alt="Proje Görseli" class="background-image">
                            </div>
                        @endif

                        <!-- Yerleştirilmiş tasarım elementleri -->
                        @if($existing_design && isset($existing_design['elements']))
                            @foreach($existing_design['elements'] as $index => $element)
                                @if(isset($element['x']) && isset($element['y']))
                                    @php
                                        $obje = $objeler->firstWhere('id', $element['obje_id'] ?? null);
                                        $width = $element['width'] ?? 50;
                                        $height = $element['height'] ?? 50;
                                        $x = $element['x'] ?? 0;
                                        $y = $element['y'] ?? 0;
                                    @endphp
                                    <div class="design-element" 
                                         style="left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px;">
                                        @if($obje && $obje['image_url'])
                                            <img src="{{ $obje['image_url'] }}" alt="{{ $obje['isim'] }}">
                                        @elseif(isset($element['src']))
                                            <img src="{{ $element['src'] }}" alt="Design Element">
                                        @else
                                            <div style="width: 100%; height: 100%; background: var(--fd-accent); border-radius: 6px;"></div>
                                        @endif
                                        
                                        <div class="element-badge">
                                            {{ $obje['isim'] ?? ('Element ' . ($index + 1)) }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(!$existing_design || !isset($existing_design['elements']) || count($existing_design['elements']) === 0)
            <div style="text-align: center; padding: 40px; color: var(--fd-text);">
                <div style="font-size: 48px; margin-bottom: 16px;">🎨</div>
                <h3 style="margin: 0 0 8px 0; color: var(--fd-text);">Tasarım Bulunamadı</h3>
                <p style="margin: 0; color: color-mix(in srgb, var(--fd-text) 70%, transparent);">
                    Bu proje için henüz tasarım oluşturulmamış.
                </p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
</div>
