<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Oneri;

class VoteController extends Controller
{
    public function index()
    {
        // Load projects (root oneri records where project_id is null) with their suggestions
        $projects = Oneri::whereNull('project_id')
            ->with(['suggestions', 'media'])
            ->get();

        return view('vote.index', compact('projects'));
    }

    public function show(Oneri $suggestion)
    {
        return view('vote.show', compact('suggestion'));
    }
}
