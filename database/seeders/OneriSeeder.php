<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Oneri;
use App\Models\Category;
use App\Models\User;

class OneriSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();

        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Kategoriler veya kullanıcılar bulunamadı. OneriSeeder atlanıyor.');
            return;
        }

        // Suggestion 1 - Ağaçlandırma
        Oneri::create([
            'category_id' => $categories->where('name', 'Ağaçlandırma')->first()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Kadıköy\'de Caddeler Ağaçlandırılsın',
            'description' => 'Kadıköy\'ün ana caddelerinin ağaçlandırılması ile hava kalitesi iyileştirilsin. Yazın sıcaklığının azalması için geniş yapraklı ağaçlar dikilsin.',
            'estimated_duration' => 3,
            'budget' => 100000.00,
            'latitude' => 40.9850,
            'longitude' => 29.0250,
            'address' => 'Kadıköy, İstanbul',
            'address_details' => 'Mühürdar Caddesi',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'neighborhood' => 'Bostancı',
            'street_cadde' => 'Mühürdar Caddesi',
            'street_sokak' => 'Yaşar Sokak',
        ]);

        // Suggestion 2 - İmar
        Oneri::create([
            'category_id' => $categories->where('name', 'İmar')->first()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Eski Ev Restorasyonu',
            'description' => 'Cihangir\'deki tarihi evlerin restorasyon projesi başlatılsın. Bu eski konutlar onarılarak kültürel mirası korunsun.',
            'estimated_duration' => 12,
            'budget' => 250000.00,
            'latitude' => 41.0380,
            'longitude' => 29.0180,
            'address' => 'Cihangir, İstanbul',
            'address_details' => 'Cihangir Tarihi Mahallesi',
            'city' => 'İstanbul',
            'district' => 'Beyoğlu',
            'neighborhood' => 'Cihangir',
            'street_cadde' => 'Firuzağa Caddesi',
            'street_sokak' => 'Tarih Sokak',
        ]);

        // Suggestion 3 - Çevre Düzenleme
        Oneri::create([
            'category_id' => $categories->where('name', 'Çevre Düzenleme')->first()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Taksim Meydanı Peyzaj Yenilenmesi',
            'description' => 'Taksim Meydanı\'nın peyzaj tasarımı yenilenmeli. Daha yeşil ve yürüyüş alanı uygun olmasını sağlayacak düzenlemeler yapılsın.',
            'estimated_duration' => 6,
            'budget' => 500000.00,
            'latitude' => 41.0370,
            'longitude' => 29.0255,
            'address' => 'Taksim Meydanı, İstanbul',
            'address_details' => 'Taksim Meydanı Merkez',
            'city' => 'İstanbul',
            'district' => 'Beyoğlu',
            'neighborhood' => 'Taksim',
            'street_cadde' => 'İstiklal Caddesi',
            'street_sokak' => 'Meydana Sokak',
        ]);

        // Suggestion 4 - Temizlik
        Oneri::create([
            'category_id' => $categories->where('name', 'Temizlik')->first()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Sokak Temizliğinin Sıklığı Artırılsın',
            'description' => 'Trafiği fazla olan sokaklarda temizlik hizmetinin günlük yapılması sağlansın. Daha temiz bir şehir ortamı oluşturulsun.',
            'estimated_duration' => 1,
            'budget' => 50000.00,
            'latitude' => 41.0350,
            'longitude' => 29.0200,
            'address' => 'Beyoğlu, İstanbul',
            'address_details' => 'Beyoğlu Ana Sokakları',
            'city' => 'İstanbul',
            'district' => 'Beyoğlu',
            'neighborhood' => 'Beyoğlu Merkez',
            'street_cadde' => 'İstiklal Caddesi',
            'street_sokak' => 'Temizlik Sokak',
        ]);

        // Suggestion 5 - Usta
        Oneri::create([
            'category_id' => $categories->where('name', 'Usta')->first()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Usta Eğitim Programı Başlatılsın',
            'description' => 'Şehirde usta eğitim programları açılarak genç nesil için istihdam imkanı yaratılsın. Elektrik, tesisatçı, boyacı gibi alanlarda eğitim verilsin.',
            'estimated_duration' => 6,
            'budget' => 150000.00,
            'latitude' => 41.0450,
            'longitude' => 29.0220,
            'address' => 'Eğitim Merkezi, İstanbul',
            'address_details' => 'Meslek Eğitim Bölgesi',
            'city' => 'İstanbul',
            'district' => 'Çankırı',
            'neighborhood' => 'Merkez',
            'street_cadde' => 'Eğitim Caddesi',
            'street_sokak' => 'Bilim Sokak',
        ]);

        // Suggestion 6 - İşçi
        Oneri::create([
            'category_id' => $categories->where('name', 'İşçi')->first()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Güvenli İş Ortamı Standartları',
            'description' => 'İnşaat alanlarında güvenli iş ortamı standartları uygulanması zorunlu hale getirilsin. Işçi sağlığı ve güvenliği priorite olsun.',
            'estimated_duration' => 2,
            'budget' => 75000.00,
            'latitude' => 41.0500,
            'longitude' => 29.0300,
            'address' => 'İş Güvenliği Koordinasyon Merkezi, İstanbul',
            'address_details' => 'Güvenlik Eğitim Alanı',
            'city' => 'İstanbul',
            'district' => 'Beylikdüzü',
            'neighborhood' => 'Sanayii Bölgesi',
            'street_cadde' => 'Sanayi Caddesi',
            'street_sokak' => 'Güvenlik Sokak',
        ]);

        // Suggestion 7 - Çocuk Parkı
        Oneri::create([
            'category_id' => $categories->where('name', 'Çocuk Parkı')->first()->id ?? null,
            'created_by_id' => $users->random()->id,
            'title' => 'Her Mahallede Çocuk Oyun Parkı',
            'description' => 'Her mahallenin en az bir çocuk oyun parkına sahip olması sağlansın. Çocuklar için güvenli ve eğlenceli ortamlar oluşturulsun.',
            'estimated_duration' => 4,
            'budget' => 200000.00,
            'latitude' => 40.9900,
            'longitude' => 29.0100,
            'address' => 'Çeşitli Mahallelerde, İstanbul',
            'address_details' => 'Mahalle Oyun Parkları',
            'city' => 'İstanbul',
            'district' => 'Bakırköy',
            'neighborhood' => 'Çeşitli',
            'street_cadde' => 'Çocuk Caddesi',
            'street_sokak' => 'Oyun Sokak',
        ]);

        // Suggestion 8
        Oneri::create([
            'category_id' => $categories->random()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Bisiklet Yolları Genişletilsin',
            'description' => 'Şehir içinde bisiklet yolları ağı genişletilerek çevre dostu ulaşım sağlansın. Toplu taşıma ile bisiklet entegrasyonu kurulsun.',
            'estimated_duration' => 8,
            'budget' => 300000.00,
            'latitude' => 41.0100,
            'longitude' => 29.0150,
            'address' => 'Şehir İçi Yollar, İstanbul',
            'address_details' => 'Bisiklet Yolu Ağı',
            'city' => 'İstanbul',
            'district' => 'Beşiktaş',
            'neighborhood' => 'Orta Mahalle',
            'street_cadde' => 'Ana Yol',
            'street_sokak' => 'Bisiklet Sokak',
        ]);

        // Suggestion 9
        Oneri::create([
            'category_id' => $categories->random()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Halka Açık WIFI Sistemi Kurulsun',
            'description' => 'Tüm parklarda ve açık alanlarda halka açık kablosuz internet hizmeti verilsin. Dijital erişimde eşitlik sağlansın.',
            'estimated_duration' => 3,
            'budget' => 120000.00,
            'latitude' => 41.0200,
            'longitude' => 29.0300,
            'address' => 'Halka Açık Alanlar, İstanbul',
            'address_details' => 'Park ve Meydanlar',
            'city' => 'İstanbul',
            'district' => 'Eminönü',
            'neighborhood' => 'Tarihi Yarımada',
            'street_cadde' => 'Dijital Caddesi',
            'street_sokak' => 'İnternet Sokak',
        ]);

        // Suggestion 10
        Oneri::create([
            'category_id' => $categories->random()->id,
            'created_by_id' => $users->random()->id,
            'title' => 'Engelli Erişimi Artırılsın',
            'description' => 'Kamusal alanlarda engelli erişimi ve engelli tuvaletleri sayısı artırılsın. Herkesin şehri eşit şekilde kullanabilmesi sağlansın.',
            'estimated_duration' => 6,
            'budget' => 180000.00,
            'latitude' => 41.0000,
            'longitude' => 29.0350,
            'address' => 'Kamusal Alanlar, İstanbul',
            'address_details' => 'Şehir İçi Erişim Yolları',
            'city' => 'İstanbul',
            'district' => 'Fatih',
            'neighborhood' => 'Rüstem Paşa',
            'street_cadde' => 'Erişim Caddesi',
            'street_sokak' => 'Engelsiz Sokak',
        ]);

        $this->command->info('10 adet öneri başarıyla oluşturuldu.');
    }
}
