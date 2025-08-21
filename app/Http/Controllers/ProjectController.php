<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Category;

class ProjectController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($validated);
        return redirect()->back()->with('success', 'Proje başarıyla eklendi!');
    }
}
