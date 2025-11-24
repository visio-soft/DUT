<?php

namespace App\Filament\Pages\UserPanel;

use App\Helpers\BackgroundImageHelper;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use Illuminate\Support\Facades\Auth;

class SuggestionDetail
{
    /**
     * Display suggestion detail page
     */
    public function show($id)
    {
        $suggestion = Suggestion::with([
            'category',
            'likes.user',
            'approvedComments.user',
            'approvedComments.likes.user',
            'approvedComments.approvedReplies.user',
            'approvedComments.approvedReplies.likes.user',
            'createdBy',
        ])
            ->findOrFail($id);

        // Get unapproved comments by the user for this suggestion (both main comments and replies)
        $userPendingComments = collect();
        if (Auth::check()) {
            $userPendingComments = SuggestionComment::with(['user', 'parent.user'])
                ->where('suggestion_id', $id)
                ->where('user_id', Auth::id())
                ->where('is_approved', false)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $backgroundData = $this->getBackgroundImageData();

        return view('filament.pages.user-panel.suggestion-detail', array_merge(
            compact('suggestion', 'userPendingComments'),
            $backgroundData
        ));
    }

    /**
     * Get background image data for views
     */
    private function getBackgroundImageData(): array
    {
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return compact('hasBackgroundImages', 'randomBackgroundImage');
    }
}
