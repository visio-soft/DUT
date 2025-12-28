<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\SuggestionCommentLike;
use App\Models\SuggestionLike;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerRefactoringTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected Suggestion $suggestion;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();

        // Create a category
        $this->category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);

        // Create a suggestion
        $this->suggestion = Suggestion::create([
            'category_id' => $this->category->id,
            'created_by_id' => $this->user->id,
            'title' => 'Test Suggestion',
            'description' => 'Test Description',
            'min_budget' => 1000,
            'max_budget' => 5000,
        ]);
    }

    public function test_suggestion_like_uses_correct_column_name(): void
    {
        // Act: Create a like using the correct column name
        $like = SuggestionLike::create([
            'user_id' => $this->user->id,
            'suggestion_id' => $this->suggestion->id,
        ]);

        // Assert: Check that the like was created with the correct column
        $this->assertDatabaseHas('suggestion_likes', [
            'user_id' => $this->user->id,
            'suggestion_id' => $this->suggestion->id,
        ]);

        // Verify the relationship works
        $this->assertEquals($this->suggestion->id, $like->suggestion_id);
    }

    public function test_suggestion_comment_uses_correct_column_name(): void
    {
        // Act: Create a comment using the correct column name
        $comment = SuggestionComment::create([
            'suggestion_id' => $this->suggestion->id,
            'user_id' => $this->user->id,
            'comment' => 'Test Comment',
            'is_approved' => true,
        ]);

        // Assert: Check that the comment was created with the correct column
        $this->assertDatabaseHas('suggestion_comments', [
            'suggestion_id' => $this->suggestion->id,
            'user_id' => $this->user->id,
            'comment' => 'Test Comment',
        ]);

        // Verify the relationship works
        $this->assertEquals($this->suggestion->id, $comment->suggestion_id);
    }

    public function test_suggestion_comment_like_uses_correct_model_and_column(): void
    {
        // Arrange: Create a comment first
        $comment = SuggestionComment::create([
            'suggestion_id' => $this->suggestion->id,
            'user_id' => $this->user->id,
            'comment' => 'Test Comment',
            'is_approved' => true,
        ]);

        // Act: Create a comment like using SuggestionCommentLike (not OneriCommentLike)
        $like = SuggestionCommentLike::create([
            'suggestion_comment_id' => $comment->id,
            'user_id' => $this->user->id,
        ]);

        // Assert: Check that the like was created with the correct column
        $this->assertDatabaseHas('suggestion_comment_likes', [
            'suggestion_comment_id' => $comment->id,
            'user_id' => $this->user->id,
        ]);

        // Verify the relationship works
        $this->assertEquals($comment->id, $like->suggestion_comment_id);
    }

    public function test_comment_reply_uses_correct_column_name(): void
    {
        // Arrange: Create a parent comment
        $parentComment = SuggestionComment::create([
            'suggestion_id' => $this->suggestion->id,
            'user_id' => $this->user->id,
            'comment' => 'Parent Comment',
            'is_approved' => true,
        ]);

        // Act: Create a reply using the correct column name
        $reply = SuggestionComment::create([
            'suggestion_id' => $this->suggestion->id,
            'user_id' => $this->user->id,
            'parent_id' => $parentComment->id,
            'comment' => 'Reply Comment',
            'is_approved' => true,
        ]);

        // Assert: Check that the reply was created with the correct columns
        $this->assertDatabaseHas('suggestion_comments', [
            'suggestion_id' => $this->suggestion->id,
            'parent_id' => $parentComment->id,
            'comment' => 'Reply Comment',
        ]);

        // Verify the relationships work
        $this->assertEquals($parentComment->id, $reply->parent_id);
        $this->assertEquals($this->suggestion->id, $reply->suggestion_id);
    }

    public function test_background_helper_method_exists_in_controller(): void
    {
        // This test ensures the refactored getBackgroundImageData method exists
        $reflection = new \ReflectionClass(\App\Http\Controllers\UserController::class);

        $this->assertTrue($reflection->hasMethod('getBackgroundImageData'));

        $method = $reflection->getMethod('getBackgroundImageData');
        $this->assertTrue($method->isPrivate());
    }

    public function test_duplicate_background_image_code_removed(): void
    {
        // Read the UserController file content
        $controllerPath = app_path('Http/Controllers/UserController.php');
        $content = file_get_contents($controllerPath);

        // Count occurrences of the old duplicated pattern
        $oldPattern = 'BackgroundImageHelper::hasBackgroundImages()';
        $occurrences = substr_count($content, $oldPattern);

        // Should only appear once in the helper method, not in each action method
        // The helper method + possibly one direct call
        $this->assertLessThanOrEqual(2, $occurrences,
            'Background image code appears to be duplicated. Expected maximum 2 occurrences (in helper method and possibly one direct use)');
    }
}
