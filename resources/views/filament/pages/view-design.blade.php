<div id="filament-viewer-root">
<x-filament-panels::page>
    <div>
        <style>
            /* Reuse the same styles as drag-drop-test but with some modifications for view-only mode */
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
                --fd-bg-image-opacity: 1;
                --fd-element-bg: rgba(0, 0, 0, 0);
                --fd-name-bg: rgba(255,255,255,0.85);
                --fd-name-text: #ffffffff;
                --fd-danger: var(--filament-danger, #dc2626);
                --fd-danger-bg: var(--filament-danger-100, #fee2e2);
                --fd-info: var(--filament-info, #0ea5e9);
            }

            /* Support Tailwind/Filament dark class */
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
            }

            .project-objects-panel {
                width: 250px;
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
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                z-index: 25;
            }

            /* View-only landscape elements (no interactions) */
            .landscape-element {
                position: absolute;
                user-select: none;
                touch-action: none;
                border: 3px solid transparent;
                border-radius: 4px;
                z-index: 10;
                width: 120px;
                height: 120px;
                /* Remove cursor styles since this is view-only */
                cursor: default;
            }

            .element-content {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: transparent !important;
                position: relative;
                border-radius: 6px;
                padding: 8px;
                box-shadow: none !important;
            }

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
                background: transparent !important;
                filter: none !important;
                -webkit-filter: none !important;
                box-shadow: none !important;
            }

            /* Project objects panel styles */
            .object-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 12px;
                margin: 8px 0;
                background: var(--fd-bg);
                border-radius: 8px;
                border: 1px solid var(--fd-border);
            }

            .object-image {
                width: 40px;
                height: 40px;
                border-radius: 6px;
                object-fit: cover;
            }

            .object-item span {
                font-weight: 500;
                color: var(--fd-text);
            }

            .object-count {
                margin-left: auto;
                background: var(--fd-accent);
                color: white;
                padding: 2px 6px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
            }

            /* Empty state */
            .empty-objects {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 18px;
                border-radius: 10px;
                background: color-mix(in srgb, var(--fd-panel-bg) 85%, transparent);
                border: 1px dashed var(--fd-border);
                color: var(--fd-text);
            }

            .empty-objects .empty-icon {
                font-size: 34px;
                width: 56px;
                height: 56px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                background: color-mix(in srgb, var(--fd-accent) 10%, transparent);
            }

            .empty-objects .empty-content h3 {
                margin: 0 0 6px 0;
                font-size: 15px;
                font-weight: 700;
                color: var(--fd-text);
            }

            .empty-objects .empty-content p {
                margin: 0;
                font-size: 13px;
                color: color-mix(in srgb, var(--fd-text) 70%, transparent);
            }

            /* Responsive */
            @media (max-width: 1250px) {
                .landscape-studio {
                    flex-direction: column;
                    height: auto;
                    max-width: 100%;
                    padding: 10px;
                }

                .design-area {
                    width: 100%;
                    height: 500px;
                    min-width: 300px;
                    min-height: 500px;
                }
            }

            @media (max-width: 768px) {
                .landscape-studio {
                    flex-direction: column;
                    height: auto;
                    padding: 5px;
                }

                .design-area {
                    width: 100%;
                    height: 400px;
                    min-width: 280px;
                    min-height: 400px;
                }

                .project-objects-panel {
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
                <!-- Project Objects Panel - shows only objects used in this project -->
                <div class="project-objects-panel">
                    <h3 style="margin:0 0 20px 0; color: var(--fd-text); font-weight: 600;">Projede Kullanılan Objeler</h3>
                    
                    <div id="projectObjectsList">
                        @if($project_objects && count($project_objects) > 0)
                            @foreach($project_objects as $object)
                                <div class="object-item">
                                    <img src="{{ $object['image_url'] ?: 'https://picsum.photos/40/40?random=' . $object['id'] }}"
                                         alt="{{ $object['name'] }}"
                                         class="object-image">
                                    <span>{{ $object['name'] }}</span>
                                    <span class="object-count">{{ $object['count'] }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-objects">
                                <div class="empty-icon" aria-hidden="true">📦</div>
                                <div class="empty-content">
                                    <h3>Obje Bulunamadı</h3>
                                    <p>Bu projede henüz hiç obje kullanılmamış.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Design Area - view-only -->
                <div class="design-area">
                    <div class="property-boundary" id="propertyBoundary">
                        <div class="boundary-label">Sadece Görüntüleme</div>
                        
                        <!-- Background image container -->
                        <div class="background-image-container" id="backgroundImageContainer">
                            <!-- Project image will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let designElements = []; // Read-only design elements

                function loadBackgroundImage(projectImage) {
                    console.log('🖼️ [BACKGROUND] Loading background image:', projectImage);

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

                    console.log('🔗 [BACKGROUND] Corrected image src:', imageSrc);

                    img.src = imageSrc;
                    img.className = 'background-image';
                    img.alt = 'Project Background Image';

                    img.onload = function() {
                        console.log('✅ [BACKGROUND] Project background image loaded successfully:', imageSrc);
                    };

                    img.onerror = function() {
                        console.error('❌ [BACKGROUND] Background image failed to load:', imageSrc);
                        // Use gradient background on error
                        backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                        console.log('🎨 [BACKGROUND] Using gradient background');
                    };

                    backgroundContainer.appendChild(img);
                }

                function createViewOnlyElement(type, imageUrl, name, x = 0, y = 0, width = 120, height = 120) {
                    console.log('🏗️ [CREATE VIEW] Creating view-only element:', { type, name, x, y, width, height });

                    const element = document.createElement('div');
                    element.className = 'landscape-element';
                    element.style.transform = `translate(${x}px, ${y}px)`;
                    element.style.width = width + 'px';
                    element.style.height = height + 'px';

                    const content = document.createElement('div');
                    content.className = 'element-content';

                    // Normalize image src
                    const imgSrc = normalizeImageSrc(imageUrl);

                    // Create image element
                    const img = document.createElement('img');
                    img.src = imgSrc || '/images/default-object.png';
                    img.alt = name || '';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';

                    img.onerror = function () {
                        console.error('❌ [IMAGE] View element image failed to load, using placeholder:', img.src);
                        img.src = '/images/default-object.png';
                    };

                    // Add name label if provided
                    if (name && name !== 'null') {
                        const labelWrap = document.createElement('div');
                        labelWrap.className = 'element-label';
                        const nameEl = document.createElement('div');
                        nameEl.className = 'element-name';
                        nameEl.textContent = name;
                        labelWrap.appendChild(nameEl);
                        content.appendChild(labelWrap);
                    }

                    content.appendChild(img);
                    element.appendChild(content);

                    console.log('✅ [CREATE VIEW] View-only element created successfully');
                    return element;
                }

                function normalizeImageSrc(imageSrc) {
                    if (!imageSrc) return null;
                    let src = imageSrc;
                    if (!src.startsWith('http') && !src.startsWith('/')) {
                        if (!src.startsWith('storage/')) {
                            src = 'storage/' + src;
                        }
                        src = '/' + src;
                    }
                    return src;
                }

                function loadExistingDesignViewOnly(designData) {
                    console.log('📂 [LOAD VIEW DESIGN] Loading existing design for viewing:', designData);

                    if (designData.elements && designData.elements.length > 0) {
                        console.log('📦 [LOAD VIEW DESIGN] Element count:', designData.elements.length);

                        const boundary = document.getElementById('propertyBoundary');

                        designData.elements.forEach((elementData, index) => {
                            console.log(`   🏗️ [LOAD VIEW DESIGN] Loading element ${index + 1}:`, elementData);

                            // Get position information
                            let x = 0, y = 0, width = 120, height = 120;

                            // Check new format (location and scale)
                            if (elementData.location && typeof elementData.location === 'object') {
                                x = elementData.location.x || 0;
                                y = elementData.location.y || 0;

                                if (elementData.scale && typeof elementData.scale === 'object') {
                                    width = (elementData.scale.x || 1) * 120;
                                    height = (elementData.scale.y || 1) * 120;
                                }
                            }
                            // Check old format
                            else if (elementData.x !== undefined && elementData.y !== undefined) {
                                x = elementData.x || 0;
                                y = elementData.y || 0;
                                width = elementData.width || 120;
                                height = elementData.height || 120;
                            }

                            const imageUrl = elementData.image_url || null;
                            const name = elementData.name || '';

                            const element = createViewOnlyElement(
                                'custom',
                                imageUrl,
                                name,
                                x,
                                y,
                                width,
                                height
                            );

                            boundary.appendChild(element);

                            console.log(`   ✅ [LOAD VIEW DESIGN] Element ${index + 1} loaded successfully: x=${x}, y=${y}`);
                        });

                        console.log('✅ [LOAD VIEW DESIGN] Existing design loaded successfully for viewing:', designData.elements.length + ' elements');
                    } else {
                        console.log('⚠️ [LOAD VIEW DESIGN] No elements found to load');
                    }
                }

                // Initialize the view-only design viewer
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('🚀 [INIT VIEW] Page loaded - initializing view-only mode...');

                    // Get data from PHP
                    const projectId = @json($project_id ?? null);
                    const projectImage = @json($project_image ?? null);
                    const existingDesign = @json($existing_design ?? null);

                    console.log('📊 [INIT VIEW] Data from PHP:');
                    console.log('   🆔 Project ID:', projectId);
                    console.log('   🖼️ Project Image:', projectImage);
                    console.log('   🎨 Existing Design:', existingDesign);

                    // Load background image
                    if (projectImage) {
                        console.log('🖼️ [INIT VIEW] Loading background image:', projectImage);
                        loadBackgroundImage(projectImage);
                    } else {
                        console.log('🎨 [INIT VIEW] No project image - using gradient background');
                        const backgroundContainer = document.getElementById('backgroundImageContainer');
                        backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                    }

                    // Load existing design for viewing
                    if (existingDesign && existingDesign.elements) {
                        console.log('📂 [INIT VIEW] Loading existing design:', existingDesign.elements.length + ' elements');
                        loadExistingDesignViewOnly(existingDesign);
                    } else {
                        console.log('🆕 [INIT VIEW] No existing design to display');
                    }

                    console.log('✅ [INIT VIEW] View-only mode initialization completed');
                });
            </script>
        </div>
    </div>
</x-filament-panels::page>
</div>