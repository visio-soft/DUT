## Kısa kullanım notu

Veritabanındaki gereksiz tabloları temizleyip (istenmeyenleri truncate/delete) seed çalıştırmak için küçük bir yardımcı eklendi.

Kullanım

- Varsayılan çalıştırma (varsayılan olarak `migrations` ve `categories` tablolarını korur, `Database\Seeders\DatabaseSeeder` çalıştırır):

```
php scripts/seed_clean.php
```

- Composer üzerinden:

```
composer seed:clean
```

- Saklanacak tabloları ve çalıştırılacak seedleri özelleştirme:

```
php scripts/seed_clean.php --keep=categories,users --seeders=Database\\Seeders\\CategorySeeder,Database\\Seeders\\UserSeeder
```

Notlar

- Bu işlem veri siler (truncate/delete). Sadece geliştirme ortamında veya yedek alındığından emin olarak kullanın.
- PgSQL, MySQL ve SQLite ile çalışır.


