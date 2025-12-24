<?php

namespace Database\Seeders;

use App\Models\Suggestion;
use App\Models\SuggestionLike;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuggestionLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all suggestions that are not projects (have a project_id)
        $suggestions = Suggestion::whereNotNull('project_id')->get();
        // Get all users
        $users = User::all();

        if ($suggestions->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No suggestions or users found. Skipping SuggestionLikeSeeder.');
            return;
        }

        $this->command->info('Seeding SuggestionLikes...');
        
        // We will create ~500 likes distributed across suggestions
        $count = 0;
        foreach ($suggestions as $suggestion) {
            // Random number of likes for this suggestion (e.g. 5 to 50)
            $numberOfLikes = rand(5, 50);

            for ($i = 0; $i < $numberOfLikes; $i++) {
                // Pick a random user
                $user = $users->random();
                
                // Avoid duplicate likes for same user-suggestion pair
                if (SuggestionLike::where('user_id', $user->id)
                        ->where('suggestion_id', $suggestion->id)
                        ->exists()) {
                    continue;
                }

                $age = rand(18, 65);
                $gender = rand(0, 1) ? 'male' : 'female';
                
                // Some logic to make data realistic: 
                // Maybe older people vote less? Or gender skew? 
                // Let's just keep it simple random for now.
                
                SuggestionLike::create([
                    'user_id' => $user->id,
                    'suggestion_id' => $suggestion->id,
                    'age' => $age,
                    'gender' => $gender,
                    'is_anonymous' => rand(0, 1) == 1,
                    // Simulate random creation time in the last 30 days
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }
        
        $this->command->info("Seeded {$count} new likes.");
    }
}
