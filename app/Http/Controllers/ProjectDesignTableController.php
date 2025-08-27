<?php

namespace App\Http\Controllers;

use App\Models\ProjectDesign;
use App\Models\ProjectDesignLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectDesignTableController extends Controller
{
    public function index()
    {
        $projectDesigns = ProjectDesign::with(['project', 'project.category', 'likes'])
            ->whereHas('project')
            ->get()
            ->map(function ($design) {
                $project = $design->project;

                // Get project image
                $projectImage = '';
                if ($project && $project->hasMedia('images')) {
                    $projectImage = $project->getFirstMediaUrl('images');
                }

                return [
                    'id' => $design->id,
                    'project_name' => $project->title ?? 'Bilinmeyen Proje',
                    'project_budget' => $project->budget ?? 0,
                    'project_image' => $projectImage,
                    'likes_count' => $design->likes->count(),
                    'is_liked' => Auth::check() ? $design->isLikedByUser(Auth::id()) : false,
                    'created_at' => $design->created_at->timestamp,
                ];
            });

        return view('project-designs.table', compact('projectDesigns'));
    }

    public function like(Request $request, ProjectDesign $projectDesign)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Beğenmek için giriş yapmalısınız.'], 401);
        }

        $userId = Auth::id();

        // Check if already liked
        $existingLike = ProjectDesignLike::where('user_id', $userId)
            ->where('project_design_id', $projectDesign->id)
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $liked = false;
        } else {
            // Like
            ProjectDesignLike::create([
                'user_id' => $userId,
                'project_design_id' => $projectDesign->id,
            ]);
            $liked = true;
        }

        $likesCount = $projectDesign->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
