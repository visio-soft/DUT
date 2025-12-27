<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\SuggestionLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Demo Data Seeder...');
        
        // Get existing data
        $suggestions = Suggestion::whereNotNull('project_id')->get();
        $users = User::all();

        if ($suggestions->isEmpty()) {
            $this->command->error('❌ No suggestions found. Please run ProjectSeeder and SuggestionSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->error('❌ No users found. Please run UserSeeder first.');
            return;
        }

        $this->command->info("📊 Found {$suggestions->count()} suggestions and {$users->count()} users");

        // Clear existing likes and comments for fresh demo data
        $this->command->info('🧹 Clearing existing demo data...');
        SuggestionLike::truncate();
        SuggestionComment::truncate();

        // Seed likes
        $this->seedLikes($suggestions, $users);
        
        // Seed comments
        $this->seedComments($suggestions, $users);
        
        $this->command->info('✅ Demo Data Seeder completed successfully!');
    }

    private function seedLikes($suggestions, $users): void
    {
        $this->command->info('💖 Seeding likes...');
        
        $likeCount = 0;
        $genderStats = ['erkek' => 0, 'kadın' => 0, 'diğer' => 0, 'anonymous' => 0];
        
        foreach ($suggestions as $suggestion) {
            // Random number of likes: 10-100 for variety
            $numberOfLikes = rand(10, 100);
            $usedUserIds = [];
            
            for ($i = 0; $i < $numberOfLikes; $i++) {
                $user = $users->random();
                
                // Skip if user already liked this suggestion
                if (in_array($user->id, $usedUserIds)) {
                    continue;
                }
                $usedUserIds[] = $user->id;
                
                // Gender distribution: 45% erkek, 40% kadın, 5% diğer, 10% anonymous
                $random = rand(1, 100);
                $isAnonymous = $random > 90; // 10% anonymous
                
                if ($isAnonymous) {
                    $gender = null;
                    $age = null;
                    $genderStats['anonymous']++;
                } else {
                    if ($random <= 45) {
                        $gender = 'erkek';
                        $genderStats['erkek']++;
                    } elseif ($random <= 85) {
                        $gender = 'kadın';
                        $genderStats['kadın']++;
                    } else {
                        $gender = 'diğer';
                        $genderStats['diğer']++;
                    }
                    
                    // Age distribution: realistic spread
                    $ageRandom = rand(1, 100);
                    if ($ageRandom <= 20) {
                        $age = rand(18, 25); // 20% young adults
                    } elseif ($ageRandom <= 50) {
                        $age = rand(26, 35); // 30% young professionals
                    } elseif ($ageRandom <= 75) {
                        $age = rand(36, 50); // 25% middle aged
                    } else {
                        $age = rand(51, 70); // 25% seniors
                    }
                }
                
                SuggestionLike::create([
                    'user_id' => $user->id,
                    'suggestion_id' => $suggestion->id,
                    'age' => $age,
                    'gender' => $gender,
                    'is_anonymous' => $isAnonymous,
                    'created_at' => now()->subDays(rand(0, 60))->subHours(rand(0, 24)),
                    'updated_at' => now(),
                ]);
                
                $likeCount++;
            }
        }
        
        $this->command->info("   📈 Created {$likeCount} likes");
        $this->command->info("   👨 Erkek: {$genderStats['erkek']}");
        $this->command->info("   👩 Kadın: {$genderStats['kadın']}");
        $this->command->info("   🧑 Diğer: {$genderStats['diğer']}");
        $this->command->info("   🕵️ Anonim: {$genderStats['anonymous']}");
    }

    private function seedComments($suggestions, $users): void
    {
        $this->command->info('💬 Seeding comments...');
        
        $sampleComments = [
            'Bu öneri gerçekten harika! Kesinlikle destekliyorum.',
            'Çok güzel bir fikir, umarım hayata geçer.',
            'Bu konuda daha fazla detay verilmeli.',
            'Belediyemiz için çok önemli bir adım olacak.',
            'Uzun zamandır böyle bir şey bekliyorduk.',
            'Mükemmel bir öneri, tam da ihtiyacımız olan şey.',
            'Bu projeyi destekliyorum ama bazı değişiklikler yapılmalı.',
            'Çok iyi düşünülmüş bir öneri.',
            'Kesinlikle katılıyorum, bu değişiklik şart.',
            'Bu öneri hayata geçerse çok mutlu olurum.',
            'Emeği geçenlere teşekkürler.',
            'Bu konuda acil aksiyon alınmalı.',
            'Harika bir girişim, başarılar diliyorum.',
            'Umarım bu öneri en çok oyu alır.',
            'Çevremiz için çok faydalı olacak.',
            'Bu fikre bayıldım!',
            'Keşke daha önce düşünülseydi.',
            'Ailece çok memnun kalacağız.',
            'Çocuklarımız için harika bir yatırım.',
            'Yeşil alanları seven biri olarak destekliyorum.',
        ];

        $sampleReplies = [
            'Kesinlikle katılıyorum!',
            'Ben de aynı düşüncedeyim.',
            'Güzel bir bakış açısı.',
            'Teşekkürler, çok haklısınız.',
            'Bu konuda hemfikiriz.',
            'İyi bir nokta yakalamışsınız.',
            'Evet, aynen öyle.',
            'Destekliyorum bu görüşü.',
        ];

        $commentCount = 0;
        $replyCount = 0;

        foreach ($suggestions as $suggestion) {
            // Random number of comments: 3-15
            $numberOfComments = rand(3, 15);
            
            for ($i = 0; $i < $numberOfComments; $i++) {
                $user = $users->random();
                
                $comment = SuggestionComment::create([
                    'suggestion_id' => $suggestion->id,
                    'user_id' => $user->id,
                    'comment' => $sampleComments[array_rand($sampleComments)],
                    'is_approved' => rand(1, 10) <= 8, // 80% approved
                    'parent_id' => null,
                    'created_at' => now()->subDays(rand(0, 45))->subHours(rand(0, 24)),
                    'updated_at' => now(),
                ]);
                
                $commentCount++;
                
                // Add 0-3 replies to some comments
                if (rand(1, 3) === 1) {
                    $numberOfReplies = rand(1, 3);
                    
                    for ($j = 0; $j < $numberOfReplies; $j++) {
                        $replyUser = $users->random();
                        
                        SuggestionComment::create([
                            'suggestion_id' => $suggestion->id,
                            'user_id' => $replyUser->id,
                            'comment' => $sampleReplies[array_rand($sampleReplies)],
                            'is_approved' => rand(1, 10) <= 8, // 80% approved
                            'parent_id' => $comment->id,
                            'created_at' => $comment->created_at->addHours(rand(1, 48)),
                            'updated_at' => now(),
                        ]);
                        
                        $replyCount++;
                    }
                }
            }
        }
        
        $this->command->info("   💬 Created {$commentCount} comments");
        $this->command->info("   ↩️ Created {$replyCount} replies");
    }
}
