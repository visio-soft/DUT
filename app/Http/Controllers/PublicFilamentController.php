<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicFilamentController extends Controller
{
    /**
     * Show a public page styled with Filament components.
     */
    public function index(Request $request)
    {
        return view('filament.public');
    }
}
