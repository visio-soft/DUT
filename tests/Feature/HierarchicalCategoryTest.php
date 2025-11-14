<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HierarchicalCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function category_can_have_parent()
    {
        $parent = Category::create(['name' => 'Parent Category']);
        $child = Category::create(['name' => 'Child Category', 'parent_id' => $parent->id]);

        $this->assertInstanceOf(Category::class, $child->parent);
        $this->assertEquals($parent->id, $child->parent->id);
    }

    /** @test */
    public function category_can_have_children()
    {
        $parent = Category::create(['name' => 'Parent Category']);
        $child1 = Category::create(['name' => 'Child Category 1', 'parent_id' => $parent->id]);
        $child2 = Category::create(['name' => 'Child Category 2', 'parent_id' => $parent->id]);

        $children = $parent->children;
        
        $this->assertCount(2, $children);
        $this->assertTrue($children->contains($child1));
        $this->assertTrue($children->contains($child2));
    }

    /** @test */
    public function category_can_be_standalone_without_parent()
    {
        $category = Category::create(['name' => 'Standalone Category']);

        $this->assertNull($category->parent_id);
        $this->assertNull($category->parent);
    }

    /** @test */
    public function category_can_get_all_ancestors()
    {
        $grandparent = Category::create(['name' => 'Grandparent']);
        $parent = Category::create(['name' => 'Parent', 'parent_id' => $grandparent->id]);
        $child = Category::create(['name' => 'Child', 'parent_id' => $parent->id]);

        $ancestors = $child->ancestors();

        $this->assertCount(2, $ancestors);
        $this->assertEquals($parent->id, $ancestors[0]->id);
        $this->assertEquals($grandparent->id, $ancestors[1]->id);
    }

    /** @test */
    public function category_deletes_children_on_cascade()
    {
        $parent = Category::create(['name' => 'Parent Category']);
        $child = Category::create(['name' => 'Child Category', 'parent_id' => $parent->id]);

        $parent->delete();

        $this->assertSoftDeleted($parent);
        $this->assertSoftDeleted($child);
    }

    /** @test */
    public function parent_id_is_in_fillable_attributes()
    {
        $category = new Category();
        
        $this->assertContains('parent_id', $category->getFillable());
    }

    /** @test */
    public function category_hierarchy_can_be_three_levels_deep()
    {
        $level1 = Category::create(['name' => 'Level 1']);
        $level2 = Category::create(['name' => 'Level 2', 'parent_id' => $level1->id]);
        $level3 = Category::create(['name' => 'Level 3', 'parent_id' => $level2->id]);

        // Test going down the hierarchy
        $this->assertEquals($level2->id, $level1->children->first()->id);
        $this->assertEquals($level3->id, $level2->children->first()->id);

        // Test going up the hierarchy
        $this->assertEquals($level2->id, $level3->parent->id);
        $this->assertEquals($level1->id, $level2->parent->id);
    }
}
