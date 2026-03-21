<?php

namespace App\Http\Controllers\API;

use App\Models\Property\PropertyType;
use App\Models\Location\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Location\City;
use App\Models\Location\PropertyLocation;

class FilterController extends Controller
{
    public function filter_data(Request $request)
    {
        $types = PropertyType::select('type_name as name','id as value')->orderBy('id', 'DESC')->get();
        $locations = City::select('name', 'id as value')->orderBy('id', 'DESC')->get();
        $data = [
            'types' => $types,
            'locations' => $locations
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
