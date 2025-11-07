<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Oneri;
use App\Models\OneriLike;
use App\Models\OneriComment;
use App\Models\OneriCommentLike;
use App\Helpers\BackgroundImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Ana sayfa - Hero section ve rastgele projeler
     */
    public function index()
    {
        // Rastgele kategoriler (projeler) al
        $randomProjects = Category::with(['oneriler' => function($query) {
                $query->limit(3); // Her kategoriden max 3 öneri
            }])
            ->has('oneriler') // Sadece önerisi olan kategoriler
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Arka plan için rastgele resim al (her sayfa yenilenmesinde farklı)
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
    public function projects(Request $request)
    {
        // Filtre query parametrelerini al
        $filters = $request->only(['category', 'district', 'neighborhood', 'status', 'min_budget', 'max_budget']);

        // Tüm kategorileri (projeleri) önerileriyle birlikte getir
        $projectsQuery = Category::with([
                'oneriler' => function ($query) use ($filters) {
                    // Bütçe filtresini query seviyesinde uygula
                    if (!empty($filters['min_budget'])) {
                        $query->where('budget', '>=', $filters['min_budget']);
                    }
                    if (!empty($filters['max_budget'])) {
                        $query->where('budget', '<=', $filters['max_budget']);
                    }
                },
                'oneriler.likes',
                'oneriler.createdBy'
            ])
            ->has('oneriler'); // Sadece önerisi olan kategoriler

        // Kategori filtresi
        if (!empty($filters['category'])) {
            $projectsQuery->where('id', $filters['category']);
        }

        // İlçe filtresi
        if (!empty($filters['district'])) {
            $projectsQuery->where('district', $filters['district']);
        }

        // Mahalle filtresi
        if (!empty($filters['neighborhood'])) {
            $projectsQuery->where('neighborhood', $filters['neighborhood']);
        }

        // Durum filtresi (Oylama durumu)
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $projectsQuery->where('end_datetime', '>', now());
            } elseif ($filters['status'] === 'expired') {
                $projectsQuery->where('end_datetime', '<=', now());
            }
        }

        // Bütçe filtresi varsa, sadece önerisi olan kategorileri al
        if (!empty($filters['min_budget']) || !empty($filters['max_budget'])) {
            $projectsQuery->whereHas('oneriler', function ($query) use ($filters) {
                if (!empty($filters['min_budget'])) {
                    $query->where('budget', '>=', $filters['min_budget']);
                }
                if (!empty($filters['max_budget'])) {
                    $query->where('budget', '<=', $filters['max_budget']);
                }
            });
        }

        $projects = $projectsQuery->get();

        // Tüm kategorileri filtre için al
        $allCategories = Category::has('oneriler')->orderBy('name')->get();
        
        // İlçe listesi
        $districts = array_keys(config('istanbul_neighborhoods', []));
        
        // Seçili ilçeye göre mahalleler
        $neighborhoods = [];
        if (!empty($filters['district'])) {
            $neighborhoods = config('istanbul_neighborhoods.' . $filters['district'], []);
        }

        // Arka plan için rastgele resim al (her sayfa yenilenmesinde farklı)
        $hasBackgroundImages = BackgroundImageHelper::hasBackgroundImages();
        $randomBackgroundImage = null;

        if ($hasBackgroundImages) {
            $imageData = BackgroundImageHelper::getRandomBackgroundImage();
            $randomBackgroundImage = $imageData ? $imageData['url'] : null;
        }

        return view('user.projects', compact(
            'projects', 
            'hasBackgroundImages', 
            'randomBackgroundImage',
            'allCategories',
            'districts',
            'neighborhoods',
            'filters'
        ));
    }

    /**
     * Proje önerileri sayfası - Belirli bir projenin tüm önerileri
     */
    public function projectSuggestions($id)
    {
        // Projeyi (kategoriyi) önerileriyle birlikte getir
        $project = Category::with([
                'oneriler.likes',
                'oneriler.comments',
                'oneriler.createdBy'
            ])
            ->findOrFail($id);

        // Arka plan için rastgele resim al (her sayfa yenilenmesinde farklı)
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
        $suggestion = Oneri::with([
                'category',
                'likes.user',
                'approvedComments.user',
                'approvedComments.likes.user',
                'approvedComments.approvedReplies.user',
                'approvedComments.approvedReplies.likes.user',
                'createdBy'
            ])
            ->findOrFail($id);

        // Kullanıcının bu öneriye yazdığı onaylanmamış yorumları da getir (hem ana yorumlar hem cevaplar)
        $userPendingComments = collect();
        if (Auth::check()) {
            $userPendingComments = OneriComment::with(['user', 'parent.user'])
                ->where('oneri_id', $id)
                ->where('user_id', Auth::id())
                ->where('is_approved', false)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Arka plan için rastgele resim al (her sayfa yenilenmesinde farklı)
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
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor'], 401);
        }

        $suggestion = Oneri::with('category')->findOrFail($suggestionId);
        $user = Auth::user();
        $categoryId = $suggestion->category_id;

        // Proje süresi kontrol et
        if ($suggestion->category && $suggestion->category->isExpired()) {
            return response()->json([
                'error' => 'Bu projenin süresi dolmuştur. Artık beğeni yapılamaz.',
                'expired' => true
            ], 403);
        }

        // Aynı kategorideki (projedeki) diğer beğenileri kontrol et
        $existingLike = OneriLike::whereHas('oneri', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('user_id', $user->id)
            ->with('oneri')
            ->first();

        $switchedFrom = null;
        $liked = false;

        if ($existingLike) {
            // Eğer bu öneriye beğeni varsa kaldır, başka öneriye ise değiştir
            if ($existingLike->oneri_id == $suggestionId) {
                $existingLike->delete();
                $liked = false;
            } else {
                // Eski beğeniyi kaydet (hangi öneriden değiştirildiğini bilmek için)
                $switchedFrom = $existingLike->oneri->title;

                // Eski beğeniyi sil, yeni beğeni ekle
                $existingLike->delete();
                OneriLike::create([
                    'user_id' => $user->id,
                    'oneri_id' => $suggestionId
                ]);
                $liked = true;
            }
        } else {
            // Yeni beğeni ekle
            OneriLike::create([
                'user_id' => $user->id,
                'oneri_id' => $suggestionId
            ]);
            $liked = true;
        }

        // Güncel beğeni sayısını hesapla
        $likesCount = $suggestion->fresh()->likes()->count();

        // Bu kategorideki tüm önerilerin güncel beğeni sayılarını al
        $allSuggestionsInCategory = Oneri::where('category_id', $categoryId)
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
                : 'Beğeni kaldırıldı!'
        ]);
    }

    /**
     * AJAX ile yorum ekleme işlemi
     */
    public function storeComment(Request $request, $suggestionId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor', 'success' => false], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000|min:3'
        ], [
            'comment.required' => 'Yorum içeriği gereklidir.',
            'comment.max' => 'Yorum en fazla 1000 karakter olabilir.',
            'comment.min' => 'Yorum en az 3 karakter olmalıdır.'
        ]);

        $suggestion = Oneri::findOrFail($suggestionId);
        $user = Auth::user();

        try {
            // Yorum ekleme (varsayılan olarak onaysız)
            $comment = OneriComment::create([
                'oneri_id' => $suggestion->id,
                'user_id' => $user->id,
                'comment' => trim($request->comment),
                'is_approved' => false // Admin onayı gerekiyor
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yorumunuz başarıyla gönderildi. Onaylandıktan sonra görüntülenecektir.',
                'comment_id' => $comment->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yorum eklenirken bir hata oluştu. Lütfen tekrar deneyin.'
            ], 500);
        }
    }

    /**
     * AJAX ile yorum cevaplama işlemi
     */
    public function storeReply(Request $request, $commentId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor', 'success' => false], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:1000|min:3'
        ], [
            'comment.required' => 'Cevap içeriği gereklidir.',
            'comment.max' => 'Cevap en fazla 1000 karakter olabilir.',
            'comment.min' => 'Cevap en az 3 karakter olmalıdır.'
        ]);

        $parentComment = OneriComment::findOrFail($commentId);
        $user = Auth::user();

        try {
            // Cevap ekleme (varsayılan olarak onaysız)
            $reply = OneriComment::create([
                'oneri_id' => $parentComment->oneri_id,
                'user_id' => $user->id,
                'parent_id' => $parentComment->id,
                'comment' => trim($request->comment),
                'is_approved' => false // Admin onayı gerekiyor
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cevabınız başarıyla gönderildi. Onaylandıktan sonra görüntülenecektir.',
                'reply_id' => $reply->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cevap eklenirken bir hata oluştu. Lütfen tekrar deneyin.'
            ], 500);
        }
    }

    /**
     * AJAX ile yorum beğenme işlemi
     */
    public function toggleCommentLike(Request $request, $commentId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor', 'success' => false], 401);
        }

        $comment = OneriComment::findOrFail($commentId);
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
                    'user_id' => $user->id
                ]);
                $liked = true;
            }

            // Güncel beğeni sayısını hesapla
            $likesCount = OneriCommentLike::where('oneri_comment_id', $commentId)->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $liked ? 'Yorum beğenildi!' : 'Beğeni kaldırıldı!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Beğeni işlemi sırasında bir hata oluştu.'
            ], 500);
        }
    }
}
