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

Bu kısa bölüm, tek bir seeder çalıştırma adımını gösterir; farklı ihtiyaçlar için seeder içeriğini / namespace ayarlarını kontrol edin.
