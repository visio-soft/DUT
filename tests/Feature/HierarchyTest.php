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

        // Create the hierarchy: Category > ProjectGroup > Project (many-to-many)
        $this->category = Category::create(['name' => 'Test Category']);
        $this->projectGroup = ProjectGroup::create([
            'name' => 'Test Project Group',
            'category_id' => $this->category->id,
        ]);
        $this->project = Project::create([
            'title' => 'Test Project',
            'category_id' => $this->category->id,
            'created_by_id' => $this->user->id,
        ]);

        // Attach project to project group
        $this->project->projectGroups()->attach($this->projectGroup->id);
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
        // ProjectGroup should have projects (many-to-many)
        $this->assertCount(1, $this->projectGroup->projects);
        $this->assertEquals($this->project->id, $this->projectGroup->projects->first()->id);
    }

    public function test_project_to_project_groups_relationship(): void
    {
        // Project can belong to multiple project groups
        $this->assertCount(1, $this->project->projectGroups);
        $this->assertEquals($this->projectGroup->id, $this->project->projectGroups->first()->id);
    }

    public function test_project_can_belong_to_multiple_groups(): void
    {
        // Create another project group
        $projectGroup2 = ProjectGroup::create([
            'name' => 'Test Project Group 2',
            'category_id' => $this->category->id,
        ]);

        // Attach project to second group
        $this->project->projectGroups()->attach($projectGroup2->id);

        // Project should belong to 2 groups
        $this->assertCount(2, $this->project->projectGroups);
        $this->assertTrue($this->project->projectGroups->contains($this->projectGroup));
        $this->assertTrue($this->project->projectGroups->contains($projectGroup2));
    }

    public function test_project_gets_category_through_project_group(): void
    {
        // Project should get category through first project group
        $this->assertEquals($this->category->id, $this->project->projectGroups->first()->category->id);
        $this->assertEquals('Test Category', $this->project->projectGroups->first()->category->name);
    }

    public function test_project_to_suggestion_relationship(): void
    {
        // Create a suggestion for the project
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'category_id' => $this->category->id,
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
            'category_id' => $this->category->id,
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Suggestion should belong to project
        $this->assertEquals($this->project->id, $suggestion->project->id);
        $this->assertEquals('Test Project', $suggestion->project->title);
    }

    public function test_suggestion_gets_project_groups_through_project(): void
    {
        // Create a suggestion
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'category_id' => $this->category->id,
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Suggestion should get project groups through project
        $this->assertCount(1, $suggestion->project->projectGroups);
        $this->assertEquals($this->projectGroup->id, $suggestion->project->projectGroups->first()->id);
    }

    public function test_suggestion_gets_category_through_project_and_project_group(): void
    {
        // Create a suggestion
        $suggestion = Suggestion::create([
            'title' => 'Test Suggestion',
            'category_id' => $this->category->id,
            'project_id' => $this->project->id,
            'created_by_id' => $this->user->id,
        ]);

        // Suggestion should get category through project > project group
        $firstGroup = $suggestion->project->projectGroups->first();
        $this->assertEquals($this->category->id, $firstGroup->category->id);
        $this->assertEquals('Test Category', $firstGroup->category->name);
    }

    public function test_project_has_no_project_group_id_fillable(): void
    {
        // Projects use many-to-many, not direct foreign key
        $project = new Project;
        $this->assertFalse(in_array('project_group_id', $project->getFillable()));
    }

    public function test_suggestion_has_project_id_fillable(): void
    {
        $suggestion = new Suggestion;
        $this->assertTrue(in_array('project_id', $suggestion->getFillable()));
    }

    public function test_project_has_category_id_fillable(): void
    {
        // Project needs category_id to be set directly (for backward compatibility)
        // Category can be inferred from project groups too
        $project = new Project;
        $this->assertTrue(in_array('category_id', $project->getFillable()));
    }

    public function test_suggestion_has_category_id_fillable(): void
    {
        // Suggestion needs category_id for direct assignment
        // Category can also be accessed through project > project groups
        $suggestion = new Suggestion;
        $this->assertTrue(in_array('category_id', $suggestion->getFillable()));
    }
}
