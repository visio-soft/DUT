<?php

namespace Database\Seeders;

use App\Models\ProjectDesign;
use App\Models\ProjectDesignLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectDesignLikesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // If the project_designs table doesn't exist yet (migrations running), skip seeding likes
        if (!\Illuminate\Support\Facades\Schema::hasTable('project_designs')) {
            $this->command->info('Table `project_designs` does not exist yet. Skipping ProjectDesignLikesSeeder.');
            return;
        }

        // Get all users and project designs
        $users = User::all();
        $projectDesigns = ProjectDesign::all();

        if ($users->isEmpty() || $projectDesigns->isEmpty()) {
            $this->command->info('No users or project designs found. Skipping likes seeder.');
            return;
        }

        $this->command->info('Seeding project design likes...');

        // Clear existing likes
        ProjectDesignLike::truncate();

        // Add some random likes
        foreach ($projectDesigns as $design) {
            // Randomly select 1-5 users to like this design
            $randomUsers = $users->random(rand(1, min(5, $users->count())));

            foreach ($randomUsers as $user) {
                ProjectDesignLike::create([
                    'user_id' => $user->id,
                    'project_design_id' => $design->id,
                ]);
            }
        }

        $totalLikes = ProjectDesignLike::count();
        $this->command->info("Created {$totalLikes} project design likes.");
    }
}
