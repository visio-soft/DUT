<script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.27/interact.min.js"></script>
<script>
// Global değişkenler - anlık veri takibi için
window.designState = {
    elements: new Map(), // elementId -> elementData mapping
    projectId: null,
    objeler: [],
    project: null,
    isDirty: false, // Değişiklik takibi
    lastSaveTime: null
};

// Element verilerini takip eden sınıf
class ElementTracker {
    constructor(elementId, objeId, x = 0, y = 0, width = 120, height = 120) {
        this.elementId = elementId;
        this.objeId = parseInt(objeId);
        this.location = { x: x, y: y };
        this.scale = { 
            x: width / 120,  // 120 = varsayılan boyut
            y: height / 120 
        };
        this.originalSize = { width: 120, height: 120 };
        this.lastUpdated = Date.now();
    }

    updateLocation(x, y) {
        this.location = { x: parseFloat(x), y: parseFloat(y) };
        this.lastUpdated = Date.now();
        window.designState.isDirty = true;
        console.log(`Element ${this.elementId} konum güncellendi:`, this.location);
    }

    updateScale(width, height) {
        this.scale = {
            x: parseFloat(width) / this.originalSize.width,
            y: parseFloat(height) / this.originalSize.height
        };
        this.lastUpdated = Date.now();
        window.designState.isDirty = true;
        console.log(`Element ${this.elementId} boyut güncellendi:`, this.scale);
    }

    toJSON() {
        return {
            obje_id: this.objeId,
            location: this.location,
            scale: this.scale
        };
    }
}

// Geliştirilmiş createElement fonksiyonu
function createElement(type, imageUrl, name, x = 0, y = 0, objeId = null) {
    elementCounter++;
    const elementId = `element_${type}_${elementCounter}`;
    const element = document.createElement('div');
    element.id = elementId;
    element.className = 'landscape-element';
    element.style.transform = `translate(${x}px, ${y}px)`;
    
    // Element tracking'i başlat
    if (objeId) {
        element.setAttribute('data-obje-id', objeId);
        const tracker = new ElementTracker(elementId, objeId, x, y, 120, 120);
        window.designState.elements.set(elementId, tracker);
    }

    const content = document.createElement('div');
    content.className = 'element-content';
    
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

// Geliştirilmiş makeElementInteractive fonksiyonu
function makeElementInteractive(element) {
    const elementId = element.id;
    
    // Draggable - anlık konum takibi
    interact(element).draggable({
        listeners: {
            start(event) {
                selectElement(event.target);
                console.log(`Sürükleme başladı: ${elementId}`);
            },
            move(event) {
                const target = event.target;
                const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                target.style.transform = `translate(${x}px, ${y}px)`;
                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);

                // Anlık konum güncellemesi
                const tracker = window.designState.elements.get(elementId);
                if (tracker) {
                    tracker.updateLocation(x, y);
                }
            },
            end(event) {
                console.log(`Sürükleme bitti: ${elementId}`);
                // Otomatik kaydetme tetikle (debounced)
                scheduleAutoSave();
            }
        }
    });

    // Resizable - anlık boyut takibi
    interact(element).resizable({
        edges: { left: true, right: true, bottom: true, top: true },
        listeners: {
            start(event) {
                selectElement(event.target);
                console.log(`Boyutlandırma başladı: ${elementId}`);
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

                // Anlık güncelleme
                const tracker = window.designState.elements.get(elementId);
                if (tracker) {
                    tracker.updateLocation(x, y);
                    tracker.updateScale(width, height);
                }
            },
            end(event) {
                console.log(`Boyutlandırma bitti: ${elementId}`);
                // Otomatik kaydetme tetikle (debounced)
                scheduleAutoSave();
            }
        },
        modifiers: [
            interact.modifiers.restrictSize({
                min: { width: 50, height: 50 }
            })
        ]
    });
}

// Debounced otomatik kaydetme
let autoSaveTimeout = null;
function scheduleAutoSave() {
    if (autoSaveTimeout) {
        clearTimeout(autoSaveTimeout);
    }
    
    autoSaveTimeout = setTimeout(() => {
        if (window.designState.isDirty) {
            console.log('Otomatik kaydetme tetiklendi...');
            collectAndSaveDesignData();
        }
    }, 2000); // 2 saniye sonra kaydet
}

// Element silme tracking'i
function deleteElement(element) {
    if (element && confirm('Seçili elementi silmek istediğinizden emin misiniz?')) {
        const elementId = element.id;
        
        // Tracking'den kaldır
        window.designState.elements.delete(elementId);
        window.designState.isDirty = true;
        
        element.remove();
        deselectAll();
        
        console.log(`Element silindi: ${elementId}`);
        scheduleAutoSave();
    }
}

