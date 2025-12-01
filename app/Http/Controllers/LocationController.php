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

        $data = match ($type) {
            'city' => \App\Models\City::where('country_id', $parentId)->orderBy('name')->get(['id', 'name']),
            'district' => \App\Models\District::where('city_id', $parentId)->orderBy('name')->get(['id', 'name']),
            'neighborhood' => \App\Models\Neighborhood::where('district_id', $parentId)->orderBy('name')->get(['id', 'name']),
            default => \App\Models\Country::orderBy('name')->get(['id', 'name']),
        };

        return response()->json($data);
    }
}
