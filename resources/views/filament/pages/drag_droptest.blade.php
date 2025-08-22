<x-filament-panels::page>

	
	<div class="landscape-designer-wrapper">	
		@push('styles')
		<style>
			/* Ana container */
			.landscape-studio {
				display: flex;
				gap: 20px;
				height: 80vh;
				font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			}

		/* Element paleti */
		.element-palette {
			width: 250px;
			background: white;
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			padding: 20px;
			overflow-y: auto;
		}

		.palette-title {
			font-size: 18px;
			font-weight: 600;
			color: #1f2937;
			margin-bottom: 16px;
			text-align: center;
		}

		.element-category {
			margin-bottom: 20px;
		}

		.category-title {
			font-size: 14px;
			font-weight: 500;
			color: #6b7280;
			margin-bottom: 8px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.palette-item {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px;
			margin-bottom: 8px;
			background: #f9fafb;
			border: 2px solid transparent;
			border-radius: 8px;
			cursor: grab;
			transition: all 0.2s ease;
		}

		.palette-item:hover {
			background: #f3f4f6;
			border-color: #10b981;
			transform: translateY(-1px);
		}

		.palette-item:active {
			cursor: grabbing;
		}

		.palette-icon {
			font-size: 24px;
			width: 32px;
			text-align: center;
		}

		.palette-label {
			font-size: 13px;
			font-weight: 500;
			color: #374151;
		}

		/* Tasarım alanı */
		.design-area {
			flex: 1;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border-radius: 12px;
			position: relative;
			overflow: hidden;
		}

		/* Arsa sınırı */
		.property-boundary {
			position: absolute;
			top: 50px;
			left: 50px;
			right: 50px;
			bottom: 50px;
			background: #22c55e;
			border: 4px dashed #16a34a;
			border-radius: 16px;
			position: relative;
			overflow: hidden;
			height: 90%;
			width: 90%;
		}

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
			background: rgba(255,255,255,0.9);
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
			pointer-events: none; /* Child elementlerin drag'i engellemesini önle */
		}

		.landscape-element .resize-handle,
		.landscape-element .rotate-handle {
			pointer-events: auto; /* Handle'ların tıklanabilir olmasını sağla */
		}

		.element-content {
			width: 100%;
			height: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			background: rgba(255,255,255,0.95);
			border-radius: 6px;
			padding: 8px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

		.resize-se { bottom: -6px; right: -6px; cursor: se-resize; }
		.resize-sw { bottom: -6px; left: -6px; cursor: sw-resize; }
		.resize-ne { top: -6px; right: -6px; cursor: ne-resize; }
		.resize-nw { top: -6px; left: -6px; cursor: nw-resize; }

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

		.rotate-handle:active { cursor: grabbing; }

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
			background: rgba(255,255,255,0.95);
			border: 1px solid #e5e7eb;
			border-radius: 8px;
			font-size: 13px;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.2s ease;
		}

		.toolbar-btn:hover {
			background: white;
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
		</style>
		@endpush

		<div class="landscape-studio">
			<!-- Element Paleti -->
			<div class="element-palette">
				<h3 class="palette-title">🏡 Tasarım Öğeleri</h3>			<div class="element-category">
				<div class="category-title">Ağaçlar & Bitkiler</div>
				<div class="palette-item" data-element="tree" data-icon="🌳" data-name="Ağaç">
					<span class="palette-icon">🌳</span>
					<span class="palette-label">Ağaç</span>
				</div>
				<div class="palette-item" data-element="tree2" data-icon="🌲" data-name="Çam Ağacı">
					<span class="palette-icon">🌲</span>
					<span class="palette-label">Çam Ağacı</span>
				</div>
				<div class="palette-item" data-element="flower" data-icon="🌸" data-name="Çiçek">
					<span class="palette-icon">🌸</span>
					<span class="palette-label">Çiçek</span>
				</div>
				<div class="palette-item" data-element="bush" data-icon="🌿" data-name="Çalı">
					<span class="palette-icon">🌿</span>
					<span class="palette-label">Çalı</span>
				</div>
			</div>

			<div class="element-category">
				<div class="category-title">Dekoratif Öğeler</div>
				<div class="palette-item" data-element="pot" data-icon="🪴" data-name="Saksı">
					<span class="palette-icon">🪴</span>
					<span class="palette-label">Saksı</span>
				</div>
				<div class="palette-item" data-element="fountain" data-icon="⛲" data-name="Çeşme">
					<span class="palette-icon">⛲</span>
					<span class="palette-label">Çeşme</span>
				</div>
				<div class="palette-item" data-element="stone" data-icon="🪨" data-name="Taş">
					<span class="palette-icon">🪨</span>
					<span class="palette-label">Taş</span>
				</div>
				<div class="palette-item" data-element="bench" data-icon="🪑" data-name="Bank">
					<span class="palette-icon">🪑</span>
					<span class="palette-label">Bank</span>
				</div>
			</div>

			<div class="element-category">
				<div class="category-title">Su Öğeleri</div>
				<div class="palette-item" data-element="pool" data-icon="🏊‍♂️" data-name="Havuz">
					<span class="palette-icon">🏊‍♂️</span>
					<span class="palette-label">Havuz</span>
				</div>
				<div class="palette-item" data-element="pond" data-icon="🐟" data-name="Gölet">
					<span class="palette-icon">🐟</span>
					<span class="palette-label">Gölet</span>
				</div>
			</div>

			<div class="element-category">
				<div class="category-title">Yapılar</div>
				<div class="palette-item" data-element="gazebo" data-icon="🏛️" data-name="Çardak">
					<span class="palette-icon">🏛️</span>
					<span class="palette-label">Çardak</span>
				</div>
				<div class="palette-item" data-element="fence" data-icon="🚧" data-name="Çit">
					<span class="palette-icon">🚧</span>
					<span class="palette-label">Çit</span>
				</div>
			</div>
		</div>

		<!-- Tasarım Alanı -->
		<div class="design-area">
			<div class="toolbar">
				<button class="toolbar-btn" onclick="clearAll()">🗑️ Temizle</button>
				<button class="toolbar-btn" onclick="saveDesign()">💾 Kaydet</button>
				<button class="toolbar-btn delete" onclick="deleteSelected()" id="deleteBtn" style="display: none;">❌ Sil</button>
			</div>

				<div class="property-boundary" id="propertyBoundary">
					<div class="boundary-label">🏡 Arsa Sınırı</div>
				</div>
			</div>
		</div>

		<!-- interact.js CDN -->
		@push('scripts')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.27/interact.min.js" integrity="sha512-U7pxMz47PpFp2XkN2CrzPoILVGEwb6dN0o0/gcz6wserNSwnprr4YoVd5QHHWHVVSA+5gCxeh0yZDVy28T91nQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		let selectedElement = null;
		let elementCounter = 0;
		let isResizing = false;
		let isRotating = false;
		let dragPreview = null;

		// Element transform yönetimi
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
						move: function(event) {
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
						start: function(event) {
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
					edges: { left: true, right: true, bottom: true, top: true },
					listeners: {
						start: function(event) {
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
						move: function(event) {
							// Doğru elementi bul
							let targetElement = event.target;
							if (!targetElement.classList.contains('landscape-element')) {
								targetElement = targetElement.closest('.landscape-element');
							}
							
							const transform = getTransform(targetElement);
							
							let { width, height } = event.rect;
							width = Math.max(50, width);
							height = Math.max(50, height);
							
							targetElement.style.width = width + 'px';
							targetElement.style.height = height + 'px';
							
							transform.x += event.deltaRect.left;
							transform.y += event.deltaRect.top;
							
							setTransform(targetElement, transform);
						},
						end: function(event) {
							isResizing = false;
						}
					},
					modifiers: [
						interact.modifiers.restrictEdges({
							outer: '#propertyBoundary'
						}),
						interact.modifiers.restrictSize({
							min: { width: 50, height: 50 }
						})
					]
				});

			// Rotate handle
			const rotateHandle = element.querySelector('.rotate-handle');
			interact(rotateHandle)
				.draggable({
					listeners: {
						start: function(event) {
							isRotating = true;
							selectElement(element);
							event.stopPropagation();
						},
						move: function(event) {
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
						end: function(event) {
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
					start: function(event) {
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
					move: function(event) {
						if (dragPreview) {
							dragPreview.style.left = event.clientX - 40 + 'px';
							dragPreview.style.top = event.clientY - 40 + 'px';
						}
					},
					end: function(event) {
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
		document.getElementById('propertyBoundary').addEventListener('click', function(e) {
			// Sadece arsa sınırının kendisine tıklandığında seçimi temizle
			if (e.target === this || e.target.classList.contains('boundary-label')) {
				deselectAll();
			}
		});

		// Element content'ine tıklama event'i ekle
		document.addEventListener('click', function(e) {
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
		document.addEventListener('DOMContentLoaded', function() {
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
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Delete' && selectedElement) {
				deleteSelected();
			}
			if (e.key === 'Escape') {
				deselectAll();
			}
		});
		</script>
		@endpush
	</div>
</x-filament-panels::page>
