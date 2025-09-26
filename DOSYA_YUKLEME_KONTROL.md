# Dosya Yükleme Sorunu Çözüm Rehberi

## Tespit Edilen Sorun
`public/js/chunked-upload.js` dosyasında `baseUrl` tanımsız hata düzeltildi.

## Domain Sunucuda Kontrol Edilmesi Gereken Noktalar

### 1. PHP Ayarları
Domain sunucunuzda aşağıdaki PHP ayarlarını kontrol edin:
```bash
# php.ini dosyasında bu değerlerin doğru ayarlandığından emin olun:
upload_max_filesize = 50M
post_max_size = 60M
max_file_uploads = 20
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

### 2. Dizin İzinleri
Aşağıdaki dizinlerin yazma izinlerinin olduğundan emin olun:
```bash
# Domain sunucuda bu komutları çalıştırın:
chmod -R 775 storage/
chmod -R 775 public/media/
chmod -R 775 storage/app/public/
```

### 3. Symbolic Link Kontrolü
```bash
# Domain sunucuda storage link oluşturun:
php artisan storage:link
```

### 4. Web Server Yapılandırması

#### Apache (.htaccess)
`public/.htaccess` dosyasına eklenebilir:
```apache
# File upload limits
php_value upload_max_filesize 50M
php_value post_max_size 60M
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 256M
```

#### Nginx
Domain sunucunuzda nginx yapılandırmasına ekleyin:
```nginx
client_max_body_size 60M;
client_body_timeout 300s;
client_header_timeout 300s;
```

### 5. Environment Değişkenleri
Domain sunucusunda `.env` dosyasında:
```env
APP_URL=https://yourdomain.com  # Doğru domain
FILESYSTEM_DISK=media
APP_DEBUG=false  # Production için false
```

### 6. Database Kontrolleri
Media tablosunda kayıtların düzgün oluştuğunu kontrol edin.

### 7. Log Kontrolleri
Domain sunucuda hata loglarını kontrol edin:
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/nginx/error.log
tail -f /var/log/apache2/error.log
```

## Test Komutları

### Debug Route Testi
```
https://yourdomain.com/debug-upload
```

### Chunked Upload Test
```javascript
// Browser console'da test:
fetch('/api/chunked-upload', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({action: 'test'})
}).then(r => r.json()).then(console.log)
```

## Düzeltilen Dosyalar
1. `public/js/chunked-upload.js` - baseUrl sorunu düzeltildi

## Yapılması Gerekenler (Domain Sunucu)
1. PHP ayarlarını kontrol et ve güncelle
2. Dizin izinlerini ayarla
3. Web server yapılandırmasını güncelle
4. `.env` dosyasını kontrol et
5. Storage link oluştur
6. Logları kontrol et

## Sorun Devam Ederse
- Network tab'da HTTP requestlerin durumunu kontrol edin
- 413 (Payload Too Large) hatası alıyorsanız PHP/Web server limitleri sorunu
- 500 hatası alıyorsanız Laravel log kontrolü gerekli
- 404 hatası alıyorsanız route tanımı eksik