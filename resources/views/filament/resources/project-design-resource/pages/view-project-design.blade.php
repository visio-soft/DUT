<x-filament-panels::page>
    <div class="landscape-designer-wrapper">
        <div class="landscape-studio">
            <div class="design-area">
                <div class="property-boundary" id="propertyBoundary">
                    <!-- Elements will be placed here -->
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --fd-bg: #f8fafc;
            --fd-panel-bg: #ffffff;
            --fd-border: #e5e7eb;
            --fd-text: #374151;
            --fd-accent: #10b981;
            --fd-accent-2: #64614eff;
            --fd-muted-white: rgba(255,255,255,0.95);
            --fd-bg-image-opacity: 1;
            --fd-element-bg: rgba(255,255,255,0.9);
            --fd-name-bg: rgba(255,255,255,0.85);
            --fd-name-text: #374151;
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
            min-height: 600px;
        }

        .design-area {
            width: 100%;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            background: transparent;
        }

        .property-boundary {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: none;
            border-radius: 0;
            overflow: hidden;
            @if($projectBackgroundImage)
            background-image: url('{{ $projectBackgroundImage }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            @else
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            @endif
        }

        .landscape-element {
            position: absolute;
            border: 3px solid transparent;
            border-radius: 4px;
            transition: all 0.2s ease;
            z-index: 10;
            cursor: pointer;
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
            background: transparent; /* fully transparent so background image shows through */
            position: relative;
            border-radius: 0; /* let images be flush with element bounds */
            padding: 0; /* remove padding so image fills the element */
            box-shadow: none; /* remove shadow */
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

        .element-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .stats-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .stat-box {
            flex: 1;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: var(--fd-accent);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: #16a34a;
            margin-top: 4px;
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
            console.log('🎨 Design Viewer: Initializing...');

            // Backend'den gelen veriler
            const designData = @json($designData);
            const objectData = @json($objectData);
            const projectBackgroundImage = @json($projectBackgroundImage);

            console.log('📊 Design Data:', designData);
            console.log('🎯 Object Data:', objectData);
            console.log('🖼️ Project Background:', projectBackgroundImage);

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
                    image_url: `https://picsum.photos/120/120?random=${objeId}`
                };
                console.log(`🔍 Getting object info for ID ${objeId}:`, objectInfo);
                return objectInfo;
            }

            function createElement(elementData, index) {
                const objInfo = getObjectInfo(elementData.obje_id);
                console.log(`🎯 Creating element ${index} with data:`, {elementData, objInfo});

                const element = document.createElement('div');
                element.className = 'landscape-element';
                element.id = `element_${index}`;

                // Set position and size
                element.style.left = elementData.x + 'px';
                element.style.top = elementData.y + 'px';
                element.style.width = elementData.width + 'px';
                element.style.height = elementData.height + 'px';

                // Create content
                const content = document.createElement('div');
                content.className = 'element-content';

                // Build inner content without opaque backgrounds. Only show name badge when available.
                content.innerHTML = ``;

                // If name exists and is not null/empty string, add a small badge
                if (objInfo.name && objInfo.name !== 'null') {
                    const labelWrap = document.createElement('div');
                    labelWrap.className = 'element-label';
                    const nameEl = document.createElement('div');
                    nameEl.className = 'element-name';
                    nameEl.textContent = objInfo.name;
                    labelWrap.appendChild(nameEl);
                    content.appendChild(labelWrap);
                }

                // Add image element (fills the element)
                const imgEl = document.createElement('img');
                imgEl.className = 'element-image';
                imgEl.src = objInfo.image_url || (`https://picsum.photos/120/120?random=${elementData.obje_id}`);
                imgEl.alt = objInfo.name || `Obje ${elementData.obje_id}`;
                imgEl.onerror = function() { this.src = `https://picsum.photos/120/120?random=${elementData.obje_id}`; };
                content.appendChild(imgEl);

                element.appendChild(content);

                // Add hover effect with element info
                element.title = `${objInfo.name} - ${elementData.width}x${elementData.height}px`;

                console.log(`✅ Element ${index} created successfully`);
                return element;
            }

            function loadDesign(data) {
                console.log('🎨 Loading design data:', data);

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
                console.log('🖼️ Setting project background image:', projectBackgroundImage);
                const boundary = document.getElementById('propertyBoundary');
                if (boundary) {
                    boundary.style.backgroundImage = `url('${projectBackgroundImage}')`;
                    boundary.style.backgroundSize = 'cover';
                    boundary.style.backgroundPosition = 'center';
                    boundary.style.backgroundRepeat = 'no-repeat';
                    console.log('✅ Background image applied to boundary');
                }
            } else {
                console.log('⚠️ No project background image available');
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
