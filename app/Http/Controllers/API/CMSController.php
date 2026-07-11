<?php

namespace App\Http\Controllers\API;

use App\Models\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Admin\Settings;
use App\Models\UserManagement\UserInform;
use App\Services\CMSService;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UserManagement\User;

class CMSController extends Controller
{
    private CmsService $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function getHomeData(Request $request)
    {
        try {
            // if(!auth('api')->user()) {
            //     return $this->errorResponse(
            //         message: __('messages.unauthorized'),
            //         code: 401
            //     );
            // }

            $data = $this->cmsService->getHomeData();

            return $this->successResponse('Home data fetched successfully', $data);
        } catch(\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFeaturedProperties(Request $request)
    {
        $limit = $request->limit ?? 4;
        $properties = $this->cmsService->getFeaturedProperties($limit);

        return $this->successResponse('Featured properties fetched successfully', $properties);
    }
    
    public function getMenu(Request $request)
    {
        $menus = Menu::join('menu_items', 'menus.id', 'menu_items.menu_id')
                    ->select('menu_items.id as id', 'menus.slug as slug', 'menu_items.title as menu_title', 'menu_items.link as menu_link')
                    ->orderBy('menu_items.order', 'ASC')
                    ->get();

        return response()->json([
            'status' => 'success',
            'menus' => $menus,
        ]);
    }

    public function getUserDashboard(Request $request)
    {
        try {
            
            $dash_data = $this->cmsService->getUserDashboard();
            return response()->json([
                'success' => true,
                'data' => $dash_data,
            ]);
        } catch(\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getContact(Request $request)
    {
        try {
            $id = User::join('user_role','users.id','=','user_role.user_id')
                        ->join('roles','user_role.role_id','=','roles.id')
                        ->where('roles.administrator',1)->first()->id ?? null;
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
