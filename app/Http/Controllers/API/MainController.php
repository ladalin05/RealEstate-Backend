<?php

namespace App\Http\Controllers\API;

use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Admin\Settings;
use App\Models\UserInform;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\User;

class MainController extends Controller
{
    public function getMenu(Request $request)
    {
        $menus = Menus::query()->where('status',1)->orderBy('page_order', 'ASC')->get();

        return response()->json([
            'status' => 'success',
            'menus' => $menus,
        ]);
    }
    
    public function getContact(Request $request)
    {
        try {
            $id = User::where('role_id',1)->first()->id ?? null;
            if (!$id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
            $user_info = UserInform::where('user_id',$id)->first();
            return response()->json([
                'success' => true,
                'user_info' => $user_info,
            ]);
        } catch(\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