// Gerçek zamanlı tasarım durumu alma
function getCurrentDesignState() {
    const elements = [];
    
    window.designState.elements.forEach((tracker, elementId) => {
        elements.push(tracker.toJSON());
    });
    
    return {
        project_id: window.designState.projectId,
        elements: elements,
        timestamp: new Date().toISOString(),
        total_elements: elements.length,
        last_update: window.designState.isDirty ? Date.now() : window.designState.lastSaveTime
    };
}

// Geliştirilmiş veri kaydetme
function collectAndSaveDesignData() {
    console.log('Tasarım verileri toplanıyor (tracking\'den)...');
    
    const design = getCurrentDesignState();
    
    console.log('Anlık tasarım durumu:', design);
    console.log('Toplam takip edilen element:', window.designState.elements.size);

    // Livewire metodunu çağır
    if (window.Livewire && window.$wire) {
        try {
            window.$wire.call('storeDesignData', design);
            window.designState.isDirty = false;
            window.designState.lastSaveTime = Date.now();
            console.log('Tasarım verileri başarıyla kaydedildi');
        } catch (error) {
            console.error('Livewire çağrısı başarısız:', error);
            tryLivewireCall(design);
        }
    } else {
        console.warn('Livewire bulunamadı, fallback yöntemleri deneniyor...');
        tryLivewireCall(design);
    }
}

// Mevcut tasarımı yüklerken tracking başlat
function loadExistingDesign(designData) {
    if (designData.elements && designData.elements.length > 0) {
        const boundary = document.getElementById('propertyBoundary');
        
        designData.elements.forEach((elementData, index) => {
            let obje = null;
            let x = 0, y = 0, width = 120, height = 120;
            let imageUrl = '', name = '';
            
            // Yeni format kontrol et
            if (elementData.obje_id && elementData.location && elementData.scale) {
                obje = window.designState.objeler.find(o => o.id == elementData.obje_id);
                
                if (!obje) {
                    console.warn('Obje bulunamadı:', elementData.obje_id);
                    return;
                }
                
                const originalWidth = 120;
                const originalHeight = 120;
                width = (elementData.scale.x || 1) * originalWidth;
                height = (elementData.scale.y || 1) * originalHeight;
                
                x = elementData.location.x || 0;
                y = elementData.location.y || 0;
                
                imageUrl = obje.image_url || ('https://picsum.photos/80/80?random=' + obje.id);
                name = obje.isim;
            } 
            // Eski format kontrol et
            else if (elementData.x !== undefined && elementData.y !== undefined) {
                x = elementData.x || 0;
                y = elementData.y || 0;
                width = elementData.width || 120;
                height = elementData.height || 120;
                imageUrl = elementData.image_url || '';
                name = elementData.name || '';
                
                if (elementData.obje_id) {
                    obje = window.designState.objeler.find(o => o.id == elementData.obje_id);
                }
            } else {
                console.warn('Geçersiz element data formatı:', elementData);
                return;
            }
            
            const elementType = obje ? ('obje_' + obje.id) : 'custom';
            const objeId = obje ? obje.id : (elementData.obje_id || null);
            
            const element = createElement(
                elementType,
                imageUrl,
                name,
                x,
                y,
                objeId
            );
            
            element.style.width = width + 'px';
            element.style.height = height + 'px';
            
            boundary.appendChild(element);
            makeElementInteractive(element);
            
            // Yüklenen element için tracking güncelle
            const tracker = window.designState.elements.get(element.id);
            if (tracker) {
                tracker.updateLocation(x, y);
                tracker.updateScale(width, height);
            }
        });
        
        console.log('Mevcut tasarım yüklendi ve tracking başlatıldı:', designData.elements.length + ' element');
        window.designState.isDirty = false; // Yükleme sonrası temiz durum
    }
}

// Geliştirilmiş DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // PHP'den gelen verileri JavaScript'e aktar
    const objeler = @json($objeler ?? []);
    const projectId = @json($project_id ?? null);
    const projectImage = @json($project_image ?? null);
    const existingDesign = @json($existing_design ?? null);
    const project = @json($project ?? null);
    
    // Global design state'i başlat
    window.designState.projectId = projectId;
    window.designState.objeler = objeler;
    window.designState.project = project;
    window.designState.elements = new Map();
    window.designState.isDirty = false;
    window.designState.lastSaveTime = Date.now();
    
    // Objeleri pallete yükle
    loadObjectsToPalette(objeler);
    
    // Arka plan resmini yükle
    if (projectImage) {
        loadBackgroundImage(projectImage);
    } else {
        const backgroundContainer = document.getElementById('backgroundImageContainer');
        backgroundContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    }
    
    // Mevcut tasarımı yükle (tracking ile)
    if (existingDesign && existingDesign.elements) {
        loadExistingDesign(existingDesign);
    }
    
    // Debug bilgileri
    if (project) {
        console.log('Proje yüklendi:', project.title, '| Konum:', project.district + ', ' + project.neighborhood);
        console.log('Design State başlatıldı:', window.designState);
    }
    
    // Periyodik durum raporu (development için)
    setInterval(() => {
        if (window.designState.elements.size > 0) {
            console.log(`Anlık durum: ${window.designState.elements.size} element takip ediliyor, isDirty: ${window.designState.isDirty}`);
        }
    }, 30000); // 30 saniyede bir
});

