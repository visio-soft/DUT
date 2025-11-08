<?php

namespace App\Http\Controllers;

use App\Helpers\BackgroundImageHelper;
use App\Models\Category;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
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
        // Get random categories (projects)
        $randomProjects = Category::with(['suggestions' => function ($query) {
            $query->limit(3); // Max 3 suggestions per category
        }])
            ->has('suggestions') // Only categories with suggestions
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Get random background image (different on each page refresh)
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return view('user.index', compact('randomProjects', 'hasBackgroundImages', 'randomBackgroundImage'));
    }

    /**
     * Projeler sayfası - Tüm projeler ve öneriler
     */
    public function projects()
    {
        // Get all categories (projects) with their suggestions
        $projects = Category::with([
            'suggestions.likes',
            'suggestions.createdBy',
        ])
            ->has('suggestions') // Only categories with suggestions
            ->get();

        // Get random background image (different on each page refresh)
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return view('user.projects', compact('projects', 'hasBackgroundImages', 'randomBackgroundImage'));
    }

    /**
     * Proje önerileri sayfası - Belirli bir projenin tüm önerileri
     */
    public function projectSuggestions($id)
    {
        // Get the project (category) with its suggestions
        $project = Category::with([
            'suggestions.likes',
            'suggestions.comments',
            'suggestions.createdBy',
        ])
            ->findOrFail($id);

        // Get random background image (different on each page refresh)
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return view('user.project-suggestions', compact('project', 'hasBackgroundImages', 'randomBackgroundImage'));
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

        // Get random background image (different on each page refresh)
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return view('user.suggestion-detail', compact('suggestion', 'hasBackgroundImages', 'randomBackgroundImage', 'userPendingComments'));
    }

    /**
     * AJAX ile beğeni işlemi
     */
    public function toggleLike(Request $request, $suggestionId)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor'], 401);
        }

        $suggestion = Suggestion::with('category')->findOrFail($suggestionId);
        $user = Auth::user();
        $categoryId = $suggestion->category_id;

        // Proje süresi kontrol et
        if ($suggestion->category && $suggestion->category->isExpired()) {
            return response()->json([
                'error' => 'Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.',
                'expired' => true,
            ], 403);
        }

        // Check other likes in the same category (project)
        $existingLike = SuggestionLike::whereHas('suggestion', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
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
                    'oneri_id' => $suggestionId,
                ]);
                $liked = true;
            }
        } else {
            // Yeni beğeni ekle
            SuggestionLike::create([
                'user_id' => $user->id,
                'oneri_id' => $suggestionId,
            ]);
            $liked = true;
        }

        // Güncel beğeni sayısını hesapla
        $likesCount = $suggestion->fresh()->likes()->count();

        // Get updated like counts for all suggestions in this category
        $allSuggestionsInCategory = Suggestion::where('category_id', $categoryId)
            ->withCount('likes')
            ->get()
            ->pluck('likes_count', 'id')
            ->toArray();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
            'all_likes' => $allSuggestionsInCategory,
            'switched_from' => $switchedFrom,
            'current_title' => $suggestion->title,
            'category_id' => $categoryId,
            'message' => $liked
                ? ($switchedFrom ? 'Seçiminiz değiştirildi!' : 'Öneri beğenildi! (Kategori başına sadece bir beğeni)')
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
                'oneri_id' => $suggestion->id,
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
                'oneri_id' => $parentComment->oneri_id,
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
            $existingLike = OneriCommentLike::where('oneri_comment_id', $commentId)
                ->where('user_id', $user->id)
                ->first();

            if ($existingLike) {
                // Beğeniyi kaldır
                $existingLike->delete();
                $liked = false;
            } else {
                // Beğeni ekle
                OneriCommentLike::create([
                    'oneri_comment_id' => $commentId,
                    'user_id' => $user->id,
                ]);
                $liked = true;
            }

            // Güncel beğeni sayısını hesapla
            $likesCount = OneriCommentLike::where('oneri_comment_id', $commentId)->count();

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
}
