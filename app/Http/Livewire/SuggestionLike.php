<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Oneri;
use App\Models\OneriLike;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuggestionLike extends Component
{
    public Oneri $suggestion;

    public int $likesCount = 0;
    public bool $liked = false;

    public function mount(Oneri $suggestion)
    {
        $this->suggestion = $suggestion;
        $this->likesCount = $suggestion->likes()->count();
        $this->liked = Auth::check() && $suggestion->likes()->where('user_id', Auth::id())->exists();
    }

    public function toggleLike()
    {
        if (! Auth::check()) {
            return;
        }

        $userId = Auth::id();

        // Determine project id (root project or this suggestion if it's a root)
        if ($this->suggestion->project_id) {
            $projectId = $this->suggestion->project_id;
        } else {
            $projectId = $this->suggestion->id;
        }

        // If the user already liked this suggestion -> toggle off
        $currentLike = OneriLike::where('user_id', $userId)->where('oneri_id', $this->suggestion->id)->first();
        if ($currentLike) {
            $currentLike->delete();
            $this->liked = false;
            $this->likesCount = max(0, $this->likesCount - 1);
            return;
        }

                // Otherwise remove any other likes the user has inside this project.
                // If the oneriler.project_id column exists, perform project-scoped deletion.
                if (Schema::hasColumn('oneriler', 'project_id')) {
                    DB::table('oneri_likes')
                        ->join('oneriler', 'oneri_likes.oneri_id', '=', 'oneriler.id')
                        ->where('oneri_likes.user_id', $userId)
                        ->where(function ($q) use ($projectId) {
                            $q->where('oneriler.project_id', $projectId)
                              ->orWhere('oneri_likes.oneri_id', $projectId);
                        })
                        ->where('oneri_likes.oneri_id', '!=', $this->suggestion->id)
                        ->delete();
                } else {
                    // Fallback: delete any other like by the user (global) except current suggestion
                    OneriLike::where('user_id', $userId)
                        ->where('oneri_id', '!=', $this->suggestion->id)
                        ->delete();
                }

        // Create like for the selected suggestion
        OneriLike::create(['user_id' => $userId, 'oneri_id' => $this->suggestion->id]);
        $this->liked = true;
        // Recalculate likes count to reflect actual DB value
        $this->likesCount = $this->suggestion->likes()->count();

    // No browser events here to keep compatibility with this Livewire version
    }

    public function render()
    {
        return view('livewire.suggestion-like');
    }
}
