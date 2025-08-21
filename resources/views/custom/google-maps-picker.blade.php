<div class="google-maps-container">
    <div id="map-container" 
         style="height: 400px; width: 100%; border: 2px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
        
        @if(config('filament-google-maps.key'))
            <!-- Google Maps yüklü olduğunda harita gösterilecek -->
            <div id="map" style="height: 100%; width: 100%;"></div>
            
            <script>
                let map, marker, searchBox;
                
                function initMap() {
                    // Türkiye merkezi koordinatları
                    const turkeyCenter = { lat: 39.9334, lng: 32.8597 };
                    
                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 6,
                        center: turkeyCenter,
                    });
                    
                    marker = new google.maps.Marker({
                        position: turkeyCenter,
                        map: map,
                        draggable: true,
                        title: "Konum seçmek için sürükleyin veya haritaya tıklayın"
                    });
                    
                    // Adres arama kutusu
                    const searchInput = document.querySelector('input[wire\\:model="data.search_address"]');
                    if (searchInput) {
                        searchBox = new google.maps.places.SearchBox(searchInput);
                        
                        // Arama sonuçlarına göre haritayı güncelle
                        searchBox.addListener('places_changed', function() {
                            const places = searchBox.getPlaces();
                            if (places.length == 0) return;
                            
                            const place = places[0];
                            if (!place.geometry || !place.geometry.location) return;
                            
                            // Marker'ı yeni konuma taşı
                            marker.setPosition(place.geometry.location);
                            map.setCenter(place.geometry.location);
                            map.setZoom(15);
                            
                            // Koordinatları güncelle
                            updateCoordinates(
                                place.geometry.location.lat(), 
                                place.geometry.location.lng()
                            );
                        });
                    }
                    
                    // Marker sürüklendiğinde koordinatları güncelle
                    marker.addListener('dragend', function() {
                        const position = marker.getPosition();
                        updateCoordinates(position.lat(), position.lng());
                    });
                    
                    // Haritaya tıklandığında marker'ı taşı
                    map.addListener('click', function(e) {
                        const position = e.latLng;
                        marker.setPosition(position);
                        updateCoordinates(position.lat(), position.lng());
                    });
                }
                
                function updateCoordinates(lat, lng) {
                    // Hidden input'ları güncelle
                    const latInput = document.querySelector('input[wire\\:model="data.latitude"]');
                    const lngInput = document.querySelector('input[wire\\:model="data.longitude"]');
                    
                    if (latInput) latInput.value = lat;
                    if (lngInput) lngInput.value = lng;
                    
                    // Reverse geocoding ile adresi al
                    const geocoder = new google.maps.Geocoder();
                    const latlng = { lat: lat, lng: lng };
                    
                    geocoder.geocode({ location: latlng }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            const address = results[0];
                            let district = '';
                            let detailAddress = '';
                            
                            // Adres bileşenlerinden ilçeyi bul
                            address.address_components.forEach(component => {
                                if (component.types.includes('administrative_area_level_2') || 
                                    component.types.includes('sublocality_level_1')) {
                                    district = component.long_name;
                                }
                            });
                            
                            // Detay adres oluştur
                            detailAddress = address.formatted_address
                                .replace(', İstanbul, Türkiye', '')
                                .replace(', Turkey', '')
                                .replace(district + ', ', '');
                            
                            // Form alanlarını güncelle
                            const districtSelect = document.querySelector('select[wire\\:model="data.district"]');
                            const addressInput = document.querySelector('input[wire\\:model="data.address"]');
                            
                            if (districtSelect && district) {
                                // İlçe seçeneğini kontrol et ve varsa seç
                                const options = Array.from(districtSelect.options);
                                const matchingOption = options.find(option => 
                                    option.value.toLowerCase().includes(district.toLowerCase()) ||
                                    district.toLowerCase().includes(option.value.toLowerCase())
                                );
                                
                                if (matchingOption) {
                                    districtSelect.value = matchingOption.value;
                                    // Livewire'a bildirme
                                    districtSelect.dispatchEvent(new Event('change'));
                                }
                            }
                            
                            if (addressInput) {
                                addressInput.value = detailAddress;
                                addressInput.dispatchEvent(new Event('input'));
                            }
                            
                            // Arama kutusunu da güncelle
                            const searchInput = document.querySelector('input[wire\\:model="data.search_address"]');
                            if (searchInput) {
                                searchInput.value = address.formatted_address;
                                searchInput.dispatchEvent(new Event('input'));
                            }
                        }
                    });
                    
                    // Livewire'a değişikliği bildir
                    if (window.Livewire) {
                        window.Livewire.emit('updateCoordinates', { lat: lat, lng: lng });
                    }
                }
                
                // Sayfa yüklendiğinde haritayı başlat
                window.addEventListener('load', function() {
                    if (typeof google !== 'undefined') {
                        initMap();
                    }
                });
            </script>
            
            <!-- Google Maps API'sini yükle (Places library ile birlikte) -->
            <script async defer 
                src="https://maps.googleapis.com/maps/api/js?key={{ config('filament-google-maps.key') }}&libraries=places&callback=initMap">
            </script>
        @else
            <!-- API anahtarı yoksa bilgi mesajı göster -->
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Google Maps</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Haritayı kullanabilmek için Google Maps API anahtarı gerekiyor.
                        <br>
                        <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">.env</code> dosyasına 
                        <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">GOOGLE_MAPS_API_KEY</code> ekleyin.
                    </p>
                    <p class="mt-2 text-xs text-gray-400">
                        API anahtarı için <strong>Maps JavaScript API</strong> ve <strong>Places API</strong> aktif edilmelidir.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .google-maps-container {
        margin-top: 1rem;
    }
</style>
