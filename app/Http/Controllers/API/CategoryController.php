<?php

namespace App\Http\Controllers\API;

use App\Models\Property\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        $category = PropertyType::query()->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => 'success',
            'categories' => $category,
        ]);
    }
}
