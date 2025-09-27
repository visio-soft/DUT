# 20MB Dosya Upload Limiti

Sistem **20MB maksimum dosya boyutu** ile çalışır.

## ✅ Yapılan Değişiklikler

### Filament Resources

-   **OneriResource**: `maxSize(20480)` - 20MB limit
-   **CategoryResource**: `maxSize(20480)` - 20MB limit

### Config Dosyları

-   **media-library.php**: `max_file_size = 20MB`
-   **upload.php**: `max_file_size_mb = 20`

### PHP Ayarları

-   **AppServiceProvider**: Basic PHP limits
    -   `upload_max_filesize = 25M`
    -   `post_max_size = 30M`
    -   `memory_limit = 256M`

## 🚀 CANLI SUNUCUDA UYGULAMA

### 1️⃣ **Hosting Panel Üzerinden (En Kolay)**

**cPanel/DirectAdmin/Plesk** varsa:

```
PHP Ayarları > PHP Options > Şu değerleri girin:
- upload_max_filesize = 25M
- post_max_size = 30M
- memory_limit = 256M
- max_execution_time = 300
```

### 2️⃣ **php.ini Dosyası Düzenleme**

Hosting'de `public_html` veya ana dizinde `.htaccess` dosyasına ekleyin:

```apache
# 20MB Upload için PHP ayarları
php_value upload_max_filesize 25M
php_value post_max_size 30M
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_time 300
```

### 3️⃣ **PHP-FPM Pool Ayarları (VPS/Dedicated)**

`/etc/php/8.x/fpm/pool.d/www.conf` dosyasına:

```ini
php_admin_value[upload_max_filesize] = 25M
php_admin_value[post_max_size] = 30M
php_admin_value[memory_limit] = 256M
php_admin_value[max_execution_time] = 300
```

### 4️⃣ **Nginx Ayarları (VPS/Dedicated)**

`/etc/nginx/sites-available/yoursite` dosyasına:

```nginx
client_max_body_size 25M;
client_body_timeout 300;
fastcgi_read_timeout 300;
```

### 5️⃣ **Test Etme**

Değişiklikleri yaptıktan sonra:

```bash
# 1. Web server restart
sudo systemctl restart nginx  # veya apache2

# 2. PHP-FPM restart
sudo systemctl restart php8.2-fpm

# 3. Laravel cache temizle
php artisan optimize:clear
```

## ⚠️ **Önemli Notlar**

1. **Shared Hosting**: Sadece hosting panel veya .htaccess yöntemi kullanılabilir
2. **VPS/Dedicated**: Tüm yöntemler kullanılabilir, server restart gerekebilir
3. **Cloudflare**: 100MB limit var, problem çıkarmaz
4. **Test**: 15-20MB dosya upload ederek test edin

## 🎯 **Sonuç**

✅ Uygulama kodu hazır - 20MB limit aktif  
⚠️ Sunucu ayarları gerekli - yukarıdaki adımları takip edin
