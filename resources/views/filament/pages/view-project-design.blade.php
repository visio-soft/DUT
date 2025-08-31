<div id="filament-designer-root">
<x-filament-panels::page>
    <div>
        <style>
            /* Base using Filament CSS variables (fall back to sensible defaults) */
            body { font-family: inherit; background-color: transparent; padding: 0; color: inherit; margin: 0; }
            :root {
                --fd-bg: var(--filament-background, #f8fafc);
                --fd-panel-bg: var(--filament-panel, #ffffff);
                --fd-border: var(--filament-border, #e5e7eb);
                --fd-text: var(--filament-text, #374151);
                --fd-accent: var(--filament-primary, #10b981);
                --fd-accent-2: var(--filament-accent, #64614eff);
                --fd-muted-white: rgba(255,255,255,0.95);
                /* opacity applied to the background image so we can control "wash" above it */
                --fd-bg-image-opacity: 1;
                /* element card background (keeps elements readable but avoids washing the whole image) */
                --fd-element-bg: rgba(0, 0, 0, 0);
                /* name badge background for element labels */
                --fd-name-bg: rgba(255,255,255,0.85);
                --fd-name-text: #ffffffff;
                --fd-danger: var(--filament-danger, #dc2626);
                --fd-danger-bg: var(--filament-danger-100, #fee2e2);
                --fd-info: var(--filament-info, #0ea5e9);
            }

            /* Dark mode variables when Filament toggles .dark on the document */
            .dark {
                --fd-bg: #0b1220;
                --fd-panel-bg: #0f1724;
                --fd-border: #1f2937;
                --fd-text: #e6eef6;
                --fd-accent: #10b981;
                --fd-accent-2: #10b981;
                --fd-muted-white: rgba(255,255,255,0.03);
                --fd-bg-image-opacity: 1;
                --fd-element-bg: rgba(255,255,255,0.03);
                --fd-name-bg: rgba(0,0,0,0.6);
                --fd-name-text: var(--fd-text);
                --fd-danger: #f87171;
                --fd-danger-bg: rgba(248,113,113,0.06);
                --fd-info: #38bdf8;
            }

            .landscape-designer-wrapper { width: 100%; max-width: 1400px; margin: auto; }
            .landscape-studio { display: flex; gap: 20px; height: 700px; min-height: 700px; max-height: 700px; width: 100%; max-width: 1200px; margin: 0 auto; }

            /* Left panel that matches Filament card look */
            .element-palette {
                width: 250px;
                background: var(--fd-panel-bg);
                border-radius: 12px;
                padding: 18px;
                box-shadow: 0 6px 18px -6px rgba(0,0,0,0.12);
                border: 1px solid var(--fd-border);
                overflow-y: auto;
            }

            .panel-title { margin:0 0 12px 0; font-weight:600; color:var(--fd-text); font-size:15px; }

            .palette-list-item { display:flex; gap:12px; align-items:center; padding:10px 6px; border-radius:8px; border-bottom:1px solid color-mix(in srgb, var(--fd-border) 75%, transparent); transition:all .15s ease; }
            .palette-list-item:hover { background: color-mix(in srgb, var(--fd-accent) 6%, var(--fd-panel-bg)); transform: translateY(-2px); }

            .palette-thumb { width:48px; height:48px; object-fit:cover; border-radius:6px; background: color-mix(in srgb, var(--fd-panel-bg) 90%, transparent); border:1px solid color-mix(in srgb, var(--fd-border) 60%, transparent); }
            .palette-meta { display:flex; flex-direction:column; }
            .palette-name { font-weight:600; color:var(--fd-text); font-size:14px; }
            .empty-hint { font-size:13px; color: color-mix(in srgb, var(--fd-text) 60%, transparent); margin-top:6px; }
            .palette-count { margin-left: auto; background: var(--fd-accent); color: white; font-weight:700; padding:2px 7px; border-radius:999px; font-size:10px; }

            .design-area {
                flex: 1;
                border-radius: 12px;
                position: relative;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
                overflow: hidden;
                width: 800px;
                height: 700px;
                min-width: 800px;
                min-height: 700px;
            }

            .property-boundary {
                position: absolute;
                top: 50px;
                left: 50px;
                right: 50px;
                bottom: 50px;
                /* Removed green dashed border: keep spacing and rounding but no visible border */
                border: none;
                border-radius: 16px;
                overflow: hidden;
            }

            /* Arka plan resmi için container */
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
                /* Use a variable so we can tweak per-theme without hunting through the file */
                opacity: var(--fd-bg-image-opacity, 1);
                border-radius: 12px;
                /* make sure no additional filter dims the image */
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
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                z-index: 25;
            }

            /* Yerleştirilen öğeler */
            .landscape-element {
                position: absolute;
                user-select: none;
                touch-action: none;
                border: 3px solid transparent;
                border-radius: 4px;
                transition: all 0.1s ease;
                z-index: 10;
                width: 120px;
                height: 120px;
            }

            .landscape-element:hover {
                border-color: var(--fd-accent-2);
                z-index: 15;
            }

            .landscape-element.selected {
                border-color: var(--fd-accent);
                z-index: 20;
            }

            .element-content {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                /* Make element container fully transparent so transparent images stay transparent */
                background: transparent !important;
                position: relative;
                border-radius: 6px;
                padding: 8px;
                /* remove shadow so images aren't visually backed by a drop shadow */
                box-shadow: none !important;
            }

            /* Overlay label on top of the image */
            .element-label {
                position: absolute;
                top: 6px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 12;
                pointer-events: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .element-name {
                color: inherit;
                /* remove heavy overlay and text shadow so underlying image remains visible */
                text-shadow: none !important;
                background: transparent !important;
                padding: 4px 8px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                max-width: 120px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .landscape-element img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 4px;
                /* ensure no default background or filter is applied to the image element */
                background: transparent !important;
                filter: none !important;
                -webkit-filter: none !important;
                box-shadow: none !important;
            }

            .empty-state { padding:12px; border-radius:8px; background: color-mix(in srgb, var(--fd-panel-bg) 85%, transparent); border:1px dashed var(--fd-border); }

            /* Accent gradient fallback that respects theme variables */
            .background-gradient {
                width:100%; height:100%; background: linear-gradient(135deg, var(--fd-accent) 0%, var(--fd-accent-2) 100%);
            }

            @media (max-width:1250px){
                .landscape-studio{ flex-direction:column; height:auto; }
                .element-palette{ width:100%; max-height:300px; }
                .design-area{ width:100%; height:500px; min-width:300px; min-height:500px; }
            }
        </style>

        <div class="landscape-designer-wrapper">
            <div class="landscape-studio">
                <div class="element-palette">
                    <h3 style="margin:0 0 12px 0; font-weight:600; color:var(--fd-text);">Tasarımdaki Objeler</h3>

                    @php
                        // Prepare elements list from saved design
                        $design = $existing_design ?? null;
                        $elements = [];
                        if ($design && isset($design['elements']) && is_iterable($design['elements'])) {
                            $elements = $design['elements'];
                        }

                        // helper to normalize image src robustly
                        $normalizeSrc = function ($src) {
                            if (!$src) return null;
                            $s = trim($src);
                            // quick accepts
                            if (\Illuminate\Support\Str::startsWith($s, ['http://', 'https://', '//', '/'])) {
                                return $s;
                            }

                            // try Storage URLs (public disk) when possible
                            try {
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($s)) {
                                    return \Illuminate\Support\Facades\Storage::url($s);
                                }
                                if (\Illuminate\Support\Facades\Storage::exists($s)) {
                                    return \Illuminate\Support\Facades\Storage::url($s);
                                }
                            } catch (\Throwable $e) {
                                // ignore and fallback
                            }

                            // common prefixes
                            if (\Illuminate\Support\Str::startsWith($s, 'public/')) {
                                $s = substr($s, 7);
                                return '/storage/'.$s;
                            }
                            if (!\Illuminate\Support\Str::startsWith($s, 'storage/')) {
                                $s = 'storage/'.$s;
                            }
                            return '/'.$s;
                        };

                        // Build unique list keyed by obje_id or element id
                        $listed = [];

                        // Count occurrences per obje_id so we can show quantities in the list
                        $counts = [];
                        foreach ($elements as $ee) {
                            $oid = $ee['obje_id'] ?? null;
                            if ($oid) {
                                if (!isset($counts[$oid])) $counts[$oid] = 0;
                                $counts[$oid]++;
                            }
                        }
                    @endphp

                    @if (empty($elements))
                        <div class="empty-state">
                            <strong>Bu tasarımda obje yok</strong>
                            <div style="font-size:13px; color:color-mix(in srgb, var(--fd-text) 60%, transparent);">Tasarım kaydı boş veya yüklenemedi.</div>
                        </div>
                    @else
                        @foreach($elements as $el)
                            @php
                                $objeId = $el['obje_id'] ?? null;
                                // ensure uniqueness by obje id if present otherwise by index
                                $key = $objeId ? 'o_'.$objeId : 'e_'.(isset($el['id']) ? $el['id'] : spl_object_id((object)$el));
                                if (isset($listed[$key])) { continue; }
                                $listed[$key] = true;

                                // resolve image and name by priority: element data, controller's objectData (DB), fallback to global $objeler
                                $image = $el['image_url'] ?? null;
                                $name = $el['name'] ?? null;

                                // prefer controller-provided objectData which contains media urls
                                if (empty($image) && isset($objectData) && $objeId && isset($objectData[$objeId])) {
                                    $image = $objectData[$objeId]['image_url'] ?? null;
                                }
                                if (empty($name) && isset($objectData) && $objeId && isset($objectData[$objeId])) {
                                    $name = $objectData[$objeId]['name'] ?? null;
                                }

                                // fallback to scanning $objeler collection (models)
                                if ((empty($image) || empty($name)) && !empty($objeler)) {
                                    foreach ($objeler as $o) {
                                        $oid = is_array($o) ? ($o['id'] ?? ($o['obje_id'] ?? null)) : ($o->id ?? ($o->obje_id ?? null));
                                        if ($objeId && $oid == $objeId) {
                                            if (empty($image)) {
                                                $image = is_array($o) ? ($o['image_url'] ?? ($o['image'] ?? null)) : (\App\Helpers\MediaHelper::getImageUrl($o, 'images') ?? ($o->image_url ?? ($o->image ?? null)) );
                                            }
                                            if (empty($name)) {
                                                $name = is_array($o) ? ($o['name'] ?? ($o['isim'] ?? null)) : ($o->name ?? ($o->isim ?? null));
                                            }
                                            break;
                                        }
                                    }
                                }

                                $image = $normalizeSrc($image) ?? '/images/default-object.png';
                                $name = $name ?? 'İsim yok';
                            @endphp

                            <div class="palette-list-item">
                                <img src="{{ $image }}" alt="{{ $name }}" class="palette-thumb">
                                <div class="palette-meta">
                                    <div class="palette-name">{{ $name }}</div>
                                </div>
                                @if(isset($objeId) && isset($counts[$objeId]) && $counts[$objeId] > 1)
                                    <div class="palette-count">{{ $counts[$objeId] }}</div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="design-area">
                    <div class="property-boundary" id="propertyBoundary">

                        <!-- Arka plan resmi container -->
                        <div class="background-image-container" id="backgroundImageContainer">
                            @if(!empty($project_image))
                                @php
                                    $bg = $project_image;
                                    if (!str_starts_with($bg,'http') && !str_starts_with($bg,'/')) {
                                        if (!str_starts_with($bg,'storage/')) $bg = 'storage/'.$bg;
                                        $bg = '/'.$bg;
                                    }
                                @endphp
                                <img src="{{ $bg }}" class="background-image" alt="Proje Arka Plan Resmi">
                            @else
                                <div style="width:100%; height:100%; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                            @endif
                        </div>

                        {{-- Render static elements from saved design --}}
                        @if(!empty($elements))
                            @foreach($elements as $el)
                                @php
                                    // Determine coordinates and size. Support both new {location, scale} and old {x,y,width,height}
                                    $x = 0; $y = 0; $w = 120; $h = 120;
                                    if (isset($el['location']) && is_array($el['location'])) {
                                        $x = $el['location']['x'] ?? 0;
                                        $y = $el['location']['y'] ?? 0;
                                        if (isset($el['scale']) && is_array($el['scale'])) {
                                            $w = ($el['scale']['x'] ?? 1) * 120;
                                            $h = ($el['scale']['y'] ?? 1) * 120;
                                        }
                                    } elseif (isset($el['x']) && isset($el['y'])) {
                                        $x = $el['x'] ?? 0;
                                        $y = $el['y'] ?? 0;
                                        $w = $el['width'] ?? $w;
                                        $h = $el['height'] ?? $h;
                                    }

                                    $img = $el['image_url'] ?? null;
                                    $name = $el['name'] ?? null;
                                    // use preloaded objectData (controller) first
                                    if (empty($img) && isset($objectData) && isset($el['obje_id']) && isset($objectData[$el['obje_id']])) {
                                        $img = $objectData[$el['obje_id']]['image_url'] ?? null;
                                    }
                                    if (empty($name) && isset($objectData) && isset($el['obje_id']) && isset($objectData[$el['obje_id']])) {
                                        $name = $objectData[$el['obje_id']]['name'] ?? null;
                                    }

                                    // fallback to global objeler collection
                                    if ((empty($img) || empty($name)) && !empty($objeler)) {
                                        foreach ($objeler as $o) {
                                            $oid = is_array($o) ? ($o['id'] ?? ($o['obje_id'] ?? null)) : ($o->id ?? ($o->obje_id ?? null));
                                            if (isset($el['obje_id']) && $oid == $el['obje_id']) {
                                                if (empty($img)) {
                                                    $img = is_array($o) ? ($o['image_url'] ?? ($o['image'] ?? null)) : (\App\Helpers\MediaHelper::getImageUrl($o, 'images') ?? ($o->image_url ?? ($o->image ?? null)) );
                                                }
                                                if (empty($name)) {
                                                    $name = is_array($o) ? ($o['name'] ?? ($o['isim'] ?? null)) : ($o->name ?? ($o->isim ?? null));
                                                }
                                                break;
                                            }
                                        }
                                    }

                                    $img = $normalizeSrc($img) ?? '/images/default-object.png';
                                @endphp

                                <div class="landscape-element" title="{{ $name }}" style="transform: translate({{ (int)$x }}px, {{ (int)$y }}px); width: {{ (int)$w }}px; height: {{ (int)$h }}px;">
                                    <div class="element-content">
                                        @if($name && $name !== 'null')
                                            <div class="element-label">
                                                <div class="element-name">{{ $name }}</div>
                                            </div>
                                        @endif
                                        <img src="{{ $img }}" alt="{{ $name }}">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
</div>