// Keyboard shortcuts'u güncelle
document.addEventListener('keydown', function (e) {
    if (e.key === 'Delete' && selectedElement) {
        deleteElement(selectedElement);
    }
    if (e.key === 'Escape') {
        deselectAll();
    }
    // Manuel kaydetme kısayolu
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        console.log('Manuel kaydetme tetiklendi');
        collectAndSaveDesignData();
    }
});

// Sayfa kapatılırken uyarı (kaydedilmemiş değişiklikler varsa)
window.addEventListener('beforeunload', function(e) {
    if (window.designState.isDirty) {
        const message = 'Kaydedilmemiş değişiklikleriniz var. Sayfadan ayrılmak istediğinizden emin misiniz?';
        e.returnValue = message;
        return message;
    }
});

// Global fonksiyonları güncelle
window.collectAndSaveDesignData = collectAndSaveDesignData;

// Otomatik olarak sayfa içindeki görünür "kaydet" butonlarını gizle ve işlevlerini devre dışı bırak
function hideInlineSaveButtons() {
    // Eğer proje zaten tamamlanmışsa inline kaydet butonlarını bırak
    try {
        if (window.designState && window.designState.project && window.designState.project.design_completed) {
            console.info('Proje tamamlanmış - inline kaydet butonları bırakılıyor.');
            return;
        }
    } catch (e) {
        // ignore
    }
    const selectors = [
        'button[data-save-design]',
        'button.save-design',
        'button#save-design',
        'button[onclick*="collectAndSaveDesignData"], a[onclick*="collectAndSaveDesignData"]'
    ];

    const container = document.getElementById('filament-designer-root') || document.body;
    const found = new Set();

    selectors.forEach(sel => {
        container.querySelectorAll(sel).forEach(el => found.add(el));
    });

    // Ayrıca metin içeren butonları kontrol et (Türkçe 'Kaydet' anahtar kelimesi)
    container.querySelectorAll('button, a').forEach(el => {
        const txt = (el.textContent || '').trim();
        if (/kaydet/i.test(txt) && (el.offsetParent !== null)) {
            found.add(el);
        }
    });

    found.forEach(el => {
        try {
            // Eğer inline onclick varsa kaldır
            if (el.getAttribute && el.getAttribute('onclick') && el.getAttribute('onclick').includes('collectAndSaveDesignData')) {
                el.removeAttribute('onclick');
            }

            // Eğer jQuery veya başka bir handler ile bağlandıysa, en azından butonu gizle
            el.style.display = 'none';
            el.classList.add('hidden');
            console.info('Inline save button hidden and disabled:', el);
        } catch (e) {
            console.warn('Could not hide inline save button', el, e);
        }
    });

    if (found.size === 0) {
        console.debug('No inline save buttons found to hide.');
    }
}

// Kullancı isteğiyle inline butonları yeniden aktif etme (devtools'tan çağırılabilir)
window.enableInlineSaveButtons = function() {
    const els = document.querySelectorAll('button.hidden, a.hidden');
    els.forEach(el => {
        el.style.display = '';
        el.classList.remove('hidden');
    });
    console.info('Inline save buttons re-enabled');
};

// Gizleme işlemini DOM yüklendikten sonra çalıştır
document.addEventListener('DOMContentLoaded', function() {
    // Kısa gecikme ile çalıştır; Filament içeriği yüklendikten sonra yakalamak için
    setTimeout(hideInlineSaveButtons, 250);
});
window.getCurrentDesignState = getCurrentDesignState;
window.designState = window.designState;
</script>

<!--
    INLINE KAYDET BUTONU (KALICI OLARAK YORUM SATIRINA ALINDI)
    Aşağıdaki buton uygulamada inline olarak kaydetme işlevi sağlıyordu.
    Bu butonun işlevi Filament sayfa action'ı olan
    `Tasarımı Kaydet ve Tamamla` (app/Filament/Pages/DragDropTest.php)
    aracılığıyla yürütülmek üzere devredildi. Eğer tekrar aktifleştirmek isterseniz,
    bu bloktaki HTML yorumunu kaldırabilirsiniz.

    <button id="save-design" class="fi-btn fi-btn-size-md fi-color-success" onclick="collectAndSaveDesignData()">
        Tasarımı Kaydet
    </button>
-->