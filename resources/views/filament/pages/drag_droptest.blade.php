@push('styles')
    <style>
        .property-boundary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg,
            rgba(34, 197, 94, 0.1) 25%,
            transparent 25%,
            transparent 75%,
            rgba(34, 197, 94, 0.1) 75%),
            linear-gradient(45deg,
                rgba(34, 197, 94, 0.1) 25%,
                transparent 25%,
                transparent 75%,
                rgba(34, 197, 94, 0.1) 75%);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
        }

        .boundary-label {
            position: absolute;
            top: 10px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            color: #16a34a;
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
            transform-origin: center center;
            z-index: 10;
        }

        .landscape-element:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .landscape-element.selected {
            border-color: #10b981;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            z-index: 20;
        }

        .landscape-element:active {
            cursor: grabbing;
        }

        .landscape-element * {
            pointer-events: none;
            /* Child elementlerin drag'i engellemesini önle */
        }

        .landscape-element .resize-handle,
        .landscape-element .rotate-handle {
            pointer-events: auto;
            /* Handle'ların tıklanabilir olmasını sağla */
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

        .element-icon {
            font-size: 2em;
            margin-bottom: 4px;
        }

        .element-name {
            font-size: 11px;
            font-weight: 500;
            color: #374151;
            text-align: center;
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

        .resize-se {
            bottom: -6px;
            right: -6px;
            cursor: se-resize;
        }

        .resize-sw {
            bottom: -6px;
            left: -6px;
            cursor: sw-resize;
        }

        .resize-ne {
            top: -6px;
            right: -6px;
            cursor: ne-resize;
        }

        .resize-nw {
            top: -6px;
            left: -6px;
            cursor: nw-resize;
        }

        .rotate-handle {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: #ef4444;
            border: 2px solid white;
            border-radius: 50%;
            cursor: grab;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .landscape-element.selected .rotate-handle {
            opacity: 1;
        }

        .rotate-handle:active {
            cursor: grabbing;
        }

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
        }

        .toolbar-btn:hover {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .toolbar-btn.delete {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .toolbar-btn.delete:hover {
            background: #fecaca;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .landscape-studio {
                flex-direction: column;
                height: auto;
            }

            .element-palette {
                width: 100%;
                max-height: 200px;
            }

            .design-area {
                height: 70vh;
            }
        }

        /* Drag preview */
        .drag-preview {
            position: fixed;
            pointer-events: none;
            z-index: 1000;
            opacity: 0.8;
            transform: scale(0.8);
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
        }

        .palette-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            margin: 5px 0;
            background: #f9fafb;
            border-radius: 8px;
            cursor: grab;
        }

        .palette-item:hover {
            background: #f3f4f6;
            border: 2px solid #10b981;
        }

        .palette-image {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }

        .design-area {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            position: relative;
        }

        .property-boundary {
            position: absolute;
            top: 50px;
            left: 50px;
            right: 50px;
            bottom: 50px;
            background: #22c55e;
            border: 4px dashed #16a34a;
            border-radius: 16px;
        }

        .landscape-element {
            position: absolute;
            cursor: grab;
            border: 3px solid transparent;
            border-radius: 8px;
            z-index: 10;
        }

        .landscape-element:hover {
            border-color: #3b82f6;
        }

        .landscape-element.selected {
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
        }

        .landscape-element img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .resize-handle {
            position: absolute;
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border: 2px solid white;
            border-radius: 50%;
            opacity: 0;
        }

        .landscape-element.selected .resize-handle {
            opacity: 1;
        }

        .resize-se {
            bottom: -6px;
            right: -6px;
            cursor: se-resize;
        }

        .resize-sw {
            bottom: -6px;
            left: -6px;
            cursor: sw-resize;
        }

        .resize-ne {
            top: -6px;
            right: -6px;
            cursor: ne-resize;
        }

        .resize-nw {
            top: -6px;
            left: -6px;
            cursor: nw-resize;
        }

        .rotate-handle {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: #ef4444;
            border: 2px solid white;
            border-radius: 50%;
            cursor: grab;
            opacity: 0;
        }

        .landscape-element.selected .rotate-handle {
            opacity: 1;
        }

        .toolbar {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
        }

        .toolbar-btn {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
@endpush

<x-filament-panels::page>
    <div id="drag-droptest-root">
        <div class="landscape-designer-wrapper">
            <div class="landscape-studio">
                <div class="element-palette">
                    <h3>🏡 Peyzaj Öğeleri</h3>

                    <!-- Kendi resimlerinizi buraya ekleyin -->
                    <div class="palette-item" data-element="tree" data-image="/images/tree1.jpg" data-name="Ağaç">
                        <img src="/images/tree1.jpg" alt="Ağaç" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🌳</span>
                        <span>Ağaç</span>
                    </div>

                    <div class="palette-item" data-element="flower" data-image="/images/flower1.jpg" data-name="Çiçek">
                        <img src="/images/flower1.jpg" alt="Çiçek" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🌸</span>
                        <span>Çiçek</span>
                    </div>

                    <div class="palette-item" data-element="bush" data-image="/images/bush1.jpg" data-name="Çalı">
                        <img src="/images/bush1.jpg" alt="Çalı" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🌿</span>
                        <span>Çalı</span>
                    </div>

                    <div class="palette-item" data-element="pot" data-image="/images/pot1.jpg" data-name="Saksı">
                        <img src="/images/pot1.jpg" alt="Saksı" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🪴</span>
                        <span>Saksı</span>
                    </div>

                    <div class="palette-item" data-element="fountain" data-image="/images/fountain1.jpg"
                         data-name="Çeşme">
                        <img src="/images/fountain1.jpg" alt="Çeşme" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">⛲</span>
                        <span>Çeşme</span>
                    </div>

                    <div class="palette-item" data-element="stone" data-image="/images/stone1.jpg" data-name="Taş">
                        <img src="/images/stone1.jpg" alt="Taş" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🪨</span>
                        <span>Taş</span>
                    </div>

                    <div class="palette-item" data-element="pool" data-image="/images/pool1.jpg" data-name="Havuz">
                        <img src="/images/pool1.jpg" alt="Havuz" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🏊‍♂️</span>
                        <span>Havuz</span>
                    </div>

                    <div class="palette-item" data-element="bench" data-image="/images/bench1.jpg" data-name="Bank">
                        <img src="/images/bench1.jpg" alt="Bank" class="palette-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none;">🪑</span>
                        <span>Bank</span>
                    </div>
                </div>

                <div class="design-area">
                    <div class="toolbar">
                        <button class="toolbar-btn" onclick="clearAll()">🗑️ Temizle</button>
                        <button class="toolbar-btn" onclick="saveDesign()">💾 Kaydet</button>
                        <button class="toolbar-btn" onclick="deleteSelected()" id="deleteBtn" style="display: none;">❌
                            Sil
                        </button>
                    </div>
                    <div class="property-boundary" id="propertyBoundary">
                        <div
                            style="position: absolute; top: 10px; left: 20px; background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 20px; font-size: 12px; color: #16a34a;">
                            🏡 Arsa Sınırı
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.27/interact.min.js" integrity="sha512-U7pxMz47PpFp2XkN2CrzPoILVGEwb6dN0o0/gcz6wserNSwnprr4YoVd5QHHWHVVSA+5gCxeh0yZDVy28T91nQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let selectedElement = null;
        let elementCounter = 0;
        let isResizing = false;
        let isRotating = false;

        function getTransform(element) {
            return {
                x: parseFloat(element.getAttribute('data-x')) || 0,
                y: parseFloat(element.getAttribute('data-y')) || 0,
                rotation: parseFloat(element.getAttribute('data-rotation')) || 0
            };
        }

        function setTransform(element, transform) {
            element.setAttribute('data-x', transform.x);
            element.setAttribute('data-y', transform.y);
            element.setAttribute('data-rotation', transform.rotation);
            element.style.transform = `translate(${transform.x}px, ${transform.y}px) rotate(${transform.rotation}deg)`;
        }

        function selectElement(element) {
            if (selectedElement) selectedElement.classList.remove('selected');
            selectedElement = element;
            element.classList.add('selected');
            document.getElementById('deleteBtn').style.display = 'block';
        }

        function createElement(type, imageSrc, name, x = 100, y = 100) {
            elementCounter++;
            const element = document.createElement('div');
            element.id = `element_${type}_${elementCounter}`;
            element.className = 'landscape-element';
            element.style.cssText = `transform: translate(${x}px, ${y}px); width: 80px; height: 80px;`;

            element.innerHTML = `
					<img src="${imageSrc}" alt="${name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
					<div class="resize-handle resize-se"></div>
					<div class="resize-handle resize-sw"></div>
					<div class="resize-handle resize-ne"></div>
					<div class="resize-handle resize-nw"></div>
					<div class="rotate-handle"></div>
				`;

            return element;
        }

        function makeElementInteractive(element) {
            interact(element)
                .draggable({
                    modifiers: [interact.modifiers.restrictRect({
                        restriction: '#propertyBoundary',
                        endOnly: true
                    })],
                    listeners: {
                        move: function (event) {
                            if (isResizing || isRotating) return;
                            const transform = getTransform(event.target);
                            transform.x += event.dx;
                            transform.y += event.dy;
                            setTransform(event.target, transform);
                        },
                        start: function (event) {
                            selectElement(event.target);
                        }
                    }
                })
                .resizable({
                    edges: {
                        left: true,
                        right: true,
                        bottom: true,
                        top: true
                    },
                    listeners: {
                        start: () => isResizing = true,
                        move: function (event) {
                            const transform = getTransform(event.target);
                            let {
                                width,
                                height
                            } = event.rect;
                            width = Math.max(50, width);
                            height = Math.max(50, height);
                            event.target.style.width = width + 'px';
                            event.target.style.height = height + 'px';
                            transform.x += event.deltaRect.left;
                            transform.y += event.deltaRect.top;
                            setTransform(event.target, transform);
                        },
                        end: () => isResizing = false
                    },
                    modifiers: [
                        interact.modifiers.restrictEdges({
                            outer: '#propertyBoundary'
                        }),
                        interact.modifiers.restrictSize({
                            min: {
                                width: 50,
                                height: 50
                            }
                        })
                    ]
                });

            interact(element.querySelector('.rotate-handle')).draggable({
                listeners: {
                    start: () => isRotating = true,
                    move: function (event) {
                        const rect = element.getBoundingClientRect();
                        const centerX = rect.left + rect.width / 2;
                        const centerY = rect.top + rect.height / 2;
                        const angle = Math.atan2(event.clientY - centerY, event.clientX - centerX);
                        const transform = getTransform(element);
                        transform.rotation = (angle * 180 / Math.PI) + 90;
                        setTransform(element, transform);
                    },
                    end: () => isRotating = false
                }
            });
        }

        interact('.palette-item').draggable({
            listeners: {
                end: function (event) {
                    const boundary = document.getElementById('propertyBoundary');
                    const boundaryRect = boundary.getBoundingClientRect();

                    if (event.clientX >= boundaryRect.left && event.clientX <= boundaryRect.right &&
                        event.clientY >= boundaryRect.top && event.clientY <= boundaryRect.bottom) {

                        const item = event.target.closest('.palette-item');
                        const imageSrc = item.dataset.image;
                        const name = item.dataset.name;
                        const type = item.dataset.element;

                        const relativeX = event.clientX - boundaryRect.left - 40;
                        const relativeY = event.clientY - boundaryRect.top - 40;

                        const newElement = createElement(type, imageSrc, name, relativeX, relativeY);
                        boundary.appendChild(newElement);
                        makeElementInteractive(newElement);
                        selectElement(newElement);
                    }
                }
            }
        });

        function clearAll() {
            if (confirm('Tüm tasarımı temizlemek istediğinizden emin misiniz?')) {
                document.querySelectorAll('.landscape-element').forEach(el => el.remove());
                selectedElement = null;
                document.getElementById('deleteBtn').style.display = 'none';
            }
        }

        function deleteSelected() {
            if (selectedElement && confirm('Seçili elementi silmek istediğinizden emin misiniz?')) {
                selectedElement.remove();
                selectedElement = null;
                document.getElementById('deleteBtn').style.display = 'none';
            }
        }

        function saveDesign() {
            const elements = Array.from(document.querySelectorAll('.landscape-element')).map(el => {
                const transform = getTransform(el);
                const img = el.querySelector('img');
                return {
                    id: el.id,
                    src: img.src,
                    alt: img.alt,
                    x: transform.x,
                    y: transform.y,
                    rotation: transform.rotation,
                    width: el.style.width,
                    height: el.style.height
                };
            });

            localStorage.setItem('landscapeDesign', JSON.stringify({
                elements,
                timestamp: new Date().toISOString()
            }));
            alert('Tasarım kaydedildi! 💾');
        }

        document.getElementById('propertyBoundary').addEventListener('click', function (e) {
            if (e.target === this) {
                if (selectedElement) {
                    selectedElement.classList.remove('selected');
                    selectedElement = null;
                    document.getElementById('deleteBtn').style.display = 'none';
                }
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Delete' && selectedElement) deleteSelected();
            if (e.key === 'Escape') {
                if (selectedElement) {
                    selectedElement.classList.remove('selected');
                    selectedElement = null;
                    document.getElementById('deleteBtn').style.display = 'none';
                }
            }
        });

        function getTransform(element) {
            return {
                x: parseFloat(element.getAttribute('data-x')) || 0,
                y: parseFloat(element.getAttribute('data-y')) || 0,
                rotation: parseFloat(element.getAttribute('data-rotation')) || 0
            };
        }

        function setTransform(element, transform) {
            element.setAttribute('data-x', transform.x);
            element.setAttribute('data-y', transform.y);
            element.setAttribute('data-rotation', transform.rotation);

            element.style.transform =
                `translate(${transform.x}px, ${transform.y}px) ` +
                `rotate(${transform.rotation}deg)`;
        }

        // Element seçimi
        function selectElement(element) {
            if (selectedElement) {
                selectedElement.classList.remove('selected');
            }

            selectedElement = element;
            element.classList.add('selected');

            // Delete butonu göster
            document.getElementById('deleteBtn').style.display = 'block';
        }

        function deselectAll() {
            if (selectedElement) {
                selectedElement.classList.remove('selected');
                selectedElement = null;
            }
            document.getElementById('deleteBtn').style.display = 'none';
        }

        // Yeni element oluşturma
        function createElement(type, icon, name, x = 100, y = 100) {
            elementCounter++;
            const elementId = `element_${type}_${elementCounter}`;

            const element = document.createElement('div');
            element.id = elementId;
            element.className = 'landscape-element';
            element.style.cssText = `transform: translate(${x}px, ${y}px); width: 80px; height: 80px;`;

            // Element içeriği
            const content = document.createElement('div');
            content.className = 'element-content';
            content.innerHTML = `
				<div class="element-icon">${icon}</div>
				<div class="element-name">${name}</div>
			`;

            // Kontrol handle'ları
            const handles = ['se', 'sw', 'ne', 'nw'];
            handles.forEach(pos => {
                const handle = document.createElement('div');
                handle.className = `resize-handle resize-${pos}`;
                element.appendChild(handle);
            });

            const rotateHandle = document.createElement('div');
            rotateHandle.className = 'rotate-handle';
            element.appendChild(rotateHandle);

            element.appendChild(content);

            return element;
        }

        // Element yerleştirme
        function placeElement(element) {
            const boundary = document.getElementById('propertyBoundary');
            boundary.appendChild(element);

            // Interact.js işlevlerini etkinleştir
            makeElementInteractive(element);

            // Elementi seç
            selectElement(element);
        }

        // Interact.js işlevleri
        function makeElementInteractive(element) {
            interact(element)
                .draggable({
                    inertia: false,
                    modifiers: [
                        interact.modifiers.restrictRect({
                            restriction: '#propertyBoundary',
                            endOnly: true
                        })
                    ],
                    listeners: {
                        move: function (event) {
                            if (isResizing || isRotating) return;

                            // Doğru elementi bul
                            let targetElement = event.target;
                            if (!targetElement.classList.contains('landscape-element')) {
                                targetElement = targetElement.closest('.landscape-element');
                            }

                            const transform = getTransform(targetElement);
                            transform.x += event.dx;
                            transform.y += event.dy;
                            setTransform(targetElement, transform);
                        },
                        start: function (event) {
                            // Handle'lara tıklanmadığından emin ol
                            if (event.target.classList.contains('resize-handle') ||
                                event.target.classList.contains('rotate-handle')) {
                                return;
                            }

                            // Doğru elementi bul ve seç
                            let targetElement = event.target;
                            if (!targetElement.classList.contains('landscape-element')) {
                                targetElement = targetElement.closest('.landscape-element');
                            }

                            if (targetElement) {
                                selectElement(targetElement);
                            }
                        }
                    }
                })
                .resizable({
                    edges: {
                        left: true,
                        right: true,
                        bottom: true,
                        top: true
                    },
                    listeners: {
                        start: function (event) {
                            isResizing = true;

                            // Doğru elementi bul ve seç
                            let targetElement = event.target;
                            if (!targetElement.classList.contains('landscape-element')) {
                                targetElement = targetElement.closest('.landscape-element');
                            }

                            if (targetElement) {
                                selectElement(targetElement);
                            }
                        },
                        move: function (event) {
                            // Doğru elementi bul
                            let targetElement = event.target;
                            if (!targetElement.classList.contains('landscape-element')) {
                                targetElement = targetElement.closest('.landscape-element');
                            }

                            const transform = getTransform(targetElement);

                            let {
                                width,
                                height
                            } = event.rect;
                            width = Math.max(50, width);
                            height = Math.max(50, height);

                            targetElement.style.width = width + 'px';
                            targetElement.style.height = height + 'px';

                            transform.x += event.deltaRect.left;
                            transform.y += event.deltaRect.top;

                            setTransform(targetElement, transform);
                        },
                        end: function (event) {
                            isResizing = false;
                        }
                    },
                    modifiers: [
                        interact.modifiers.restrictEdges({
                            outer: '#propertyBoundary'
                        }),
                        interact.modifiers.restrictSize({
                            min: {
                                width: 50,
                                height: 50
                            }
                        })
                    ]
                });

            // Rotate handle
            const rotateHandle = element.querySelector('.rotate-handle');
            interact(rotateHandle)
                .draggable({
                    listeners: {
                        start: function (event) {
                            isRotating = true;
                            selectElement(element);
                            event.stopPropagation();
                        },
                        move: function (event) {
                            const transform = getTransform(element);

                            const rect = element.getBoundingClientRect();
                            const centerX = rect.left + rect.width / 2;
                            const centerY = rect.top + rect.height / 2;

                            const angle = Math.atan2(
                                event.clientY - centerY,
                                event.clientX - centerX
                            );

                            transform.rotation = (angle * 180 / Math.PI) + 90;
                            setTransform(element, transform);
                            event.stopPropagation();
                        },
                        end: function (event) {
                            isRotating = false;
                            event.stopPropagation();
                        }
                    }
                });
        }

        // Palet elemanları için drag başlatma
        interact('.palette-item')
            .draggable({
                listeners: {
                    start: function (event) {
                        const item = event.target;
                        const icon = item.dataset.icon;
                        const name = item.dataset.name;
                        const type = item.dataset.element;

                        // Drag preview oluştur
                        dragPreview = createElement(type, icon, name);
                        dragPreview.classList.add('drag-preview');
                        dragPreview.style.position = 'fixed';
                        dragPreview.style.pointerEvents = 'none';
                        dragPreview.style.zIndex = '1000';
                        document.body.appendChild(dragPreview);
                    },
                    move: function (event) {
                        if (dragPreview) {
                            dragPreview.style.left = event.clientX - 40 + 'px';
                            dragPreview.style.top = event.clientY - 40 + 'px';
                        }
                    },
                    end: function (event) {
                        if (dragPreview) {
                            document.body.removeChild(dragPreview);

                            // Arsa sınırı içinde mi kontrol et
                            const boundary = document.getElementById('propertyBoundary');
                            const boundaryRect = boundary.getBoundingClientRect();

                            if (event.clientX >= boundaryRect.left &&
                                event.clientX <= boundaryRect.right &&
                                event.clientY >= boundaryRect.top &&
                                event.clientY <= boundaryRect.bottom) {

                                // Element oluştur ve yerleştir
                                const item = event.target;
                                const relativeX = event.clientX - boundaryRect.left - 40;
                                const relativeY = event.clientY - boundaryRect.top - 40;

                                const newElement = createElement(
                                    item.dataset.element,
                                    item.dataset.icon,
                                    item.dataset.name,
                                    relativeX,
                                    relativeY
                                );

                                placeElement(newElement);
                            }

                            dragPreview = null;
                        }
                    }
                }
            });

        // Arsa sınırına tıklama - seçimi temizle
        document.getElementById('propertyBoundary').addEventListener('click', function (e) {
            // Sadece arsa sınırının kendisine tıklandığında seçimi temizle
            if (e.target === this || e.target.classList.contains('boundary-label')) {
                deselectAll();
            }
        });

        // Element content'ine tıklama event'i ekle
        document.addEventListener('click', function (e) {
            // Landscape element veya child'ına tıklandığında seç
            const landscapeElement = e.target.closest('.landscape-element');
            if (landscapeElement && !e.target.classList.contains('resize-handle') && !e.target.classList.contains('rotate-handle')) {
                selectElement(landscapeElement);
                e.stopPropagation();
            }
        });

        // Toolbar işlevleri
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
                const transform = getTransform(element);
                const content = element.querySelector('.element-content');
                const icon = content.querySelector('.element-icon').textContent;
                const name = content.querySelector('.element-name').textContent;

                elements.push({
                    id: element.id,
                    type: element.id.split('_')[1],
                    icon: icon,
                    name: name,
                    x: transform.x,
                    y: transform.y,
                    rotation: transform.rotation,
                    width: element.style.width,
                    height: element.style.height
                });
            });

            const design = {
                elements: elements,
                timestamp: new Date().toISOString()
            };

            // Local storage'a kaydet
            localStorage.setItem('landscapeDesign', JSON.stringify(design));

            // Kullanıcıya bildir
            alert('Tasarım başarıyla kaydedildi! 💾');

            // Console'a da yazdır (geliştirme için)
            console.log('Kaydedilen tasarım:', design);
        }

        // Sayfa yüklendiğinde kaydedilmiş tasarımı yükle
        document.addEventListener('DOMContentLoaded', function () {
            const savedDesign = localStorage.getItem('landscapeDesign');
            if (savedDesign) {
                try {
                    const design = JSON.parse(savedDesign);
                    design.elements.forEach(elementData => {
                        const element = createElement(
                            elementData.type,
                            elementData.icon,
                            elementData.name,
                            elementData.x,
                            elementData.y
                        );

                        if (elementData.width) element.style.width = elementData.width;
                        if (elementData.height) element.style.height = elementData.height;

                        const transform = {
                            x: elementData.x,
                            y: elementData.y,
                            rotation: elementData.rotation
                        };
                        setTransform(element, transform);

                        placeElement(element);
                    });
                    console.log('Kaydedilmiş tasarım yüklendi:', design);
                } catch (e) {
                    console.log('Kaydedilmiş tasarım yüklenirken hata oluştu:', e);
                }
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
    </script>
@endpush
