<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Category;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $createdByUser = User::where('email', 'admin@admin.com')->first();

        if (!$createdByUser || $categories->isEmpty()) {
            $this->command->warn('Kategoriler veya Admin kullanıcısı bulunamadı. ProjectSeeder atlanıyor.');
            return;
        }

        // Project 1 - Ağaçlandırma
        Project::create([
            'category_id' => $categories->where('name', 'Ağaçlandırma')->first()->id,
            'created_by_id' => $createdByUser->id,
            'title' => 'Ataköy Yeşil Alan Projesi',
            'description' => 'Ataköy bölgesinde 500 ağaç dikilmesi ve çevre düzenleme projesi. Bölge sakinleri tarafından talep edilen bu proje ile yeşil alan arttırılacaktır.',
            'start_date' => '2025-04-01',
            'end_date' => '2025-06-30',
            'budget' => 250000.00,
            'latitude' => 41.0438,
            'longitude' => 29.0066,
            'address' => 'Ataköy, İstanbul',
            'address_details' => 'Ataköy Mahallesi',
            'city' => 'İstanbul',
            'district' => 'Bakırköy',
            'neighborhood' => 'Ataköy',
            'street_cadde' => 'Sahil Caddesi',
            'street_sokak' => 'Yeşil Sokak',
        ]);

        // Project 2 - İmar
        Project::create([
            'category_id' => $categories->where('name', 'İmar')->first()->id,
            'created_by_id' => $createdByUser->id,
            'title' => 'Merkez Mahallesi İmar Projesi',
            'description' => 'Merkez Mahallesi\'nde yaşlı yapıların yenilenmesi ve kentsel dönüşüm projesi. 50 hanelik alan kapsayan bu proje ile bölge modernize edilecektir.',
            'start_date' => '2025-03-15',
            'end_date' => '2025-12-31',
            'budget' => 1500000.00,
            'latitude' => 41.0500,
            'longitude' => 29.0120,
            'address' => 'Merkez Mahallesi, İstanbul',
            'address_details' => 'Merkez Mahallesi Tarihi Alan',
            'city' => 'İstanbul',
            'district' => 'Fatih',
            'neighborhood' => 'Merkez Mahallesi',
            'street_cadde' => 'Sultan Mehmed Caddesi',
            'street_sokak' => 'Gül Sokak',
        ]);

        // Project 3 - Çevre Düzenleme
        Project::create([
            'category_id' => $categories->where('name', 'Çevre Düzenleme')->first()->id,
            'created_by_id' => $createdByUser->id,
            'title' => 'Park İçi Bahçe Düzenleme',
            'description' => 'Şehir parında yapılan bahçe ve peyzaj düzenleme çalışmaları. Modern tasarımla uyumlu olarak yapılan bu proje ile parkın estetik değeri artırılacaktır.',
            'start_date' => '2025-05-01',
            'end_date' => '2025-08-31',
            'budget' => 350000.00,
            'latitude' => 41.0520,
            'longitude' => 29.0080,
            'address' => 'Emirgan Parkı, İstanbul',
            'address_details' => 'Emirgan Parkı Merkez Alan',
            'city' => 'İstanbul',
            'district' => 'Sarıyer',
            'neighborhood' => 'Emirgan',
            'street_cadde' => 'Park Caddesi',
            'street_sokak' => 'Bahçe Sokak',
        ]);

        // Project 4 - Temizlik
        Project::create([
            'category_id' => $categories->where('name', 'Temizlik')->first()->id,
            'created_by_id' => $createdByUser->id,
            'title' => 'Sahil Temizlik ve Çevre Düzenlemesi',
            'description' => 'Marmara Sahili\'nde yapılan büyük ölçekli temizlik ve çevre düzenleme faaliyeti. Deniz kıyısındaki çöplerin temizlenmesi ve çevre koruma amaçlı yapılmıştır.',
            'start_date' => '2025-02-01',
            'end_date' => '2025-11-30',
            'budget' => 150000.00,
            'latitude' => 41.0300,
            'longitude' => 29.0000,
            'address' => 'Marmara Sahili, İstanbul',
            'address_details' => 'Kumköy Sahil Alanı',
            'city' => 'İstanbul',
            'district' => 'Bakırköy',
            'neighborhood' => 'Kumköy',
            'street_cadde' => 'Sahil Yolu',
            'street_sokak' => 'Temizlik Sokak',
        ]);

        // Project 5 - Çocuk Parkı
        Project::create([
            'category_id' => $categories->where('name', 'Çocuk Parkı')->first()->id ?? null,
            'created_by_id' => $createdByUser->id,
            'title' => 'Yeni Çocuk Oyun Parkı İnşaatı',
            'description' => 'Sulukule Mahallesi\'nde modern çocuk oyun parkı inşaatı. Güvenli ve eğlenceli bir alan oluşturulması hedeflenmektedir.',
            'start_date' => '2025-06-01',
            'end_date' => '2025-09-30',
            'budget' => 500000.00,
            'latitude' => 41.0180,
            'longitude' => 29.0150,
            'address' => 'Sulukule Mahallesi, İstanbul',
            'address_details' => 'Sulukule Oyun Parkı Alanı',
            'city' => 'İstanbul',
            'district' => 'Fatih',
            'neighborhood' => 'Sulukule',
            'street_cadde' => 'Çocuk Caddesi',
            'street_sokak' => 'Oyun Sokak',
        ]);

        $this->command->info('5 adet proje başarıyla oluşturuldu.');
    }
}
