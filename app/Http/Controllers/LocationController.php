<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getChildren(Request $request)
    {
        $parentId = $request->input('parent_id');
        $type = $request->input('type');

        if (!$type) {
            // Default to countries if no type specified
            $query = Location::where('type', Location::TYPE_COUNTRY);
        } else {
            // Validate parentId presence for child types if necessary, 
            // but simplified logic: query by type and parent_id
            $query = Location::where('type', $type);
            
            if ($parentId) {
                $query->where('parent_id', $parentId);
            }
        }

        $locations = $query->orderBy('name')->get(['id', 'name']);

        return response()->json($locations);
    }
}
