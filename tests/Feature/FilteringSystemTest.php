<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Enums\SuggestionStatusEnum;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilteringSystemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function projects_can_be_filtered_by_status()
    {
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);

        $activeProject = Project::create([
            'title' => 'Active Project',
            'status' => ProjectStatusEnum::ACTIVE,
        ]);
        $activeProject->projectGroups()->attach($projectGroup->id);

        $draftProject = Project::create([
            'title' => 'Draft Project',
            'status' => ProjectStatusEnum::DRAFT,
        ]);
        $draftProject->projectGroups()->attach($projectGroup->id);

        $activeProjects = Project::where('status', ProjectStatusEnum::ACTIVE)->get();
        $draftProjects = Project::where('status', ProjectStatusEnum::DRAFT)->get();

        $this->assertCount(1, $activeProjects);
        $this->assertCount(1, $draftProjects);
        $this->assertEquals('Active Project', $activeProjects->first()->title);
    }

    /** @test */
    public function suggestions_can_be_filtered_by_status()
    {
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);

        $openSuggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Open Suggestion',
            'status' => SuggestionStatusEnum::OPEN,
        ]);

        $closedSuggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Closed Suggestion',
            'status' => SuggestionStatusEnum::CLOSED,
        ]);

        $openSuggestions = Suggestion::where('status', SuggestionStatusEnum::OPEN)->get();
        $closedSuggestions = Suggestion::where('status', SuggestionStatusEnum::CLOSED)->get();

        $this->assertCount(1, $openSuggestions);
        $this->assertCount(1, $closedSuggestions);
        $this->assertEquals('Open Suggestion', $openSuggestions->first()->title);
    }

    /** @test */
    public function projects_can_be_filtered_by_neighborhood()
    {
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);

        $project1 = Project::create([
            'title' => 'Project in Kadıköy',
            'district' => 'Kadıköy',
            'neighborhood' => 'Moda',
        ]);
        $project1->projectGroups()->attach($projectGroup->id);

        $project2 = Project::create([
            'title' => 'Project in Beşiktaş',
            'district' => 'Beşiktaş',
            'neighborhood' => 'Ortaköy',
        ]);
        $project2->projectGroups()->attach($projectGroup->id);

        $kadiköyProjects = Project::where('district', 'Kadıköy')->get();
        $besiktasProjects = Project::where('district', 'Beşiktaş')->get();

        $this->assertCount(1, $kadiköyProjects);
        $this->assertCount(1, $besiktasProjects);
        $this->assertEquals('Moda', $kadiköyProjects->first()->neighborhood);
    }

    /** @test */
    public function projects_can_be_filtered_by_date_range()
    {
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);

        $earlyProject = Project::create([
            'title' => 'Early Project',
            'start_date' => '2025-01-01',
            'end_date' => '2025-02-01',
        ]);
        $earlyProject->projectGroups()->attach($projectGroup->id);

        $lateProject = Project::create([
            'title' => 'Late Project',
            'start_date' => '2025-06-01',
            'end_date' => '2025-07-01',
        ]);
        $lateProject->projectGroups()->attach($projectGroup->id);

        $projectsStartingInJanuary = Project::where('start_date', '>=', '2025-01-01')
            ->where('start_date', '<', '2025-02-01')
            ->get();

        $this->assertCount(1, $projectsStartingInJanuary);
        $this->assertEquals('Early Project', $projectsStartingInJanuary->first()->title);
    }

    /** @test */
    public function projects_can_be_filtered_by_budget_range()
    {
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);

        $lowBudgetProject = Project::create([
            'title' => 'Low Budget Project',
            'min_budget' => 1000,
            'max_budget' => 5000,
        ]);
        $lowBudgetProject->projectGroups()->attach($projectGroup->id);

        $highBudgetProject = Project::create([
            'title' => 'High Budget Project',
            'min_budget' => 50000,
            'max_budget' => 100000,
        ]);
        $highBudgetProject->projectGroups()->attach($projectGroup->id);

        $affordableProjects = Project::where('min_budget', '>=', 1000)
            ->where('max_budget', '<=', 10000)
            ->get();

        $this->assertCount(1, $affordableProjects);
        $this->assertEquals('Low Budget Project', $affordableProjects->first()->title);
    }

    /** @test */
    public function multiple_filters_can_be_combined()
    {
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);

        $matchingProject = Project::create([
            'title' => 'Matching Project',
            'status' => ProjectStatusEnum::ACTIVE,
            'district' => 'Kadıköy',
            'neighborhood' => 'Moda',
            'min_budget' => 1000,
            'max_budget' => 5000,
            'start_date' => '2025-01-01',
        ]);
        $matchingProject->projectGroups()->attach($projectGroup->id);

        $nonMatchingProject = Project::create([
            'title' => 'Non-matching Project',
            'status' => ProjectStatusEnum::DRAFT,
            'district' => 'Beşiktaş',
            'neighborhood' => 'Ortaköy',
            'min_budget' => 50000,
            'max_budget' => 100000,
            'start_date' => '2025-06-01',
        ]);
        $nonMatchingProject->projectGroups()->attach($projectGroup->id);

        $filteredProjects = Project::where('status', ProjectStatusEnum::ACTIVE)
            ->where('district', 'Kadıköy')
            ->where('min_budget', '>=', 1000)
            ->where('max_budget', '<=', 10000)
            ->get();

        $this->assertCount(1, $filteredProjects);
        $this->assertEquals('Matching Project', $filteredProjects->first()->title);
    }

    /** @test */
    public function status_field_is_in_fillable_attributes()
    {
        $project = new Project;
        $suggestion = new Suggestion;

        $this->assertContains('status', $project->getFillable());
        $this->assertContains('status', $suggestion->getFillable());
    }
}
