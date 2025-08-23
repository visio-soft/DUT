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
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow-y: auto;
        }

        .palette-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            margin: 8px 0;
            background: #f9fafb;
            border-radius: 8px;
            cursor: grab;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            touch-action: none;
        }

        .palette-item:hover {
            background: #f3f4f6;
            border-color: #10b981;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .palette-image {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }

        .palette-item span {
            font-weight: 500;
            color: #374151;
        }

        .design-area {
            flex: 1;
            background: #f8fafc;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .property-boundary {
            position: absolute;
            top: 50px;
            left: 50px;
            right: 50px;
            bottom: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: 4px dashed #10b981;
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
            z-index: 1;
        }

        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.4;
            border-radius: 12px;
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
            color: #374151;
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
            border-radius: 8px;
            transition: all 0.2s ease;
            z-index: 10;
            width: 120px;
            height: 120px;
        }

        .landscape-element:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            z-index: 15;
        }

        .landscape-element.selected {
            border-color: #10b981;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 6px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .element-name {
            font-size: 11px;
            font-weight: 500;
            color: #374151;
            text-align: center;
            margin-top: 4px;
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

        /* Toolbar */
        .toolbar {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 30;
        }

        .toolbar-btn {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #374151;
        }

        .toolbar-btn:hover {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .toolbar-btn.delete {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .toolbar-btn.delete:hover {
            background: #fecaca;
        }

        /* Custom image items */
        .custom-image-item {
            background: #f0f9ff;
            border-color: #0ea5e9;
        }

        .custom-image-item:hover {
            background: #e0f2fe;
            border-color: #0284c7;
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
                         data-image="{{ $obje['image_url'] ?: 'https://picsum.photos/80/80?random=' . $obje['id'] }}" 
                         data-name="{{ $obje['isim'] }}">
                        <img src="{{ $obje['image_url'] ?: 'https://picsum.photos/40/40?random=' . $obje['id'] }}" 
                             alt="{{ $obje['isim'] }}" 
                             class="palette-image">
                        <span>{{ $obje['isim'] }}</span>
                    </div>
                @endforeach

                @if($objeler->isEmpty())
                    <!-- Varsayılan Öğeler (Obje yoksa gösterilecek) -->
                    <div class="palette-item" data-element="tree" data-image="https://picsum.photos/80/80?random=1" data-name="Ağaç">
                        <img src="https://picsum.photos/40/40?random=1" alt="Ağaç Resmi" class="palette-image">
                        <span>Ağaç</span>
                    </div>

                    <div class="palette-item" data-element="flower" data-image="https://picsum.photos/80/80?random=2" data-name="Çiçek">
                        <img src="https://picsum.photos/40/40?random=2" alt="Çiçek Resmi" class="palette-image">
                        <span>Çiçek</span>
                    </div>

                    <div class="palette-item" data-element="bush" data-image="https://picsum.photos/80/80?random=3" data-name="Çalı">
                        <img src="https://picsum.photos/40/40?random=3" alt="Çalı Resmi" class="palette-image">
                        <span>Çalı</span>
                    </div>

                    <div class="palette-item" data-element="pot" data-image="https://picsum.photos/80/80?random=4" data-name="Saksı">
                        <img src="https://picsum.photos/40/40?random=4" alt="Saksı Resmi" class="palette-image">
                        <span>Saksı</span>
                    </div>

                    <div class="palette-item" data-element="fountain" data-image="https://picsum.photos/80/80?random=5" data-name="Çeşme">
                        <img src="https://picsum.photos/40/40?random=5" alt="Çeşme Resmi" class="palette-image">
                        <span>Çeşme</span>
                    </div>

                    <div class="palette-item" data-element="stone" data-image="https://picsum.photos/80/80?random=6" data-name="Taş">
                        <img src="https://picsum.photos/40/40?random=6" alt="Taş Resmi" class="palette-image">
                        <span>Taş</span>
                    </div>

                    <div class="palette-item" data-element="pool" data-image="https://picsum.photos/80/80?random=7" data-name="Havuz">
                        <img src="https://picsum.photos/40/40?random=7" alt="Havuz Resmi" class="palette-image">
                        <span>Havuz</span>
                    </div>

                    <div class="palette-item" data-element="bench" data-image="https://picsum.photos/80/80?random=8" data-name="Bank">
                        <img src="https://picsum.photos/40/40?random=8" alt="Bank Resmi" class="palette-image">
                        <span>Bank</span>
                    </div>
                @endif
            </div>

            <div class="design-area">
                <div class="toolbar">
                    <button class="toolbar-btn" onclick="clearAll()">🗑️ Temizle</button>
                    <button class="toolbar-btn" onclick="saveDesign()">💾 Kaydet</button>
                    <button class="toolbar-btn delete" onclick="deleteSelected()" id="deleteBtn" style="display: none;">❌ Sil</button>
                </div>

                <div class="property-boundary" id="propertyBoundary">
                    <div class="boundary-label">Tasarım Alanı</div>
                    
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
            document.getElementById('deleteBtn').style.display = 'block';
        }

        function deselectAll() {
            if (selectedElement) {
                selectedElement.classList.remove('selected');
                selectedElement = null;
            }
            document.getElementById('deleteBtn').style.display = 'none';
        }

        function createElement(type, imageUrl, name, x = 0, y = 0) {
            elementCounter++;
            const elementId = `element_${type}_${elementCounter}`;
            const element = document.createElement('div');
            element.id = elementId;
            element.className = 'landscape-element';
            element.style.transform = `translate(${x}px, ${y}px)`;

            const content = document.createElement('div');
            content.className = 'element-content';
            
            if (type === 'custom') {
                content.innerHTML = `<img src="${imageUrl}" alt="${name}" />`;
            } else {
                content.innerHTML = `
                    <img src="${imageUrl}" alt="${name}" />
                    <div class="element-name">${name}</div>
                `;
            }

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

        // Palette dragging with interact.js
        interact('.palette-item').draggable({
            listeners: {
                start(event) {
                    console.log('Drag started on:', event.target);
                },
                move(event) {
                    // Visual feedback during drag can be added here
                },
                end(event) {
                    const dropzone = document.elementFromPoint(event.clientX, event.clientY);
                    const boundary = document.getElementById('propertyBoundary');
                    
                    // Check if dropped in the design area
                    if (boundary.contains(dropzone) || dropzone === boundary) {
                        const item = event.target;
                        const imageUrl = item.getAttribute('data-image');
                        const name = item.getAttribute('data-name');
                        const type = item.getAttribute('data-element');
                        
                        const boundaryRect = boundary.getBoundingClientRect();
                        const relativeX = event.clientX - boundaryRect.left - 60;
                        const relativeY = event.clientY - boundaryRect.top - 60;
                        
                        const newElement = createElement(type, imageUrl, name);
                        placeElement(newElement, relativeX, relativeY);
                    }
                }
            }
        });

        function clearAll() {
            if (confirm('Tüm tasarımı temizlemek istediğinizden emin misiniz?')) {
                const boundary = document.getElementById('propertyBoundary');
                const elements = boundary.querySelectorAll('.landscape-element');
                elements.forEach(element => element.remove());
                deselectAll();
            }
        }

        function deleteSelected() {
            if (selectedElement && confirm('Seçili elementi silmek istediğinizden emin misiniz?')) {
                selectedElement.remove();
                deselectAll();
            }
        }

        function saveDesign() {
            const elements = [];
            const boundary = document.getElementById('propertyBoundary');
            const landscapeElements = boundary.querySelectorAll('.landscape-element');

            landscapeElements.forEach(element => {
                const content = element.querySelector('.element-content');
                const image = content.querySelector('img');
                const nameElement = content.querySelector('.element-name');
                const name = nameElement ? nameElement.textContent : 'Custom Image';
                const imageUrl = image.src;
                const x = parseFloat(element.getAttribute('data-x')) || 0;
                const y = parseFloat(element.getAttribute('data-y')) || 0;

                elements.push({
                    id: element.id,
                    type: element.id.split('_')[1],
                    imageUrl: imageUrl,
                    name: name,
                    x: x,
                    y: y,
                    width: element.style.width,
                    height: element.style.height
                });
            });

            const design = {
                elements: elements,
                timestamp: new Date().toISOString()
            };

            window.savedDesign = design;
            alert('Tasarım başarıyla kaydedildi! 💾');
        }

        // Event listeners
        document.getElementById('propertyBoundary').addEventListener('click', function (e) {
            if (e.target === this || e.target.classList.contains('boundary-label')) {
                deselectAll();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Delete' && selectedElement) {
                deleteSelected();
            }
            if (e.key === 'Escape') {
                deselectAll();
            }
        });

        // URL'den proje resmini yükle ve arka plan olarak ayarla
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const projectImage = urlParams.get('image');
            
            if (projectImage) {
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
            } else {
                // Resim yoksa gradient arka plan kullan
                const backgroundContainer = document.getElementById('backgroundImageContainer');
                backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            }
        });
        </script>
    </div>
</x-filament-panels::page>