<div id="filament-designer-root">
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

            /* Filament-aware theme variables (fall back to sensible defaults) */
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

            /* Support Tailwind/Filament dark class (when Dark Mode is toggled via class) */
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

        .element-palette {
            width: 250px;
            background: var(--fd-panel-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--fd-border);
            overflow-y: auto;
        }

        /* Empty state for palette */
        .empty-palette {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px;
            border-radius: 10px;
            background: color-mix(in srgb, var(--fd-panel-bg) 85%, transparent);
            border: 1px dashed var(--fd-border);
            color: var(--fd-text);
        }

        .empty-palette .empty-icon {
            font-size: 34px;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: color-mix(in srgb, var(--fd-accent) 10%, transparent);
        }

        .empty-palette .empty-content h3 {
            margin: 0 0 6px 0;
            font-size: 15px;
            font-weight: 700;
            color: var(--fd-text);
        }

        .empty-palette .empty-content p {
            margin: 0;
            font-size: 13px;
            color: color-mix(in srgb, var(--fd-text) 70%, transparent);
        }

        .empty-palette .empty-hint {
            margin-top: 8px;
            font-size: 12px;
            color: color-mix(in srgb, var(--fd-text) 50%, transparent);
        }

        .palette-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            margin: 8px 0;
            background: var(--fd-bg);
            border-radius: 8px;
            cursor: grab;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            touch-action: none;
        }

        .palette-item:hover {
            background: color-mix(in srgb, var(--fd-panel-bg) 90%, transparent);
            border-color: var(--fd-accent);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.12);
        }

        .palette-image {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }

        .palette-item span {
            font-weight: 500;
            color: var(--fd-text);
        }

        .design-area {
            flex: 1;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
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
            cursor: grab;
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
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.18);
            z-index: 15;
        }

        .landscape-element.selected {
            border-color: var(--fd-accent);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.24);
            z-index: 20;
        }

        .landscape-element:active {
            cursor: grabbing;
        }

        .element-content {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* lighter element background so the main image stays visible */
            background: var(--fd-element-bg, var(--fd-muted-white));
            position: relative;
            border-radius: 6px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Name wrapper that sizes to the text */
        .element-name-wrap {
            display: inline-block;
            background: var(--fd-name-bg);
            color: var(--fd-name-text);
            padding: 4px 8px;
            border-radius: 6px;
            margin-top: 6px;
            max-width: 110px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 11px;
            font-weight: 600;
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
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(0,0,0,0.6);
            background: rgba(0,0,0,0.32);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Kontrol handle'ları */
        .resize-handle {
            position: absolute;
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border: 2px solid white;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .landscape-element.selected .resize-handle {
            opacity: 1;
        }

        .resize-se { bottom: -6px; right: -6px; cursor: se-resize; }
        .resize-sw { bottom: -6px; left: -6px; cursor: sw-resize; }
        .resize-ne { top: -6px; right: -6px; cursor: ne-resize; }
        .resize-nw { top: -6px; left: -6px; cursor: nw-resize; }

        /* Resize handles */
        .resize-handle {

        /* Custom image items */
        .custom-image-item {
            background: color-mix(in srgb, var(--fd-info) 10%, var(--fd-panel-bg));
            border-color: var(--fd-info);
        }

        .custom-image-item:hover {
            background: color-mix(in srgb, var(--fd-info) 14%, var(--fd-panel-bg));
            border-color: color-mix(in srgb, var(--fd-info) 90%, black);
        }

        .landscape-element img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .landscape-studio {
                flex-direction: column;
                height: auto;
            }

            .element-palette {
                width: 100%;
                max-height: 300px;
                overflow-y: auto;
            }

            .design-area {
                height: 70vh;
                min-height: 500px;
            }
        }
        </style>
        <div class="landscape-designer-wrapper">
        <div class="landscape-studio">
            <div class="element-palette">
                <h3 style="margin-bottom: 20px; color: #374151; font-weight: 600;">Peyzaj Öğeleri</h3>

                <!-- Veritabanından Gelen Objeler -->
                @foreach($objeler as $obje)
                    <div class="palette-item" 
                         data-element="obje_{{ $obje['id'] }}" 
                         data-obje-id="{{ $obje['id'] }}"
                         data-image="{{ $obje['image_url'] ?: 'https://picsum.photos/80/80?random=' . $obje['id'] }}" 
                         data-name="{{ $obje['isim'] }}">
                        <img src="{{ $obje['image_url'] ?: 'https://picsum.photos/40/40?random=' . $obje['id'] }}" 
                             alt="{{ $obje['isim'] }}" 
                             class="palette-image">
                        <span>{{ $obje['isim'] }}</span>
                    </div>
                @endforeach

                @if($objeler->isEmpty())
                    <!-- Empty-state: nicer notification when there are no objects -->
                    <div class="empty-palette">
                        <div class="empty-icon" aria-hidden="true">ℹ️</div>
                        <div class="empty-content">
                            <h3>Obje veritabanı boş</h3>
                            <p>Henüz kayıtlı obje bulunmuyor. Yeni obje ekleyerek peyzaj öğelerinizi buraya ekleyebilirsiniz.</p>
                            <div class="empty-hint">Filament > Obje kaydı üzerinden yeni obje oluşturun.</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="design-area">
                <div class="property-boundary" id="propertyBoundary">
                    
                    <!-- Arka plan resmi container -->
                    <div class="background-image-container" id="backgroundImageContainer">
                        <!-- Yüklenen proje resmi buraya gelecek -->
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.27/interact.min.js"></script>

    <script>
    
        let selectedElement = null;
        let elementCounter = 0;

        function selectElement(element) {
            if (selectedElement) {
                selectedElement.classList.remove('selected');
            }
            selectedElement = element;
            element.classList.add('selected');
        }

        function deselectAll() {
            if (selectedElement) {
                selectedElement.classList.remove('selected');
                selectedElement = null;
            }
        }

        function createElement(type, imageUrl, name, x = 0, y = 0, objeId = null) {
            elementCounter++;
            const elementId = `element_${type}_${elementCounter}`;
            const element = document.createElement('div');
            element.id = elementId;
            element.className = 'landscape-element';
            element.style.transform = `translate(${x}px, ${y}px)`;
            
            // Obje ID'sini element'e ekle
            if (objeId) {
                element.setAttribute('data-obje-id', objeId);
            }

            const content = document.createElement('div');
            content.className = 'element-content';
            
            // Put the label as an overlay above the image for readability
            const safeName = name || '';
            content.innerHTML = `
                <div class="element-label"><div class="element-name">${safeName}</div></div>
                <img src="${imageUrl}" alt="${safeName}" />
            `;

            // Resize handles
            const handles = ['se', 'sw', 'ne', 'nw'];
            handles.forEach(pos => {
                const handle = document.createElement('div');
                handle.className = `resize-handle resize-${pos}`;
                element.appendChild(handle);
            });
            
            element.appendChild(content);
            return element;
        }

        function placeElement(element, x, y) {
            const boundary = document.getElementById('propertyBoundary');
            element.style.transform = `translate(${x}px, ${y}px)`;
            // store coordinates so interact.js has the correct baseline on first drag
            element.setAttribute('data-x', x);
            element.setAttribute('data-y', y);
            boundary.appendChild(element);
            makeElementInteractive(element);
            selectElement(element);
        }

        function makeElementInteractive(element) {
            // Draggable
            interact(element).draggable({
                listeners: {
                    start(event) {
                        selectElement(event.target);
                    },
                    move(event) {
                        const target = event.target;
                        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                        target.style.transform = `translate(${x}px, ${y}px)`;
                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);
                    }
                }
            });

            // Resizable
            interact(element).resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                listeners: {
                    start(event) {
                        selectElement(event.target);
                    },
                    move(event) {
                        const target = event.target;
                        let { width, height } = event.rect;
                        
                        width = Math.max(50, width);
                        height = Math.max(50, height);

                        target.style.width = width + 'px';
                        target.style.height = height + 'px';

                        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.deltaRect.left;
                        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.deltaRect.top;

                        target.style.transform = `translate(${x}px, ${y}px)`;
                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);
                    }
                },
                modifiers: [
                    interact.modifiers.restrictSize({
                        min: { width: 50, height: 50 }
                    })
                ]
            });
        }

        // Palette dragging with interact.js - create a follow-cursor ghost during drag
        (function() {
            let currentGhost = null;
            let ghostSize = 80; // px square for preview
            let ghostOffset = { x: ghostSize / 2, y: ghostSize / 2 };

            function createGhost(imageUrl) {
                const g = document.createElement('div');
                g.className = 'drag-ghost';
                g.style.position = 'fixed';
                g.style.left = '0px';
                g.style.top = '0px';
                g.style.width = ghostSize + 'px';
                g.style.height = ghostSize + 'px';
                g.style.pointerEvents = 'none';
                g.style.zIndex = 9999;
                g.style.transition = 'transform 0.02s linear';
                g.style.display = 'flex';
                g.style.alignItems = 'center';
                g.style.justifyContent = 'center';

                const img = document.createElement('img');
                img.src = imageUrl;
                img.alt = 'ghost';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '6px';
                img.style.boxShadow = '0 6px 18px rgba(0,0,0,0.18)';

                g.appendChild(img);
                document.body.appendChild(g);
                return g;
            }

            function setGhostPos(g, clientX, clientY) {
                if (!g) return;
                const left = clientX - ghostOffset.x;
                const top = clientY - ghostOffset.y;
                g.style.transform = `translate3d(${left}px, ${top}px, 0)`;
            }

            interact('.palette-item').draggable({
                listeners: {
                    start(event) {
                        const item = event.target;
                        const imageUrl = item.getAttribute('data-image') || '';
                        // create ghost preview
                        currentGhost = createGhost(imageUrl);
                        // position immediately
                        setGhostPos(currentGhost, event.clientX, event.clientY);
                    },
                    move(event) {
                        // move ghost to follow cursor
                        if (currentGhost) {
                            setGhostPos(currentGhost, event.clientX, event.clientY);
                        }
                    },
                    end(event) {
                        const boundary = document.getElementById('propertyBoundary');
                        const dropzone = document.elementFromPoint(event.clientX, event.clientY);

                        if (currentGhost) {
                            currentGhost.remove();
                            currentGhost = null;
                        }

                        // Check if dropped in the design area
                        if (boundary.contains(dropzone) || dropzone === boundary) {
                            const item = event.target;
                            const imageUrl = item.getAttribute('data-image');
                            const name = item.getAttribute('data-name');
                            const type = item.getAttribute('data-element') || 'custom';
                            const objeId = item.getAttribute('data-obje-id'); // Obje ID'sini al

                            const boundaryRect = boundary.getBoundingClientRect();
                            // account for ghost offset so element centers where the cursor is
                            const relativeX = event.clientX - boundaryRect.left - ghostOffset.x;
                            const relativeY = event.clientY - boundaryRect.top - ghostOffset.y;

                            const newElement = createElement(type, imageUrl, name, 0, 0, objeId);
                            placeElement(newElement, relativeX, relativeY);
                        }
                    }
                }
            });
        })();

        // Event listeners
        document.getElementById('propertyBoundary').addEventListener('click', function (e) {
            if (e.target === this || e.target.classList.contains('boundary-label')) {
                deselectAll();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Delete' && selectedElement) {
                if (selectedElement && confirm('Seçili elementi silmek istediğinizden emin misiniz?')) {
                    selectedElement.remove();
                    deselectAll();
                }
            }
            if (e.key === 'Escape') {
                deselectAll();
            }
        });

        // URL'den proje resmini yükle ve arka plan olarak ayarla
        document.addEventListener('DOMContentLoaded', function() {
            // PHP'den gelen verileri JavaScript'e aktar
            const objeler = @json($objeler ?? []);
            const projectId = @json($project_id ?? null);
            const projectImage = @json($project_image ?? null);
            const existingDesign = @json($existing_design ?? null);
            
            // Global değişkenleri ayarla
            window.projectId = projectId;
            window.objeler = objeler;
            
            // Objeleri pallete yükle
            loadObjectsToPalette(objeler);
            
            // Arka plan resmini yükle
            if (projectImage) {
                loadBackgroundImage(projectImage);
            } else {
                // Resim yoksa gradient arka plan kullan
                const backgroundContainer = document.getElementById('backgroundImageContainer');
                backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            }
            
            // Mevcut tasarımı yükle (eğer varsa)
            if (existingDesign && existingDesign.elements) {
                loadExistingDesign(existingDesign);
            }
        });
        
        function loadObjectsToPalette(objeler) {
            const palette = document.querySelector('.element-palette');
            
            if (!objeler || objeler.length === 0) {
                // Boş durum göster
                palette.innerHTML = `
                    <div class="empty-palette">
                        <div class="empty-icon">🌿</div>
                        <div class="empty-content">
                            <h3>Obje bulunamadı</h3>
                            <p>Henüz tasarım objesi eklenmemiş</p>
                            <div class="empty-hint">Yöneticiden obje eklemesini isteyin</div>
                        </div>
                    </div>
                `;
                return;
            }
            
            // Objeleri listele
            let paletteHTML = '';
            objeler.forEach(obje => {
                const imageUrl = obje.image_url || '/images/default-object.png';
                paletteHTML += `
                    <div class="palette-item" data-obje-id="${obje.id}" data-image="${imageUrl}" data-name="${obje.isim}">
                        <img src="${imageUrl}" alt="${obje.isim}" class="palette-image" />
                        <span>${obje.isim}</span>
                    </div>
                `;
            });
            
            palette.innerHTML = paletteHTML;
        }
        
        function loadBackgroundImage(projectImage) {
            const backgroundContainer = document.getElementById('backgroundImageContainer');
            const img = document.createElement('img');
            
            // Resim path'ini düzelt
            let imageSrc = projectImage;
            if (!imageSrc.startsWith('http') && !imageSrc.startsWith('/')) {
                if (!imageSrc.startsWith('storage/')) {
                    imageSrc = 'storage/' + imageSrc;
                }
                imageSrc = '/' + imageSrc;
            }
            
            img.src = imageSrc;
            img.className = 'background-image';
            img.alt = 'Proje Arka Plan Resmi';
            
            img.onload = function() {
                console.log('Proje arka plan resmi yüklendi:', imageSrc);
            };
            
            img.onerror = function() {
                console.error('Arka plan resmi yüklenemedi:', imageSrc);
                // Hata durumunda gradient arka plan kullan
                backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            };
            
            backgroundContainer.appendChild(img);
        }
        
        function loadExistingDesign(designData) {
            // Mevcut tasarımı yükle
            if (designData.elements && designData.elements.length > 0) {
                const boundary = document.getElementById('propertyBoundary');
                
                designData.elements.forEach(elementData => {
                    const element = createElement(
                        'custom',
                        elementData.image_url,
                        elementData.name,
                        elementData.x,
                        elementData.y,
                        elementData.obje_id
                    );
                    
                    // Boyutları ayarla
                    element.style.width = elementData.width + 'px';
                    element.style.height = elementData.height + 'px';
                    
                    boundary.appendChild(element);
                    makeElementInteractive(element);
                });
                
                console.log('Mevcut tasarım yüklendi:', designData.elements.length + ' element');
            }
        }
        </script>
    </div>
</x-filament-panels::page>
</div>