<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Oneri;
use App\Models\OneriComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PublicProjectController extends Controller
{
    /**
     * Display the public project index page with categories in folder structure
     */
    public function index(): View
    {
        // Get all categories with their associated projects and suggestions
        $categories = Category::with(['oneriler' => function ($query) {
            $query->whereNull('project_id') // Get only main projects, not sub-suggestions
                  ->with('createdBy')
                  ->orderBy('created_at', 'desc');
        }])->orderBy('name')->get();

        // Transform the data to separate projects from suggestions for better organization
        $categoriesWithData = $categories->map(function ($category) {
            // Get all oneriler (both projects and suggestions) for this category
            $allOneriler = $category->oneriler;
            
            // Separate projects (main entries) from suggestions (child entries)
            $projects = $allOneriler->filter(function ($oneri) {
                return is_null($oneri->project_id); // Main projects don't have project_id
            });

            // Get suggestions linked to projects in this category
            $suggestions = Oneri::where('category_id', $category->id)
                ->whereNotNull('project_id')
                ->with('createdBy', 'project')
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'category' => $category,
                'projects' => $projects,
                'suggestions' => $suggestions,
                'total_items' => $projects->count() + $suggestions->count()
            ];
        })->filter(function ($categoryData) {
            // Only include categories that have at least one project or suggestion
            return $categoryData['total_items'] > 0;
        });

        return view('public.projects.index', [
            'categories' => $categoriesWithData
        ]);
    }

    /**
     * Show a specific project detail
     */
    public function show(Oneri $project): View
    {
        // Load the project with its relationships
        $project->load(['category', 'createdBy', 'suggestions.createdBy', 'likes']);

        return view('public.projects.show', [
            'project' => $project
        ]);
    }

    /**
     * Show a specific suggestion detail
     */
    public function showSuggestion(Oneri $suggestion): View
    {
        // Load the suggestion with its relationships
        $suggestion->load(['category', 'createdBy', 'project', 'likes', 'comments.user']);

        return view('public.projects.suggestion', [
            'suggestion' => $suggestion
        ]);
    }

    /**
     * Store a new comment for a suggestion
     */
    public function storeComment(Request $request, Oneri $suggestion): RedirectResponse
    {
        $request->validate([
            'comment' => 'required|string|min:3|max:1000',
            'user_name' => 'required|string|min:2|max:100',
            'user_email' => 'nullable|email|max:255',
        ], [
            'comment.required' => 'Yorum alanı zorunludur.',
            'comment.min' => 'Yorum en az 3 karakter olmalıdır.',
            'comment.max' => 'Yorum en fazla 1000 karakter olabilir.',
            'user_name.required' => 'İsim alanı zorunludur.',
            'user_name.min' => 'İsim en az 2 karakter olmalıdır.',
            'user_name.max' => 'İsim en fazla 100 karakter olabilir.',
            'user_email.email' => 'Geçerli bir email adresi giriniz.',
        ]);

        try {
            // Check if user is authenticated
            if (Auth::check()) {
                $userId = Auth::id();
                $userName = Auth::user()->name;
            } else {
                // For non-authenticated users, create a guest user record or use session
                // For simplicity, we'll create a temporary user record or use session-based approach
                $userEmail = $request->user_email;
                $userName = $request->user_name;
                
                // Try to find existing user by email or create a guest entry
                if ($userEmail) {
                    $user = User::firstOrCreate(
                        ['email' => $userEmail],
                        [
                            'name' => $userName,
                            'password' => bcrypt(str()->random(16)), // Random password for guest users
                            'email_verified_at' => null,
                        ]
                    );
                    $userId = $user->id;
                } else {
                    // For users without email, create a temporary user
                    $user = User::create([
                        'name' => $userName,
                        'email' => 'guest_' . time() . '_' . str()->random(8) . '@temp.local',
                        'password' => bcrypt(str()->random(16)),
                        'email_verified_at' => null,
                    ]);
                    $userId = $user->id;
                }
            }

            // Create the comment
            OneriComment::create([
                'oneri_id' => $suggestion->id,
                'user_id' => $userId,
                'comment' => $request->comment,
                'is_approved' => false, // Comments need approval by default
            ]);

            Session::flash('success', 'Yorumunuz başarıyla gönderildi! Onaylandıktan sonra görünür olacaktır.');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Yorum gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
        }

        return redirect()->route('public.projects.suggestion', $suggestion->id);
    }
}
