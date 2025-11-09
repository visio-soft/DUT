<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HierarchyTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected ProjectGroup $projectGroup;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create the hierarchy: Category > ProjectGroup > Project
        $this->category = Category::create(['name' => 'Test Category']);
        $this->projectGroup = ProjectGroup::create([
            'name' => 'Test Project Group',
            'category_id' => $this->category->id,
        ]);
        $this->project = Project::create([
            'title' => 'Test Project',
            'project_group_id' => $this->projectGroup->id,
            'created_by_id' => $this->user->id,
        ]);
    }

    public function test_category_to_project_group_relationship(): void
    {
        // Category should have project groups
        $this->assertCount(1, $this->category->projectGroups);
        $this->assertEquals($this->projectGroup->id, $this->category->projectGroups->first()->id);
    }

    public function test_project_group_to_category_relationship(): void
    {
        // ProjectGroup should belong to category
        $this->assertEquals($this->category->id, $this->projectGroup->category->id);
        $this->assertEquals('Test Category', $this->projectGroup->category->name);
    }

    public function test_project_group_to_project_relationship(): void
    {
        // ProjectGroup should have projects
        $this->assertCount(1, $this->projectGroup->projects);
        $this->assertEquals($this->project->id, $this->projectGroup->projects->first()->id);
    }

    public function test_project_to_project_group_relationship(): void
    {
        // Project should belong to project group
        $this->assertEquals($this->projectGroup->id, $this->project->projectGroup->id);
        $this->assertEquals('Test Project Group', $this->project->projectGroup->name);
    }

    public function test_project_gets_category_through_project_group(): void
    {
        // Project should get category through project group
        $this->assertEquals($this->category->id, $this->project->projectGroup->category->id);
        $this->assertEquals('Test Category', $this->project->projectGroup->category->name);
    }

    public function test_project_to_suggestion_relationship(): void
    {
        // Create a suggestion for the project
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Project should have suggestions
        $this->assertCount(1, $this->project->suggestions);
        $this->assertEquals($suggestion->id, $this->project->suggestions->first()->id);
    }

    public function test_suggestion_to_project_relationship(): void
    {
        // Create a suggestion
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Suggestion should belong to project
        $this->assertEquals($this->project->id, $suggestion->project->id);
        $this->assertEquals('Test Project', $suggestion->project->title);
    }

    public function test_suggestion_gets_project_group_through_project(): void
    {
        // Create a suggestion
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Suggestion should get project group through project
        $this->assertEquals($this->projectGroup->id, $suggestion->project->projectGroup->id);
        $this->assertEquals('Test Project Group', $suggestion->project->projectGroup->name);
    }

    public function test_suggestion_gets_category_through_project_and_project_group(): void
    {
        // Create a suggestion
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Suggestion should get category through project > project group
        $this->assertEquals($this->category->id, $suggestion->project->projectGroup->category->id);
        $this->assertEquals('Test Category', $suggestion->project->projectGroup->category->name);
    }

    public function test_category_has_many_through_to_projects(): void
    {
        // Create another project in a different group
        $projectGroup2 = ProjectGroup::create([
            'name' => 'Test Project Group 2',
            'category_id' => $this->category->id,
        ]);
        $project2 = Project::create([
            'title' => 'Test Project 2',
            'project_group_id' => $projectGroup2->id,
            'created_by_id' => $this->user->id,
        ]);

        // Category should have access to all projects through project groups
        $this->assertCount(2, $this->category->projects);
    }

    public function test_project_has_project_group_id_fillable(): void
    {
        $project = new Project();
        $this->assertTrue(in_array('project_group_id', $project->getFillable()));
    }

    public function test_suggestion_has_project_id_fillable(): void
    {
        $suggestion = new Suggestion();
        $this->assertTrue(in_array('project_id', $suggestion->getFillable()));
    }

    public function test_project_does_not_have_category_id_fillable(): void
    {
        // Project gets category through project group, so category_id shouldn't be fillable
        $project = new Project();
        $this->assertFalse(in_array('category_id', $project->getFillable()));
    }

    public function test_suggestion_does_not_have_category_id_fillable(): void
    {
        // Suggestion gets category through project, so category_id shouldn't be fillable
        $suggestion = new Suggestion();
        $this->assertFalse(in_array('category_id', $suggestion->getFillable()));
    }
}
