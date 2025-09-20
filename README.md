## Kısa kullanım notu

Veritabanındaki gereksiz tabloları temizleyip (istenmeyenleri truncate/delete) seed çalıştırmak için küçük bir yardımcı eklendi.

## Seeder nasıl çalıştırılır

Aşağıdaki komutlar, Laravel projesinde tek bir seeder dosyasını çalıştırmak için kullanılabilir.

-   Basit kullanım (seeder sınıf adı ile):

```bash
php artisan db:seed --class=CategorySeeder
```

-   Tam namespace gerekiyorsa (özellikle Laravel 8+ veya farklı namespace kullanıyorsanız):

```bash
php artisan db:seed --class="Database\\Seeders\\CategorySeeder"
```

-   Eğer `DatabaseSeeder` içinde bir seeder çağrısı varsa, tüm `DatabaseSeeder`'ı çalıştırmak için:

```bash
php artisan db:seed
```

Notlar:

-   Komutları çalıştırmadan önce `.env` dosyanızda veritabanı bağlantınızın doğru ayarlı olduğundan emin olun.
-   Genelde önce migration'ları çalıştırmak isterseniz:

```bash
php artisan migrate
```

-   Local geliştirme ortamında SQLite kullanıyorsanız, `DB_CONNECTION=sqlite` ve `DB_DATABASE` ayarlarının doğru olduğuna dikkat edin.

---

## Veritabanını temizleyip seed çalıştırma (seed_clean)

Projeye eklenmiş `scripts/seed_clean.php` yardımcı scripti, belirttiğiniz tabloları koruyarak geri kalan tabloları temizler ve sonrasında seedleri çalıştırır.

Varsayılan davranış:

-   Korunan tablolar: `migrations`, `categories`
-   Çalıştırılan seeder: `Database\\Seeders\\DatabaseSeeder`

Kullanım örnekleri:

```bash
php scripts/seed_clean.php
```

veya composer üzerinden:

```bash
composer seed:clean
```

Özelleştirme (saklanacak tablolar ve seed sınıfları):

```bash
php scripts/seed_clean.php --keep=categories,users --seeders=Database\\\\Seeders\\\\CategorySeeder,Database\\\\Seeders\\\\UserSeeder
```

Notlar:

-   Bu script tabloları truncate/delete yaparak veri siler; yalnızca geliştirme ortamında veya yedek alındığında kullanın.
-   PgSQL, MySQL ve SQLite ile uyumludur.

---

## Hızlı kurulum (kısa ve net)

1. Depoyu hazırlayın ve bağımlılıkları yükleyin:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

2. SQLite kullanıyorsanız boş dosyayı oluşturun ve migration çalıştırın:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --force
```

3. Storage bağlantısını oluşturun (public/storage -> storage/app/public):

```bash
php artisan storage:link --force
```

4. Seeder'ları çalıştırın (örnek):

```bash
php artisan db:seed
```

5. Oluşturulan örnek admin hesapları (seed tarafından eklenmiştir):

-   Email: admin@admin.com / Password: password (super_admin)
-   Email: omega@admin.com / Password: omega456 (normal admin)
-   Email: normaladmin@dut.com / Password: main123 (normal admin)

6. Geliştirme sunucusu:

**Önemli**: Geliştirme ortamında hem Laravel sunucusu hem de Vite dev server'ının aynı anda çalışması gerekir.

İki ayrı terminal açarak aşağıdaki komutları çalıştırın:

**Terminal 1 - Laravel sunucusu:**
```bash
php artisan serve
```

**Terminal 2 - Vite dev server (CSS/JS için):**
```bash
npm run dev
```

Alternatif olarak, arka planda çalıştırmak için:

```bash
# Laravel sunucusu arka planda
nohup php artisan serve --host=127.0.0.1 --port=8000 > /tmp/laravel-serve.log 2>&1 &

# Vite dev server arka planda
nohup npm run dev > /tmp/vite-dev.log 2>&1 &
```

Not: Bu adımlar yerel geliştirme içindir. Üretim kurulumunda farklı konfigürasyon ve izinler gerekebilir.
