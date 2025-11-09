<?php

namespace App\Http\Controllers;

use App\Helpers\BackgroundImageHelper;
use App\Models\Category;
use App\Models\Project;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\SuggestionCommentLike;
use App\Models\SuggestionLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Ana sayfa - Hero section ve rastgele projeler
     */
    public function index()
    {
        // Get random projects with their suggestions
        $randomProjects = Project::with([
            'suggestions' => function ($query) {
                $query->limit(3); // Max 3 suggestions per project
            },
            'suggestions.likes',
            'suggestions.createdBy',
            'createdBy',
            'projectGroups.category',
        ])
            ->has('suggestions') // Only projects with suggestions
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $backgroundData = $this->getBackgroundImageData();

        return view('user.index', array_merge(compact('randomProjects'), $backgroundData));
    }

    /**
     * Projeler sayfası - Tüm projeler ve öneriler
     */
    public function projects(Request $request)
    {
        // Start with base query for projects
        $query = Project::with([
            'suggestions.likes',
            'suggestions.createdBy',
            'createdBy',
            'projectGroups.category',
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('neighborhood', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $categoryId = $request->get('category');
            $query->whereHas('projectGroups.category', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Filter by location (district)
        if ($request->filled('district')) {
            $query->where('district', $request->get('district'));
        }

        // Filter by neighborhood
        if ($request->filled('neighborhood')) {
            $query->where('neighborhood', $request->get('neighborhood'));
        }

        // Filter by status (active/expired based on end_date)
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                });
            } elseif ($status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        // Get filtered projects
        $projects = $query->get();

        // Get all categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        // Get unique districts for filter dropdown
        $districts = Project::whereNotNull('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        $backgroundData = $this->getBackgroundImageData();

        return view('user.projects', array_merge(
            compact('projects', 'categories', 'districts'),
            $backgroundData
        ));
    }

    /**
     * Proje önerileri sayfası - Belirli bir projenin tüm önerileri
     */
    public function projectSuggestions($id)
    {
        // Get the project with its suggestions
        $project = Project::with([
            'suggestions.likes',
            'suggestions.comments',
            'suggestions.createdBy',
            'createdBy',
            'projectGroups.category',
        ])
            ->findOrFail($id);

        $backgroundData = $this->getBackgroundImageData();

        return view('user.project-suggestions', array_merge(compact('project'), $backgroundData));
    }

    /**
     * Öneri detay sayfası
     */
    public function suggestionDetail($id)
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

        return view('user.suggestion-detail', array_merge(
            compact('suggestion', 'userPendingComments'),
            $backgroundData
        ));
    }

    /**
     * AJAX ile beğeni işlemi
     */
    public function toggleLike(Request $request, $suggestionId)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor'], 401);
        }

        $suggestion = Suggestion::with('project')->findOrFail($suggestionId);
        $user = Auth::user();
        $projectId = $suggestion->project_id;

        // Proje süresi kontrol et
        if ($suggestion->project && $suggestion->project->end_date && now()->greaterThan($suggestion->project->end_date)) {
            return response()->json([
                'error' => 'Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.',
                'expired' => true,
            ], 403);
        }

        // Check other likes in the same project
        $existingLike = SuggestionLike::whereHas('suggestion', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
            ->where('user_id', $user->id)
            ->with('suggestion')
            ->first();

        $switchedFrom = null;
        $liked = false;

        if ($existingLike) {
            // If there is a like for this suggestion, remove it; otherwise switch to another suggestion
            if ($existingLike->suggestion_id == $suggestionId) {
                $existingLike->delete();
                $liked = false;
            } else {
                // Save old like (to know which suggestion was changed from)
                $switchedFrom = $existingLike->suggestion->title;

                // Eski beğeniyi sil, yeni beğeni ekle
                $existingLike->delete();
                SuggestionLike::create([
                    'user_id' => $user->id,
                    'suggestion_id' => $suggestionId,
                ]);
                $liked = true;
            }
        } else {
            // Yeni beğeni ekle
            SuggestionLike::create([
                'user_id' => $user->id,
                'suggestion_id' => $suggestionId,
            ]);
            $liked = true;
        }

        // Güncel beğeni sayısını hesapla
        $likesCount = $suggestion->fresh()->likes()->count();

        // Get updated like counts for all suggestions in this project
        $allSuggestionsInProject = Suggestion::where('project_id', $projectId)
            ->withCount('likes')
            ->get()
            ->pluck('likes_count', 'id')
            ->toArray();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
            'all_likes' => $allSuggestionsInProject,
            'switched_from' => $switchedFrom,
            'current_title' => $suggestion->title,
            'project_id' => $projectId,
            'message' => $liked
                ? ($switchedFrom ? 'Seçiminiz değiştirildi!' : 'Öneri beğenildi! (Proje başına sadece bir beğeni)')
                : 'Beğeni kaldırıldı!',
        ]);
    }

    /**
     * AJAX ile yorum ekleme işlemi
     */
    public function storeComment(Request $request, $suggestionId)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor', 'success' => false], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000|min:3',
        ], [
            'comment.required' => 'Yorum içeriği gereklidir.',
            'comment.max' => 'Yorum en fazla 1000 karakter olabilir.',
            'comment.min' => 'Yorum en az 3 karakter olmalıdır.',
        ]);

        $suggestion = Suggestion::findOrFail($suggestionId);
        $user = Auth::user();

        try {
            // Yorum ekleme (varsayılan olarak onaysız)
            $comment = SuggestionComment::create([
                'suggestion_id' => $suggestion->id,
                'user_id' => $user->id,
                'comment' => trim($request->comment),
                'is_approved' => false, // Admin onayı gerekiyor
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yorumunuz başarıyla gönderildi. Onaylandıktan sonra görüntülenecektir.',
                'comment_id' => $comment->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yorum eklenirken bir hata oluştu. Lütfen tekrar deneyin.',
            ], 500);
        }
    }

    /**
     * AJAX ile yorum cevaplama işlemi
     */
    public function storeReply(Request $request, $commentId)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor', 'success' => false], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000|min:3',
        ], [
            'comment.required' => 'Cevap içeriği gereklidir.',
            'comment.max' => 'Cevap en fazla 1000 karakter olabilir.',
            'comment.min' => 'Cevap en az 3 karakter olmalıdır.',
        ]);

        $parentComment = SuggestionComment::findOrFail($commentId);
        $user = Auth::user();

        try {
            // Cevap ekleme (varsayılan olarak onaysız)
            $reply = SuggestionComment::create([
                'suggestion_id' => $parentComment->suggestion_id,
                'user_id' => $user->id,
                'parent_id' => $parentComment->id,
                'comment' => trim($request->comment),
                'is_approved' => false, // Admin onayı gerekiyor
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cevabınız başarıyla gönderildi. Onaylandıktan sonra görüntülenecektir.',
                'reply_id' => $reply->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cevap eklenirken bir hata oluştu. Lütfen tekrar deneyin.',
            ], 500);
        }
    }

    /**
     * AJAX ile yorum beğenme işlemi
     */
    public function toggleCommentLike(Request $request, $commentId)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor', 'success' => false], 401);
        }

        $comment = SuggestionComment::findOrFail($commentId);
        $user = Auth::user();

        try {
            // Kullanıcının bu yoruma beğenisi var mı kontrol et
            $existingLike = SuggestionCommentLike::where('suggestion_comment_id', $commentId)
                ->where('user_id', $user->id)
                ->first();

            if ($existingLike) {
                // Beğeniyi kaldır
                $existingLike->delete();
                $liked = false;
            } else {
                // Beğeni ekle
                SuggestionCommentLike::create([
                    'suggestion_comment_id' => $commentId,
                    'user_id' => $user->id,
                ]);
                $liked = true;
            }

            // Güncel beğeni sayısını hesapla
            $likesCount = SuggestionCommentLike::where('suggestion_comment_id', $commentId)->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $liked ? 'Yorum beğenildi!' : 'Beğeni kaldırıldı!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Beğeni işlemi sırasında bir hata oluştu.',
            ], 500);
        }
    }

    /**
     * Get background image data for views
     * Centralizes the background image logic to avoid duplication
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
