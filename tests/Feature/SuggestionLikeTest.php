<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Oneri;
use App\Models\OneriLike;
use App\Models\Category;
use Carbon\Carbon;
use Livewire\Livewire;

class SuggestionLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_only_one_suggestion_per_project()
    {
        // Create user
        $user = User::factory()->create();

        // Create required category
        $category = Category::create(['name' => 'Test Category']);

        // Create a project (root oneri)
        $project = Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Project Root',
            'description' => 'Root project',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(7)->toDateString(),
            'budget' => 1000,
            'district' => 'Test District',
        ]);

        // Create two suggestions under the project
        $s1 = Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Suggestion 1',
            'description' => 'Suggestion one',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(7)->toDateString(),
            'budget' => 100,
            'district' => 'Test District',
            'project_id' => $project->id,
        ]);

        $s2 = Oneri::create([
            'category_id' => $category->id,
            'created_by_id' => $user->id,
            'title' => 'Suggestion 2',
            'description' => 'Suggestion two',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(7)->toDateString(),
            'budget' => 150,
            'district' => 'Test District',
            'project_id' => $project->id,
        ]);

        $this->actingAs($user);

        // Like first suggestion
    Livewire::test(\App\Http\Livewire\SuggestionLike::class, ['suggestion' => $s1])
            ->call('toggleLike')
            ->assertSet('liked', true)
            ->assertSet('likesCount', 1);

        $this->assertDatabaseHas('oneri_likes', [
            'user_id' => $user->id,
            'oneri_id' => $s1->id,
        ]);

        // Like second suggestion (should remove like from the first)
    Livewire::test(\App\Http\Livewire\SuggestionLike::class, ['suggestion' => $s2])
            ->call('toggleLike')
            ->assertSet('liked', true)
            ->assertSet('likesCount', 1);

        // First like should be removed
        $this->assertDatabaseMissing('oneri_likes', [
            'user_id' => $user->id,
            'oneri_id' => $s1->id,
        ]);

        // Second like should exist
        $this->assertDatabaseHas('oneri_likes', [
            'user_id' => $user->id,
            'oneri_id' => $s2->id,
        ]);
    }
}
