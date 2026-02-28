<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class FilterController extends Controller
{
    public function filter_data(Request $request)
    {
        $types = Type::select('type_name as name','id as value')->orderBy('id', 'DESC')->get();
        $locations = Location::select('name', 'id as value')->orderBy('id', 'DESC')->get();
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
