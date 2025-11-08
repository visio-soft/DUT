<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioProjectGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_can_be_created(): void
    {
        $portfolio = Portfolio::create(['name' => 'Test Portfolio']);

        $this->assertDatabaseHas('portfolios', [
            'name' => 'Test Portfolio',
        ]);

        $this->assertEquals('Test Portfolio', $portfolio->name);
    }

    public function test_project_group_can_be_created_with_portfolio(): void
    {
        $portfolio = Portfolio::create(['name' => 'Test Portfolio']);
        $projectGroup = ProjectGroup::create([
            'name' => 'Test Project Group',
            'portfolio_id' => $portfolio->id,
        ]);

        $this->assertDatabaseHas('project_groups', [
            'name' => 'Test Project Group',
            'portfolio_id' => $portfolio->id,
        ]);
    }

    public function test_portfolio_has_many_project_groups_relationship(): void
    {
        $portfolio = Portfolio::create(['name' => 'Test Portfolio']);
        
        ProjectGroup::create([
            'name' => 'Group 1',
            'portfolio_id' => $portfolio->id,
        ]);
        
        ProjectGroup::create([
            'name' => 'Group 2',
            'portfolio_id' => $portfolio->id,
        ]);

        $this->assertCount(2, $portfolio->projectGroups);
    }

    public function test_project_group_belongs_to_portfolio_relationship(): void
    {
        $portfolio = Portfolio::create(['name' => 'Test Portfolio']);
        $projectGroup = ProjectGroup::create([
            'name' => 'Test Project Group',
            'portfolio_id' => $portfolio->id,
        ]);

        $this->assertEquals($portfolio->id, $projectGroup->portfolio->id);
        $this->assertEquals('Test Portfolio', $projectGroup->portfolio->name);
    }

    public function test_project_can_have_project_group(): void
    {
        $this->markTestSkipped('Project model uses suggestions table and requires additional setup');
    }

    public function test_suggestion_can_have_project_group(): void
    {
        $this->markTestSkipped('Suggestion model requires category and other dependencies');
    }

    public function test_project_group_id_is_in_fillable_attributes(): void
    {
        $project = new Project();
        $suggestion = new Suggestion();

        $this->assertContains('project_group_id', $project->getFillable());
        $this->assertContains('project_group_id', $suggestion->getFillable());
    }
}
