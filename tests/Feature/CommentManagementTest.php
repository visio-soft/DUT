<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function comment_can_be_created()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        $comment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment',
            'is_approved' => false,
        ]);

        $this->assertDatabaseHas('suggestion_comments', [
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment',
            'is_approved' => false,
        ]);
    }

    /** @test */
    public function comment_can_be_approved()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        $comment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment',
            'is_approved' => false,
        ]);

        $comment->update(['is_approved' => true]);

        $this->assertTrue($comment->fresh()->is_approved);
    }

    /** @test */
    public function only_approved_comments_are_returned_by_scope()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        $approvedComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Approved comment',
            'is_approved' => true,
        ]);

        $pendingComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Pending comment',
            'is_approved' => false,
        ]);

        $approvedComments = SuggestionComment::approved()->get();

        $this->assertCount(1, $approvedComments);
        $this->assertTrue($approvedComments->first()->is($approvedComment));
    }

    /** @test */
    public function only_pending_comments_are_returned_by_scope()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        $approvedComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Approved comment',
            'is_approved' => true,
        ]);

        $pendingComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Pending comment',
            'is_approved' => false,
        ]);

        $pendingComments = SuggestionComment::pending()->get();

        $this->assertCount(1, $pendingComments);
        $this->assertTrue($pendingComments->first()->is($pendingComment));
    }

    /** @test */
    public function comment_can_have_parent()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        $parentComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Parent comment',
            'is_approved' => true,
        ]);

        $childComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'parent_id' => $parentComment->id,
            'comment' => 'Child comment',
            'is_approved' => true,
        ]);

        $this->assertNotNull($childComment->parent);
        $this->assertTrue($childComment->parent->is($parentComment));
    }

    /** @test */
    public function comment_can_have_replies()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        $parentComment = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Parent comment',
            'is_approved' => true,
        ]);

        $reply1 = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'parent_id' => $parentComment->id,
            'comment' => 'Reply 1',
            'is_approved' => true,
        ]);

        $reply2 = SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'parent_id' => $parentComment->id,
            'comment' => 'Reply 2',
            'is_approved' => true,
        ]);

        $replies = $parentComment->replies;

        $this->assertCount(2, $replies);
    }

    /** @test */
    public function suggestion_approved_comments_count_is_correct()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);
        $projectGroup = ProjectGroup::create(['name' => 'Test Group', 'category_id' => $category->id]);
        $project = Project::create(['title' => 'Test Project']);
        $project->projectGroups()->attach($projectGroup->id);
        
        $suggestion = Suggestion::create([
            'project_id' => $project->id,
            'title' => 'Test Suggestion',
        ]);

        SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Approved comment 1',
            'is_approved' => true,
        ]);

        SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Approved comment 2',
            'is_approved' => true,
        ]);

        SuggestionComment::create([
            'suggestion_id' => $suggestion->id,
            'user_id' => $user->id,
            'comment' => 'Pending comment',
            'is_approved' => false,
        ]);

        $this->assertEquals(2, $suggestion->approved_comments_count);
    }
}
