# DUT - Katılım ve Proje Yönetim Sistemi

Kurumsal projeler için kullanıcı önerilerini toplamak, değerlendirmek ve yönetmek üzere tasarlanmış kapsamlı bir **Katılım ve Proje Yönetim Sistemi**. Laravel ve Filament PHP ile geliştirilmiş bu platform, yöneticilerin (örn. belediyeler, organizasyonlar) kamu geri bildirimi için projeler açmasına, kullanıcıların fikir sunmasına, oy kullanmasına ve tartışmasına olanak tanır.

## 📋 İçindekiler

- [Özellikler](#-özellikler)
- [Sistem Gereksinimleri](#-sistem-gereksinimleri)
- [Kurulum](#-kurulum)
  - [Yerel Geliştirme Ortamı Kurulumu](#yerel-geliştirme-ortamı-kurulumu)
  - [Canlı Ortam Kurulumu](#canlı-ortam-kurulumu)
- [Varsayılan Admin Hesapları](#-varsayılan-admin-hesapları)
- [Uygulamayı Çalıştırma](#-uygulamayı-çalıştırma)
- [Veritabanı Yönetimi](#-veritabanı-yönetimi)
- [Sorun Giderme](#-sorun-giderme)
- [Ek Dökümanlar](#-ek-dökümanlar)

## ✨ Özellikler

- **Admin Paneli**: Kapsamlı yönetim için Filament PHP ile geliştirilmiş
- **Kullanıcı ve Rol Yönetimi**: Rol tabanlı erişim kontrol sistemi
- **Proje Yönetimi**: Oylama özellikleri ile proje oluşturma ve yönetme
- **Öneri Sistemi**: Kullanıcılar öneri gönderebilir, oy kullanabilir ve tartışabilir
- **Çoklu Dil Desteği**: İngilizce, Türkçe, Fransızca, Almanca ve İsveççe
- **Konum Hiyerarşisi**: Ülke → Şehir → İlçe → Mahalle yapısı
- **Anket Sistemi**: Çoktan seçmeli ve metin tabanlı sorularla anket oluşturma
- **Medya Yönetimi**: Spatie Media Library ile görsel yükleme ve yönetme
- **Yorum Sistemi**: Moderasyon özellikleri ile zincirleme yorumlar

## 💻 Sistem Gereksinimleri

Kuruluma başlamadan önce, sisteminizin aşağıdaki gereksinimleri karşıladığından emin olun:

- **PHP**: >= 8.2
- **Composer**: En son sürüm
- **Node.js**: >= 18.x
- **NPM**: En son sürüm
- **Veritabanı**: MySQL 8.0+ / PostgreSQL 12+ / SQLite 3.35+

### Gerekli PHP Eklentileri

```bash
# Ubuntu/Debian
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring \
  php8.2-xml php8.2-zip php8.2-gd php8.2-mysql php8.2-pgsql php8.2-sqlite3 \
  php8.2-fileinfo php8.2-tokenizer php8.2-pdo

# macOS (Homebrew kullanarak)
brew install php@8.2

# Windows
# PHP 8.2'yi php.net adresinden indirin ve kurun
```

## 🚀 Kurulum

### Yerel Geliştirme Ortamı Kurulumu

Projeyi yerel geliştirme ortamınızda kurmak için şu adımları izleyin:

#### 1. Depoyu Klonlayın

```bash
git clone https://github.com/visio-soft/DUT.git
cd DUT
```

#### 2. PHP Bağımlılıklarını Yükleyin

```bash
composer install
```

Bu komut Laravel framework, Filament ve diğer bağımlılıklar dahil tüm gerekli PHP paketlerini yükleyecektir.

#### 3. JavaScript Bağımlılıklarını Yükleyin

```bash
npm install
```

Bu komut Vite, Alpine.js ve Tailwind CSS dahil frontend bağımlılıklarını yükler.

#### 4. Ortam Yapılandırması

Örnek ortam dosyasını kopyalayın ve yapılandırın:

```bash
cp .env.example .env
```

`.env` dosyasını düzenleyin ve ayarlarınızı yapılandırın:

```env
APP_NAME="DUT Projesi"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Veritabanı Yapılandırması (hızlı kurulum için SQLite)
DB_CONNECTION=sqlite

# VEYA MySQL/PostgreSQL kullanın
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dut
# DB_USERNAME=root
# DB_PASSWORD=

# Opsiyonel: Google Translate API (çoklu dil desteği için)
# GOOGLE_TRANSLATE_API_KEY=api_anahtariniz
# GOOGLE_TRANSLATE_PROJECT_ID=proje_id_niz
```

#### 5. Uygulama Anahtarı Oluşturun

```bash
php artisan key:generate
```

Bu komut uygulamanız için güvenli bir şifreleme anahtarı oluşturur.

#### 6. Veritabanı Kurulumu

**SQLite için** (hızlı kurulum için önerilir):

```bash
# SQLite veritabanı dosyasını oluşturun
touch database/database.sqlite

# Migrasyonları çalıştırın
php artisan migrate
```

**MySQL/PostgreSQL için**:

```bash
# Veritabanınızın oluşturulduğundan emin olun, ardından çalıştırın:
php artisan migrate
```

#### 7. Veritabanını Doldurun (Seed)

Admin kullanıcıları, kategoriler ve örnek verileri içeren başlangıç verilerini ekleyin:

```bash
php artisan db:seed
```

#### 8. Storage Bağlantısı Oluşturun

`public/storage`'dan `storage/app/public`'e sembolik bağlantı oluşturun:

```bash
php artisan storage:link
```

Bu, yüklenen dosyaların halka açık olarak erişilebilir olmasını sağlar.

#### 9. Frontend Varlıklarını Derleyin (Geliştirme)

Geliştirme için derlemeyi atlayıp doğrudan dev sunucusunu çalıştırabilirsiniz (sonraki bölüme bakın).

```bash
npm run dev
```

### Canlı Ortam Kurulumu

Canlı ortam kurulumu için [DEPLOYMENT.md](DEPLOYMENT.md) dosyasına bakın. Burada şunları bulabilirsiniz:
- Web sunucusu yapılandırması (Apache/Nginx)
- SSL/HTTPS kurulumu
- Performans optimizasyonu
- Güvenlik en iyi uygulamaları

## 🔑 Varsayılan Admin Hesapları

Seed'leri çalıştırdıktan sonra bu hesaplarla giriş yapabilirsiniz:

| E-posta | Şifre | Rol |
|---------|-------|-----|
| admin@admin.com | password | Süper Admin |
| omega@admin.com | omega456 | Admin |
| normaladmin@dut.com | main123 | Admin |

**⚠️ Önemli**: Canlı ortamda bu şifreleri hemen değiştirin!

## ▶️ Uygulamayı Çalıştırma

### Geliştirme Modu

Geliştirmede **hem** Laravel sunucusu hem de Vite dev sunucusunu aynı anda çalıştırmanız gerekir.

#### Seçenek 1: İki Ayrı Terminal (Önerilen)

**Terminal 1 - Laravel Sunucusu:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Sunucusu (CSS/JS otomatik yenileme için):**
```bash
npm run dev
```

Ardından tarayıcınızda şu adresi açın: `http://localhost:8000`

#### Seçenek 2: Tek Komut (Composer Kullanarak)

```bash
composer run dev
```

Bu komut hem sunucuları hem de queue listener ve log viewer'ı eşzamanlı çalıştırır.

#### Seçenek 3: Arka Plan İşlemleri

```bash
# Laravel sunucusunu arka planda çalıştır
php artisan serve > /tmp/laravel-serve.log 2>&1 &

# Vite dev sunucusunu arka planda çalıştır
npm run dev > /tmp/vite-dev.log 2>&1 &
```

### Uygulamaya Erişim

- **Kullanıcı Paneli**: http://localhost:8000
- **Admin Paneli**: http://localhost:8000/admin

Admin paneline erişmek için yukarıda listelenen admin hesaplarından biriyle giriş yapın.

## 🗄️ Veritabanı Yönetimi

### Seed'leri Çalıştırma

Belirli seed sınıflarını çalıştırın:

```bash
# Belirli bir seed'i çalıştır
php artisan db:seed --class=CategorySeeder

# Tüm seed'leri çalıştır
php artisan db:seed
```

### Veritabanını Temizle ve Yeniden Doldur

Proje, belirli tabloları korurken veritabanını temizlemek için bir yardımcı script içerir:

```bash
# Composer kullanarak
composer seed:clean

# Script'i doğrudan çalıştırma
php scripts/seed_clean.php

# Korunacak tablolar ve çalıştırılacak seed'ler özelleştirme
php scripts/seed_clean.php --keep=categories,users --seeders=Database\\\\Seeders\\\\CategorySeeder
```

**⚠️ Uyarı**: Bu script tabloları truncate eder. Sadece geliştirme ortamında veya yedek aldıktan sonra kullanın!

### Taze Migrasyon (Her Şeyi Sıfırla)

```bash
# Tüm tabloları sil ve migrasyonları yeniden çalıştır
php artisan migrate:fresh

# Seed'lerle birlikte
php artisan migrate:fresh --seed
```

## 🔧 Sorun Giderme

### Yaygın Sorunlar ve Çözümleri

#### Sorun: "Permission denied" hataları

```bash
# Storage ve cache izinlerini düzelt
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux/Apache
```

#### Sorun: "Mix manifest not found" veya CSS/JS yüklenmiyor

```bash
# Varlıkları derle
npm run build

# Veya dev sunucusu çalıştır
npm run dev
```

#### Sorun: Veritabanı bağlantı hataları

- `.env` dosyanızdaki veritabanı kimlik bilgilerini kontrol edin
- Veritabanı servisinin çalıştığından emin olun
- SQLite için `database/database.sqlite` dosyasının var olduğundan ve uygun izinlere sahip olduğundan emin olun

#### Sorun: "Class not found" hataları

```bash
# Autoload dosyalarını temizle ve yeniden oluştur
composer dump-autoload

# Tüm önbellekleri temizle
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Sorun: Dosya yükleme çalışmıyor

- Storage bağlantısını kontrol edin: `php artisan storage:link`
- Storage izinlerini doğrulayın: `chmod -R 775 storage`
- `php.ini` dosyasındaki PHP yükleme limitlerini kontrol edin:
  - `upload_max_filesize = 50M`
  - `post_max_size = 60M`

### Yardım Alma

Burada ele alınmayan sorunlarla karşılaşırsanız:

1. [Laravel Dokümantasyonu](https://laravel.com/docs)'nu kontrol edin
2. [Filament Dokümantasyonu](https://filamentphp.com/docs)'nu kontrol edin
3. Uygulama loglarını inceleyin: `storage/logs/laravel.log`

## 📚 Ek Dökümanlar

- **[DEPLOYMENT.md](DEPLOYMENT.md)**: Canlı ortam kurulum kılavuzu
- **[PROJECT_HANDOVER.md](PROJECT_HANDOVER.md)**: Tam proje genel bakış ve özellikler
- **[UPLOAD-README.md](UPLOAD-README.md)**: Dosya yükleme sistemi dokümantasyonu

## 🛠️ Geliştirme Komutları

```bash
# Testleri çalıştır
composer test

# Laravel Pint ile kod formatlama
./vendor/bin/pint

# Tüm önbellekleri temizle
php artisan optimize:clear

# Logları gerçek zamanlı görüntüle
php artisan pail

# Queue worker çalıştır
php artisan queue:work
```

## 📝 Lisans

Bu proje MIT Lisansı altında lisanslanmıştır.

---

**Katılımcı yönetişim ve toplum katılımı için ❤️ ile yapılmıştır**
