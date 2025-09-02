<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectDesign;
use Illuminate\Console\Command;

class UpdateProjectDesignStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:update-design-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update design_completed status for all projects based on their design records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating project design status...');

        $projects = Project::all();
        $updatedCount = 0;

        foreach ($projects as $project) {
            $hasDesign = ProjectDesign::where('project_id', $project->id)->exists();
            
            if ($project->design_completed !== $hasDesign) {
                $project->update(['design_completed' => $hasDesign]);
                $updatedCount++;
                
                $status = $hasDesign ? 'has design' : 'no design';
                $this->line("Project #{$project->id} ({$project->title}) - {$status}");
            }
        }

        $this->info("Updated {$updatedCount} projects.");
        $this->info('Done!');

        return 0;
    }
}
