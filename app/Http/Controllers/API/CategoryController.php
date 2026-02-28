<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        $category = Type::query()->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => 'success',
            'categories' => $category,
        ]);
    }
}
