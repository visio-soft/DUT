<x-filament-panels::page>
    <div class="landscape-designer-wrapper">
        <div class="landscape-studio">
            <div class="design-area">
                <div class="property-boundary" id="propertyBoundary">
                    <div class="background-image-container" id="backgroundImageContainer">
                        <!-- Background image will be loaded here -->
                    </div>
                    <!-- Elements will be placed here -->
                </div>
            </div>
        </div>
    </div>

    <style>
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
            height: 700px;
            min-height: 700px;
            max-height: 700px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            justify-content: center;
        }

        .design-area {
            width: 800px;
            height: 700px;
            min-width: 800px;
            min-height: 700px;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            background: transparent;
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
            opacity: var(--fd-bg-image-opacity, 1);
            border-radius: 12px;
            filter: none;
            -webkit-filter: none;
        }

        /* Yerleştirilen öğeler */
        .landscape-element {
            position: absolute;
            cursor: pointer;
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

        .element-content {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* Make element container transparent so transparent images render correctly */
            background: transparent !important;
            position: relative;
            border-radius: 6px;
            padding: 8px;
            /* remove inner shadow */
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
            /* remove overlay and shadow to avoid covering transparent images */
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
            /* ensure the actual image's transparency is preserved */
            background: transparent !important;
            filter: none !important;
            -webkit-filter: none !important;
            box-shadow: none !important;
        }

        .element-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .landscape-studio {
                height: auto;
            }

            .design-area {
                height: 70vh;
                min-height: 500px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Backend'den gelen veriler
            const designData = @json($designData);
            const objectData = @json($objectData);
            const projectBackgroundImage = @json($projectBackgroundImage);

            function formatTimestamp(timestamp) {
                if (!timestamp) return '-';
                const date = new Date(timestamp);
                return date.toLocaleString('tr-TR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function getObjectInfo(objeId) {
                const objectInfo = objectData[objeId] || {
                    id: objeId,
                    name: `Obje ${objeId}`,
                    isim: `Obje ${objeId}`, // Türkçe alan da ekle
                    image_url: `https://picsum.photos/120/120?random=${objeId}`
                };
                return objectInfo;
            }

            function createElement(elementData, index) {
                const objInfo = getObjectInfo(elementData.obje_id);

                const element = document.createElement('div');
                element.className = 'landscape-element';
                element.id = `element_${index}`;

                // Pozisyon ve boyut belirleme - yeni format öncelikli
                let x = 0, y = 0, width = 120, height = 120;

                // Yeni format kontrol et (location ve scale)
                if (elementData.location && typeof elementData.location === 'object') {
                    x = elementData.location.x || 0;
                    y = elementData.location.y || 0;

                    if (elementData.scale && typeof elementData.scale === 'object') {
                        width = (elementData.scale.x || 1) * 120;
                        height = (elementData.scale.y || 1) * 120;
                    }
                }
                // Eski format kontrol et
                else if (elementData.x !== undefined && elementData.y !== undefined) {
                    x = elementData.x || 0;
                    y = elementData.y || 0;
                    width = elementData.width || 120;
                    height = elementData.height || 120;
                }

                // Transform kullanarak pozisyon ayarla (drag-drop-test ile aynı)
                element.style.transform = `translate(${x}px, ${y}px)`;
                element.style.width = width + 'px';
                element.style.height = height + 'px';

                // Create content
                const content = document.createElement('div');
                content.className = 'element-content';

                // If name exists and is not null/empty string, add a small badge
                // Hem name hem isim alanını kontrol et (drag-drop-test ile uyumlu)
                const displayName = objInfo.isim || objInfo.name || '';
                if (displayName && displayName !== 'null') {
                    const labelWrap = document.createElement('div');
                    labelWrap.className = 'element-label';
                    const nameEl = document.createElement('div');
                    nameEl.className = 'element-name';
                    nameEl.textContent = displayName;
                    labelWrap.appendChild(nameEl);
                    content.appendChild(labelWrap);
                }

                // Add image element (fills the element)
                const imgEl = document.createElement('img');
                imgEl.className = 'element-image';
                imgEl.src = objInfo.image_url || (`https://picsum.photos/120/120?random=${elementData.obje_id}`);
                imgEl.alt = displayName || `Obje ${elementData.obje_id}`;
                imgEl.onerror = function() { this.src = `https://picsum.photos/120/120?random=${elementData.obje_id}`; };
                content.appendChild(imgEl);

                element.appendChild(content);

                // Add hover effect with element info
                element.title = `${displayName || 'Obje'} - ${width}x${height}px - (${x}, ${y})`;

                console.log(`✅ Element ${index} created successfully at (${x}, ${y}) with size ${width}x${height}`);
                return element;
            }

            function loadDesign(data) {

                // Clear existing elements
                const boundary = document.getElementById('propertyBoundary');

                // Remove existing elements (keep boundary label)
                boundary.querySelectorAll('.landscape-element').forEach(el => el.remove());

                // Load elements
                if (data.elements && data.elements.length > 0) {
                    data.elements.forEach((elementData, index) => {
                        // Create visual element
                        const visualElement = createElement(elementData, index);
                        boundary.appendChild(visualElement);
                    });

                    console.log(`✅ Loaded ${data.elements.length} elements`);
                } else {
                    console.log('⚠️ No elements found in design');
                }
            }

            // Initialize design
            loadDesign(designData);

            // Set project background if available
            if (projectBackgroundImage) {
                loadBackgroundImage(projectBackgroundImage);
            } else {
                const backgroundContainer = document.getElementById('backgroundImageContainer');
                if (backgroundContainer) {
                    backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                }
            }

            function loadBackgroundImage(projectImage) {

                const backgroundContainer = document.getElementById('backgroundImageContainer');
                const img = document.createElement('img');

                // Fix image path
                let imageSrc = projectImage;
                if (!imageSrc.startsWith('http') && !imageSrc.startsWith('/')) {
                    if (!imageSrc.startsWith('storage/')) {
                        imageSrc = 'storage/' + imageSrc;
                    }
                    imageSrc = '/' + imageSrc;
                }

                img.src = imageSrc;
                img.className = 'background-image';
                img.alt = 'Project Background Image';

                img.onload = function() {
                    console.log('[BACKGROUND] Project background image loaded successfully:', imageSrc);
                };

                img.onerror = function() {
                    console.error('❌ [BACKGROUND] Background image failed to load:', imageSrc);
                    // Fallback to gradient
                    backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                    console.log('Using gradient background as fallback');
                };

                backgroundContainer.appendChild(img);
            }

            // Add global click handler to remove highlights when clicking outside elements
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.landscape-element')) {
                    document.querySelectorAll('.landscape-element').forEach(el => {
                        el.style.borderColor = 'transparent';
                        el.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
                    });
                }
            });

            console.log('✅ Design Viewer: Initialized successfully');
        });
    </script>
</x-filament-panels::page>
