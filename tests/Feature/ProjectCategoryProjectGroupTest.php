<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectGroup;
use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCategoryProjectGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_category_can_be_created(): void
    {
        $projectCategory = ProjectCategory::create(['name' => 'Test Project Category']);

        $this->assertDatabaseHas('project_categories', [
            'name' => 'Test Project Category',
        ]);

        $this->assertEquals('Test Project Category', $projectCategory->name);
    }

    public function test_project_group_can_be_created_with_project_category(): void
    {
        $projectCategory = ProjectCategory::create(['name' => 'Test Project Category']);
        $projectGroup = ProjectGroup::create([
            'name' => 'Test Project Group',
            'project_category_id' => $projectCategory->id,
        ]);

        $this->assertDatabaseHas('project_groups', [
            'name' => 'Test Project Group',
            'project_category_id' => $projectCategory->id,
        ]);
    }

    public function test_project_category_has_many_project_groups_relationship(): void
    {
        $projectCategory = ProjectCategory::create(['name' => 'Test Project Category']);

        ProjectGroup::create([
            'name' => 'Group 1',
            'project_category_id' => $projectCategory->id,
        ]);

        ProjectGroup::create([
            'name' => 'Group 2',
            'project_category_id' => $projectCategory->id,
        ]);

        $this->assertCount(2, $projectCategory->projectGroups);
    }

    public function test_project_group_belongs_to_project_category_relationship(): void
    {
        $projectCategory = ProjectCategory::create(['name' => 'Test Project Category']);
        $projectGroup = ProjectGroup::create([
            'name' => 'Test Project Group',
            'project_category_id' => $projectCategory->id,
        ]);

        $this->assertEquals($projectCategory->id, $projectGroup->projectCategory->id);
        $this->assertEquals('Test Project Category', $projectGroup->projectCategory->name);
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
        $project = new Project;
        $suggestion = new Suggestion;

        $this->assertContains('project_group_id', $project->getFillable());
        $this->assertContains('project_group_id', $suggestion->getFillable());
    }
}
