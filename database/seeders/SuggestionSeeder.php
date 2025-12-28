<?php

namespace Database\Seeders;

use App\Enums\SuggestionStatusEnum;
use App\Models\Project;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuggestionSeeder extends Seeder
{
    public function run(): void
    {
        // Get users and projects
        $users = User::all();
        $projects = Project::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');

            return;
        }

        if ($projects->isEmpty()) {
            $this->command->warn('No projects found. Please run ProjectSeeder first.');

            return;
        }

        // Sample suggestions
        $suggestions = [
            [
                'title' => 'Park Alanına Yeni Ağaç Türleri Eklensin',
                'description' => 'Parkta sadece çam ağacı var. Çeşitlilik için meşe ve ıhlamur ağaçları da eklenebilir. Bu hem görsellik hem de ekolojik çeşitlilik açısından faydalı olacaktır.',
                'status' => SuggestionStatusEnum::OPEN,
                'estimated_duration' => 30,
                'min_budget' => 5000.00,
                'max_budget' => 8000.00,
                'city' => 'İstanbul',
                'district' => 'Ataşehir',
                'latitude' => 40.9826,
                'longitude' => 29.1196,
            ],
            [
                'title' => 'Sahile Bisiklet Yolu Yapılsın',
                'description' => 'Sahil şeridinde yürüyüş yolu var ancak bisiklet yolu yok. Bisiklet severler için ayrı bir şerit oluşturulmalı.',
                'status' => SuggestionStatusEnum::OPEN,
                'estimated_duration' => 60,
                'min_budget' => 50000.00,
                'max_budget' => 80000.00,
                'city' => 'İstanbul',
                'district' => 'Kadıköy',
                'latitude' => 40.9910,
                'longitude' => 29.0242,
            ],
            [
                'title' => 'Parkta Gölgelik Alanlar Artırılsın',
                'description' => 'Yaz aylarında park çok sıcak oluyor. Daha fazla gölgelik alan ve oturma yerleri yapılmalı.',
                'status' => SuggestionStatusEnum::CLOSED,
                'estimated_duration' => 45,
                'min_budget' => 15000.00,
                'max_budget' => 25000.00,
                'city' => 'İstanbul',
                'district' => 'Ataşehir',
                'latitude' => 40.9830,
                'longitude' => 29.1200,
            ],
            [
                'title' => 'Çocuk Parkına Engelli Oyun Alanı',
                'description' => 'Engelli çocuklar için özel tasarlanmış oyun ekipmanları eklenebilir. Bu sosyal içerme açısından önemli.',
                'status' => SuggestionStatusEnum::OPEN,
                'estimated_duration' => 30,
                'min_budget' => 20000.00,
                'max_budget' => 35000.00,
                'city' => 'İstanbul',
                'district' => 'Üsküdar',
                'latitude' => 41.0220,
                'longitude' => 29.0260,
            ],
            [
                'title' => 'Otopark Aydınlatması Yetersiz',
                'description' => 'Akşam saatlerinde otopark çok karanlık kalıyor. Güvenlik ve görüş için daha fazla aydınlatma gerekli.',
                'status' => SuggestionStatusEnum::CLOSED,
                'estimated_duration' => 15,
                'min_budget' => 10000.00,
                'max_budget' => 15000.00,
                'city' => 'İstanbul',
                'district' => 'Beşiktaş',
                'latitude' => 41.0422,
                'longitude' => 29.0081,
            ],
            [
                'title' => 'Sahilde Yürüyüş Yolu Genişletilsin',
                'description' => 'Hafta sonları çok kalabalık oluyor. Yürüyüş yolu daha geniş yapılabilir.',
                'status' => SuggestionStatusEnum::OPEN,
                'estimated_duration' => 90,
                'min_budget' => 100000.00,
                'max_budget' => 150000.00,
                'city' => 'İstanbul',
                'district' => 'Kadıköy',
                'latitude' => 40.9915,
                'longitude' => 29.0245,
            ],
            [
                'title' => 'Parkta Wi-Fi Erişimi Olsun',
                'description' => 'Parkta çalışmak veya internete erişmek isteyenler için ücretsiz Wi-Fi hizmeti verilebilir.',
                'status' => SuggestionStatusEnum::CLOSED,
                'estimated_duration' => 20,
                'min_budget' => 3000.00,
                'max_budget' => 5000.00,
                'city' => 'İstanbul',
                'district' => 'Maltepe',
                'latitude' => 40.9315,
                'longitude' => 29.1310,
            ],
            [
                'title' => 'Çocuk Parkında Güvenlik Kamerası',
                'description' => 'Çocukların güvenliği için park alanına kamera sistemi kurulmalı.',
                'status' => SuggestionStatusEnum::CLOSED,
                'estimated_duration' => 10,
                'min_budget' => 8000.00,
                'max_budget' => 12000.00,
                'city' => 'İstanbul',
                'district' => 'Üsküdar',
                'latitude' => 41.0218,
                'longitude' => 29.0258,
            ],
            [
                'title' => 'Caddeye Daha Fazla Bank Konulsun',
                'description' => 'Yaşlılar için cadde boyunca dinlenme bankları artırılabilir.',
                'status' => SuggestionStatusEnum::OPEN,
                'estimated_duration' => 7,
                'min_budget' => 5000.00,
                'max_budget' => 8000.00,
                'city' => 'İstanbul',
                'district' => 'Maltepe',
                'latitude' => 40.9312,
                'longitude' => 29.1305,
            ],
            [
                'title' => 'Otoparkta EV Şarj İstasyonu',
                'description' => 'Elektrikli araç sahipleri için şarj istasyonları kurulmalı.',
                'status' => SuggestionStatusEnum::OPEN,
                'estimated_duration' => 30,
                'min_budget' => 50000.00,
                'max_budget' => 75000.00,
                'city' => 'İstanbul',
                'district' => 'Beşiktaş',
                'latitude' => 41.0420,
                'longitude' => 29.0083,
            ],
        ];

        foreach ($suggestions as $suggestionData) {
            // Assign random user as creator
            $suggestionData['created_by_id'] = $users->random()->id;

            // Assign random project
            $project = $projects->random();
            $suggestionData['project_id'] = $project->id;

            $suggestion = Suggestion::create($suggestionData);

            $this->command->info("Suggestion created: {$suggestion->title}");
        }

        // Add 50 Random Suggestions
        $faker = \Faker\Factory::create('tr_TR');
        $districts = array_keys(config('istanbul_neighborhoods', ['Kadıköy' => [], 'Ataşehir' => [], 'Üsküdar' => [], 'Beşiktaş' => [], 'Maltepe' => [], 'Şişli' => []]));

        for ($i = 0; $i < 50; $i++) {
            $district = $faker->randomElement($districts);
            $project = $projects->random();
            $user = $users->random();

            $suggestion = Suggestion::create([
                'title' => $faker->realText(50),
                'description' => $faker->paragraph(2),
                'status' => $faker->randomElement([\App\Enums\SuggestionStatusEnum::OPEN, \App\Enums\SuggestionStatusEnum::CLOSED]),
                'estimated_duration' => $faker->numberBetween(1, 90),
                'min_budget' => $faker->randomFloat(2, 1000, 50000),
                'max_budget' => $faker->randomFloat(2, 60000, 200000),
                'city' => 'İstanbul',
                'district' => $district,
                'latitude' => $faker->latitude(40.8, 41.1),
                'longitude' => $faker->longitude(28.8, 29.4),
                'project_id' => $project->id,
                'created_by_id' => $user->id,
            ]);

            $this->command->info("Random Suggestion created: {$suggestion->title}");
        }

        $this->command->info('Suggestions created successfully!');
    }
}
