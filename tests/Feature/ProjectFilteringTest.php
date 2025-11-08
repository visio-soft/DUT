<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Oneri;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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
     * Test that category hierarchy works correctly.
     */
    public function test_category_can_have_parent_and_children(): void
    {
        $parentCategory = Category::create([
            'name' => 'Parent Category',
            'description' => 'This is a parent category',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
            'aktif' => true,
        ]);

        $childCategory = Category::create([
            'name' => 'Child Category',
            'description' => 'This is a child category',
            'parent_id' => $parentCategory->id,
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
            'aktif' => true,
        ]);

        $this->assertEquals($parentCategory->id, $childCategory->parent->id);
        $this->assertEquals(1, $parentCategory->children()->count());
        $this->assertTrue($parentCategory->aktif);
        $this->assertTrue($childCategory->aktif);
    }

    /**
     * Test that budget visibility toggle works.
     */
    public function test_budget_visibility_can_be_toggled(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
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
            'budget' => 50000,
            'hide_budget' => false,
        ]);

        $oneriHidden = Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Test Suggestion with Hidden Budget',
            'description' => 'Test Description',
            'budget' => 75000,
            'hide_budget' => true,
        ]);

        $this->assertFalse($oneriVisible->hide_budget);
        $this->assertTrue($oneriHidden->hide_budget);
        $this->assertEquals(50000, $oneriVisible->budget);
        $this->assertEquals(75000, $oneriHidden->budget);
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
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
        ]);

        $category2 = Category::create([
            'name' => 'Category 2',
            'description' => 'Description 2',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
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

        $response = $this->actingAs($user)->get('/projects?category=' . $category1->id);
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
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
        ]);

        $expiredCategory = Category::create([
            'name' => 'Expired Category',
            'description' => 'Expired Description',
            'start_datetime' => now()->subDays(60),
            'end_datetime' => now()->subDays(30),
            'neighborhood' => 'Test Neighborhood',
        ]);

        Oneri::create([
            'category_id' => $activeCategory->id,
            'created_by_id' => $user->id,
            'title' => 'Active Suggestion',
            'description' => 'Description',
        ]);

        Oneri::create([
            'category_id' => $expiredCategory->id,
            'created_by_id' => $user->id,
            'title' => 'Expired Suggestion',
            'description' => 'Description',
        ]);

        // Test active filter
        $response = $this->actingAs($user)->get('/projects?status=active');
        $response->assertStatus(200);
        $response->assertSee('Active Category');

        // Test expired filter
        $response = $this->actingAs($user)->get('/projects?status=expired');
        $response->assertStatus(200);
        $response->assertSee('Expired Category');
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
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
        ]);

        Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Low Budget Suggestion',
            'description' => 'Description',
            'budget' => 10000,
        ]);

        Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'High Budget Suggestion',
            'description' => 'Description',
            'budget' => 100000,
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
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
            'aktif' => true,
        ]);

        $inactiveCategory = Category::create([
            'name' => 'Inactive Category',
            'description' => 'Inactive Description',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
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
        $grandParent = Category::create([
            'name' => 'Grand Parent',
            'description' => 'Level 1',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
            'aktif' => true,
        ]);

        $parent = Category::create([
            'name' => 'Parent',
            'description' => 'Level 2',
            'parent_id' => $grandParent->id,
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
            'aktif' => true,
        ]);

        $child = Category::create([
            'name' => 'Child',
            'description' => 'Level 3',
            'parent_id' => $parent->id,
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'neighborhood' => 'Test Neighborhood',
            'aktif' => true,
        ]);

        // Test hierarchy paths
        $this->assertEquals('Grand Parent', $grandParent->getHierarchyPath());
        $this->assertEquals('Grand Parent > Parent', $parent->getHierarchyPath());
        $this->assertEquals('Grand Parent > Parent > Child', $child->getHierarchyPath());
    }

    /**
     * Test that multiple filters can be combined (category, location, date, status).
     */
    public function test_categories_can_be_filtered_with_multiple_criteria(): void
    {
        // Create active category in a specific district
        $activeCategory = Category::create([
            'name' => 'Active Category',
            'description' => 'Test',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'district' => 'Kadıköy',
            'neighborhood' => 'Moda',
            'aktif' => true,
        ]);

        // Create inactive category in a different district
        $inactiveCategory = Category::create([
            'name' => 'Inactive Category',
            'description' => 'Test',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(30),
            'district' => 'Beşiktaş',
            'neighborhood' => 'Ortaköy',
            'aktif' => false,
        ]);

        // Create expired category
        $expiredCategory = Category::create([
            'name' => 'Expired Category',
            'description' => 'Test',
            'start_datetime' => now()->subDays(60),
            'end_datetime' => now()->subDays(30),
            'district' => 'Kadıköy',
            'neighborhood' => 'Moda',
            'aktif' => true,
        ]);

        // Test combining aktif and district filters
        $filtered = Category::where('aktif', true)
            ->where('district', 'Kadıköy')
            ->get();
        
        $this->assertEquals(2, $filtered->count());
        $this->assertTrue($filtered->contains($activeCategory));
        $this->assertTrue($filtered->contains($expiredCategory));
        $this->assertFalse($filtered->contains($inactiveCategory));

        // Test combining aktif and date filters (active projects)
        $activeAndNotExpired = Category::where('aktif', true)
            ->where('end_datetime', '>', now())
            ->get();
        
        $this->assertEquals(1, $activeAndNotExpired->count());
        $this->assertTrue($activeAndNotExpired->contains($activeCategory));
    }
}
