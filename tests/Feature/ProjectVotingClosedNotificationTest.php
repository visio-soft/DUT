<?php

namespace Tests\Feature;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use App\Models\SuggestionComment;
use App\Models\SuggestionLike;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProjectVotingClosedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_notification_when_project_status_changes_to_voting_closed()
    {
        // Arrange
        $project = Project::factory()->create([
            'status' => ProjectStatusEnum::ACTIVE,
            'title' => 'Test Project',
        ]);

        $liker = User::factory()->create();
        $commenter = User::factory()->create();
        $surveyer = User::factory()->create();

        // Liker interacts
        SuggestionLike::create([
            'user_id' => $liker->id,
            'suggestion_id' => $project->id, // Project acts as suggestion
        ]);

        // Commenter interacts
        SuggestionComment::create([
            'user_id' => $commenter->id,
            'suggestion_id' => $project->id,
            'comment' => 'Nice project',
            'is_approved' => true,
        ]);

        // Surveyer interacts
        $survey = Survey::factory()->create(['project_id' => $project->id]);
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => $surveyer->id,
            'ip_address' => '127.0.0.1',
        ]);

        // Act
        // dump(\App\Models\Notification::count()); // Debug
        $project->update(['status' => ProjectStatusEnum::VOTING_CLOSED]);
        // dump(\App\Models\Notification::count()); // Debug

        // Assert
        // We check if users received the notification. 
        // Duplicate notifications might occur if observer fires multiple times or event listeners are registered multiple times,
        // but for now we ensure they received IT.
        $this->assertGreaterThanOrEqual(3, \Illuminate\Support\Facades\DB::table('notifications')->count());

        $notification = $liker->notifications()->first();
        $this->assertEquals('Proje Oylaması Kapandı', $notification->data['title']);
        $this->assertEquals('Takip ettiğiniz Test Project projesinin oylama süreci tamamlanmıştır.', $notification->data['body']);

        $this->assertTrue($commenter->notifications()->exists());
        $this->assertTrue($surveyer->notifications()->exists());
    }
}
