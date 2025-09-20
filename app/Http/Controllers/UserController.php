<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Oneri;
use App\Models\OneriLike;
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
            ->limit(6)
            ->get();

        return view('user.index', compact('randomProjects'));
    }

    /**
     * Projeler sayfası - Tüm projeler ve öneriler
     */
    public function projects()
    {
        // Tüm kategorileri (projeleri) önerileriyle birlikte getir
        $projects = Category::with([
                'oneriler.likes',
                'oneriler.createdBy'
            ])
            ->has('oneriler') // Sadece önerisi olan kategoriler
            ->get();

        return view('user.projects', compact('projects'));
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
                'createdBy'
            ])
            ->findOrFail($id);

        return view('user.suggestion-detail', compact('suggestion'));
    }

    /**
     * AJAX ile beğeni işlemi
     */
    public function toggleLike(Request $request, $suggestionId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmanız gerekiyor'], 401);
        }

        $suggestion = Oneri::findOrFail($suggestionId);
        $user = Auth::user();
        $categoryId = $suggestion->category_id;

        // Aynı kategorideki (projedeki) diğer beğenileri kontrol et
        $existingLike = OneriLike::whereHas('oneri', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('user_id', $user->id)
            ->first();

        if ($existingLike) {
            // Eğer bu öneriye beğeni varsa kaldır, başka öneriye ise değiştir
            if ($existingLike->oneri_id == $suggestionId) {
                $existingLike->delete();
                $liked = false;
            } else {
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

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
            'message' => $liked ? 'Öneri beğenildi!' : 'Beğeni kaldırıldı!'
        ]);
    }
}
