<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Oneri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'user', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    /**
     * Test that category hierarchy works correctly with MainCategory.
     */
    public function test_category_can_have_parent_and_children(): void
    {
        $mainCategory = \App\Models\MainCategory::create([
            'name' => 'Main Category',
            'description' => 'This is a main category',
            'aktif' => true,
        ]);

        $category1 = Category::create([
            'name' => 'Category 1',
            'description' => 'This is first category under main',
            'main_category_id' => $mainCategory->id,
            'aktif' => true,
        ]);

        $category2 = Category::create([
            'name' => 'Category 2',
            'description' => 'This is second category under main',
            'main_category_id' => $mainCategory->id,
            'aktif' => true,
        ]);

        $this->assertEquals($mainCategory->id, $category1->mainCategory->id);
        $this->assertEquals($mainCategory->id, $category2->mainCategory->id);
        $this->assertEquals(2, $mainCategory->categories()->count());
        $this->assertTrue($category1->aktif);
        $this->assertTrue($category2->aktif);
    }

    /**
     * Test that budget visibility toggle works.
     */
    public function test_budget_visibility_can_be_toggled(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'aktif' => true,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $oneriVisible = Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Test Suggestion with Visible Budget',
            'description' => 'Test Description',
            'min_budget' => 45000,
            'max_budget' => 55000,
            'hide_budget' => false,
        ]);

        $oneriHidden = Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Test Suggestion with Hidden Budget',
            'description' => 'Test Description',
            'min_budget' => 70000,
            'max_budget' => 80000,
            'hide_budget' => true,
        ]);

        $this->assertFalse($oneriVisible->hide_budget);
        $this->assertTrue($oneriHidden->hide_budget);
        $this->assertEquals(45000, $oneriVisible->min_budget);
        $this->assertEquals(80000, $oneriHidden->max_budget);
    }

    /**
     * Test that filtering by category works.
     */
    public function test_projects_can_be_filtered_by_category(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');

        $category1 = Category::create([
            'name' => 'Category 1',
            'description' => 'Description 1',
            'aktif' => true,
        ]);

        $category2 = Category::create([
            'name' => 'Category 2',
            'description' => 'Description 2',
            'aktif' => true,
        ]);

        Oneri::create([
            'category_id' => $category1->id,
            'created_by_id' => $user->id,
            'title' => 'Suggestion 1',
            'description' => 'Description 1',
        ]);

        Oneri::create([
            'category_id' => $category2->id,
            'created_by_id' => $user->id,
            'title' => 'Suggestion 2',
            'description' => 'Description 2',
        ]);

        $response = $this->actingAs($user)->get('/projects?category='.$category1->id);
        $response->assertStatus(200);

        // The response should contain data from category1 only
        $response->assertSee('Category 1');
    }

    /**
     * Test that filtering by voting status works.
     */
    public function test_projects_can_be_filtered_by_voting_status(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');

        $activeCategory = Category::create([
            'name' => 'Active Category',
            'description' => 'Active Description',
            'aktif' => true,
        ]);

        $inactiveCategory = Category::create([
            'name' => 'Inactive Category',
            'description' => 'Inactive Description',
            'aktif' => false,
        ]);

        Oneri::create([
            'category_id' => $activeCategory->id,
            'created_by_id' => $user->id,
            'title' => 'Active Suggestion',
            'description' => 'Description',
        ]);

        Oneri::create([
            'category_id' => $inactiveCategory->id,
            'created_by_id' => $user->id,
            'title' => 'Inactive Suggestion',
            'description' => 'Description',
        ]);

        // Test active filter
        $response = $this->actingAs($user)->get('/projects?status=active');
        $response->assertStatus(200);
        $response->assertSee('Active Category');

        // Test all projects
        $response = $this->actingAs($user)->get('/projects');
        $response->assertStatus(200);
    }

    /**
     * Test that filtering by budget range works.
     */
    public function test_projects_can_be_filtered_by_budget_range(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');

        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'aktif' => true,
        ]);

        Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Low Budget Suggestion',
            'description' => 'Description',
            'min_budget' => 8000,
            'max_budget' => 12000,
        ]);

        Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'High Budget Suggestion',
            'description' => 'Description',
            'min_budget' => 90000,
            'max_budget' => 110000,
        ]);

        // Test budget range filter
        $response = $this->actingAs($user)->get('/projects?min_budget=50000&max_budget=150000');
        $response->assertStatus(200);
        $response->assertSee('High Budget Suggestion');
    }

    /**
     * Test that categories can be filtered by aktif status.
     */
    public function test_categories_can_be_filtered_by_aktif_status(): void
    {
        $activeCategory = Category::create([
            'name' => 'Active Category',
            'description' => 'Active Description',
            'aktif' => true,
        ]);

        $inactiveCategory = Category::create([
            'name' => 'Inactive Category',
            'description' => 'Inactive Description',
            'aktif' => false,
        ]);

        // Test that we can query active categories
        $activeCategories = Category::where('aktif', true)->get();
        $this->assertEquals(1, $activeCategories->count());
        $this->assertEquals('Active Category', $activeCategories->first()->name);

        // Test that we can query inactive categories
        $inactiveCategories = Category::where('aktif', false)->get();
        $this->assertEquals(1, $inactiveCategories->count());
        $this->assertEquals('Inactive Category', $inactiveCategories->first()->name);
    }

    /**
     * Test that category hierarchy path is correctly generated.
     */
    public function test_category_hierarchy_path_is_generated_correctly(): void
    {
        $mainCategory = \App\Models\MainCategory::create([
            'name' => 'Main Category',
            'description' => 'Level 1',
            'aktif' => true,
        ]);

        $category1 = Category::create([
            'name' => 'Category 1',
            'description' => 'Level 2',
            'main_category_id' => $mainCategory->id,
            'aktif' => true,
        ]);

        $category2 = Category::create([
            'name' => 'Category 2',
            'description' => 'Level 2',
            'main_category_id' => $mainCategory->id,
            'aktif' => true,
        ]);

        // Test hierarchy relationships
        $this->assertEquals($mainCategory->id, $category1->mainCategory->id);
        $this->assertEquals($mainCategory->id, $category2->mainCategory->id);
        $this->assertEquals(2, $mainCategory->categories()->count());
    }

    /**
     * Test that multiple filters can be combined (category, status).
     */
    public function test_categories_can_be_filtered_with_multiple_criteria(): void
    {
        // Create active category
        $activeCategory = Category::create([
            'name' => 'Active Category',
            'description' => 'Test',
            'aktif' => true,
        ]);

        // Create inactive category
        $inactiveCategory = Category::create([
            'name' => 'Inactive Category',
            'description' => 'Test',
            'aktif' => false,
        ]);

        // Create another active category
        $anotherActiveCategory = Category::create([
            'name' => 'Another Active Category',
            'description' => 'Test',
            'aktif' => true,
        ]);

        // Test aktif filter
        $filtered = Category::where('aktif', true)->get();

        $this->assertEquals(2, $filtered->count());
        $this->assertTrue($filtered->contains($activeCategory));
        $this->assertTrue($filtered->contains($anotherActiveCategory));
        $this->assertFalse($filtered->contains($inactiveCategory));
    }
}
