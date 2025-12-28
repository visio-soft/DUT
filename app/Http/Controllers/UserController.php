<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatusEnum;
use App\Helpers\BackgroundImageHelper;
use App\Models\Category;
use App\Models\Project;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use App\Models\SuggestionCommentLike;
use App\Models\SuggestionLike;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Ana sayfa - Hero section ve rastgele projeler
     */
    public function index()
    {
        $randomProjects = Project::query()
            ->with(['suggestions' => function ($query) {
                $query->with('likes')->latest()->limit(3);
            }])
            ->withCount('suggestions')
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
        $projectsQuery = Project::query()
            ->with([
                'suggestions.likes',
                'suggestions.createdBy',
                'projectGroups.category',
            ]);

        if ($search = $request->string('search')->toString()) {
            $projectsQuery->where(function (Builder $query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $projectsQuery->where('status', $status);
        }

        if ($categoryId = $request->input('category_id')) {
            $projectsQuery->whereHas('projectGroups', function (Builder $query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        if ($creatorType = $request->input('creator_type')) {
            if ($creatorType === 'with_user') {
                $projectsQuery->whereNotNull('created_by_id');
            } elseif ($creatorType === 'not_assigned') {
                $projectsQuery->whereNull('created_by_id');
            }
        }

        if ($district = $request->input('district')) {
            $projectsQuery->where('district', $district);
        }

        if ($neighborhood = $request->input('neighborhood')) {
            $projectsQuery->where('neighborhood', $neighborhood);
        }

        if ($startDate = $request->input('start_date')) {
            $projectsQuery->whereDate('start_date', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $projectsQuery->whereDate('end_date', '<=', $endDate);
        }

        if ($minBudget = $request->input('min_budget')) {
            $projectsQuery->where('min_budget', '>=', $minBudget);
        }

        if ($maxBudget = $request->input('max_budget')) {
            $projectsQuery->where('max_budget', '<=', $maxBudget);
        }

        $projects = $projectsQuery
            ->orderByDesc('start_date')
            ->get();

        $statusOptions = collect(ProjectStatusEnum::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
            ->toArray();

        $filterCategories = Category::orderBy('name')->get();
        $districts = array_keys(config('istanbul_neighborhoods', []));
        $filterValues = $request->only([
            'search',
            'status',
            'category_id',
            'creator_type',
            'district',
            'neighborhood',
            'start_date',
            'end_date',
            'min_budget',
            'max_budget',
        ]);

        $backgroundData = $this->getBackgroundImageData();

        return view('user.projects', array_merge(
            compact('projects', 'statusOptions', 'filterCategories', 'districts', 'filterValues'),
            $backgroundData
        ));
    }

    /**
     * Proje önerileri sayfası - Belirli bir projenin tüm önerileri
     */
    public function projectSuggestions($id)
    {
        $project = Project::with([
            'suggestions.likes',
            'suggestions.comments',
            'suggestions.createdBy',
        ])->findOrFail($id);

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

        // Validate feedback fields if provided
        $request->validate([
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|string|in:erkek,kadın,diğer',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $suggestion = Suggestion::with(['category', 'project'])->findOrFail($suggestionId);
        $user = Auth::user();
        $projectId = $suggestion->project_id ?? null;

        if ($suggestion->project && $suggestion->project->isExpired()) {
            return response()->json([
                'error' => 'Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.',
                'expired' => true,
            ], 403);
        }

        if (! $suggestion->project && $suggestion->category && $suggestion->category->isExpired()) {
            return response()->json([
                'error' => 'Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.',
                'expired' => true,
            ], 403);
        }

        // Check other likes in the same category (project)
        $existingLike = SuggestionLike::query()
            ->where('user_id', $user->id)
            ->whereHas('suggestion', function (Builder $query) use ($projectId, $suggestion) {
                if ($projectId) {
                    $query->where('project_id', $projectId);
                } else {
                    $query->where('category_id', $suggestion->category_id);
                }
            })
            ->with('suggestion')
            ->first();

        $switchedFrom = null;
        $liked = false;
        $newLike = null;

        // Prepare feedback data
        $feedbackData = [
            'user_id' => $user->id,
            'suggestion_id' => $suggestionId,
        ];

        // Add feedback fields if provided
        if ($request->has('age')) {
            $feedbackData['age'] = $request->input('age');
        }
        if ($request->has('gender')) {
            $feedbackData['gender'] = $request->input('gender');
        }
        if ($request->has('is_anonymous')) {
            $feedbackData['is_anonymous'] = $request->boolean('is_anonymous');
        }

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
                $newLike = SuggestionLike::create($feedbackData);
                $liked = true;
            }
        } else {
            // Yeni beğeni ekle
            $newLike = SuggestionLike::create($feedbackData);
            $liked = true;
        }

        // Güncel beğeni sayısını hesapla
        $likesCount = $suggestion->fresh()->likes()->count();

        // Get updated like counts for all suggestions in this category
        $allSuggestionsInCategory = Suggestion::query()
            ->when(
                $projectId,
                fn ($query) => $query->where('project_id', $projectId),
                fn ($query) => $query->where('category_id', $suggestion->category_id)
            )
            ->withCount('likes')
            ->get()
            ->pluck('likes_count', 'id')
            ->toArray();

        // Check if we need to show feedback form (new like without feedback data)
        $needFeedback = $liked && $newLike && !$request->has('age');

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
            'all_likes' => $allSuggestionsInCategory,
            'switched_from' => $switchedFrom,
            'current_title' => $suggestion->title,
            'need_feedback' => $needFeedback,
            'like_id' => $newLike?->id,
            'message' => $liked
                ? ($switchedFrom ? 'Seçiminiz değiştirildi!' : 'Öneri beğenildi! (Kategori başına sadece bir beğeni)')
                : 'Beğeni kaldırıldı!',
        ]);
    }

    /**
     * AJAX ile beğeni geri bildirim güncelleme işlemi
     */
    public function updateLikeFeedback(Request $request, $likeId)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor'], 401);
        }

        $request->validate([
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|in:erkek,kadın,diğer',
            'is_anonymous' => 'required|boolean',
        ]);

        $like = SuggestionLike::where('id', $likeId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $like) {
            return response()->json(['error' => 'Beğeni bulunamadı'], 404);
        }

        $like->update([
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'is_anonymous' => $request->boolean('is_anonymous'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Geri bildiriminiz için teşekkür ederiz!',
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
    /**
     * Tüm bildirimleri okundu olarak işaretle
     */
    public function markAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications()
                ->where(function($query) {
                    $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
                })
                ->get()
                ->markAsRead();
                
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
