## Kısa kullanım notu

Veritabanındaki gereksiz tabloları temizleyip (istenmeyenleri truncate/delete) seed çalıştırmak için küçük bir yardımcı eklendi.

## Seeder (tek başına) nasıl çalıştırılır

Aşağıdaki komutlar, Laravel projesinde tek bir seeder dosyasını çalıştırmak için kullanılabilir.

- Basit kullanım (seeder sınıf adı ile):

```bash
php artisan db:seed --class=CategorySeeder
```

- Tam namespace gerekiyorsa (özellikle Laravel 8+ veya farklı namespace kullanıyorsanız):

```bash
php artisan db:seed --class="Database\\Seeders\\CategorySeeder"
```

- Eğer `DatabaseSeeder` içinde bir seeder çağrısı varsa, tüm `DatabaseSeeder`'ı çalıştırmak için:

```bash
php artisan db:seed
```

Notlar:
- Komutları çalıştırmadan önce `.env` dosyanızda veritabanı bağlantınızın doğru ayarlı olduğundan emin olun.
- Genelde önce migration'ları çalıştırmak isterseniz:

```bash
php artisan migrate
```

- Local geliştirme ortamında SQLite kullanıyorsanız, `DB_CONNECTION=sqlite` ve `DB_DATABASE` ayarlarının doğru olduğuna dikkat edin.

---

## Veritabanını temizleyip seed çalıştırma (seed_clean)

Projeye eklenmiş `scripts/seed_clean.php` yardımcı scripti, belirttiğiniz tabloları koruyarak geri kalan tabloları temizler ve sonrasında seedleri çalıştırır.

Varsayılan davranış:

- Korunan tablolar: `migrations`, `categories`
- Çalıştırılan seeder: `Database\\Seeders\\DatabaseSeeder`

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
- Bu script tabloları truncate/delete yaparak veri siler; yalnızca geliştirme ortamında veya yedek alındığında kullanın.
- PgSQL, MySQL ve SQLite ile uyumludur.


