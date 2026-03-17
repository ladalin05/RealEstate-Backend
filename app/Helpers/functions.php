<?php

use Illuminate\Support\Facades\DB;

use App\Models\Property\PropertyType;
use App\Models\Location\Location;
use App\Models\Admin\Menu;
use App\Models\SubscriptionPlan;
use App\Models\Property\PropertyViews;
use App\Models\UserManagement\User;
use App\Models\Admin\Permission;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

if (!function_exists('setUserMenu')) {
    function setUserMenu()
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }

        $data = [ 'access' => [], 'menus' => [], 'permissions' => [], 'has_menus' => [], 'administrator' => false, ];
        $administrator = $user->roles->where('administrator', 1)->count() > 0;

        if ($administrator) {
            $data['administrator'] = true;
            $data['menus'] = Menu::with('children') ->whereNull('parent_id')->orderBy('order')->get();
            return $data;
        }

        $access   = [];
        $menu_ids = [];

        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $access[md5(trim($permission->slug))] = $permission->id;
                if ($permission->is_menu == 1) {
                    $menu_ids[] = $permission->menu_id;
                }
            }
        }

        $permissions = Permission::all()->pluck('id', 'slug')->mapWithKeys(fn ($id, $slug) => [md5(trim($slug)) => $id])->toArray();
        $menu_ids = array_unique($menu_ids);
        $third  = Menu::whereIn('id', $menu_ids)->pluck('parent_id')->toArray();
        $menu_ids = array_merge($menu_ids, $third);
        $second = Menu::whereIn('id', $menu_ids)->pluck('parent_id')->toArray();
        $menu_ids = array_merge($menu_ids, $second);
        $has_menus = array_filter(array_unique($menu_ids));

        $data['access']      = $access;
        $data['permissions'] = $permissions;
        $data['has_menus']   = $has_menus;
        $data['menus']       = Menu::with('children') ->whereNull('parent_id')->orderBy('order')->get();

        return $data;
    }
}


if (!function_exists('uploadImage')) {
    function uploadImage($file, $oldImagePath = null, $folder = 'images')
    {
        if ($file) {
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $filename = $file->getClientOriginalName();
            $path = $file->storeAs($folder, $filename, 'public');

            return $path;
        }

        return null;
    }
}   

if (! function_exists('isActiveMenu')) {
    function isActiveMenu($route)
    {
        if (empty($route)) {
            return '';
        }

        $current = Route::currentRouteName();
        $baseRoute = substr($current, 0, strrpos($current, '.'));
        $route = trim($route, '/'); 
        $routeName = substr($route, 0, strrpos($route, '.'));

        return strpos($baseRoute, $routeName) !== false ? 'active' : '';
    }
}

if (!function_exists('updateImage')) {
    function updateImage($file, $oldImagePath = null, $folder = 'images')
    {
        if ($file) {
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $filename = $file->getClientOriginalName();
            $path = $file->storeAs($folder, $filename, 'public');

            return $path;

        }
        return $oldImagePath;
    }
}

if (!function_exists('getTypes')) {
    function getTypes()
    {
        $type_info = PropertyType::where('status', 1)->get();
        return $type_info;
    }
}

if (!function_exists('getLocations')) {
    function getLocations()
    {
        $location_info = Location::where('status', 1)->get();
        return $location_info;
    }
}

if (!function_exists('getPlans')) {
    function getPlans()
    {
        $sub_plan = SubscriptionPlan::where('status', 1)->get();
        return $sub_plan;
    }
}

if (!function_exists('getUserById')) {
    function getUserById($id)
    {
        $users = User::where('id', $id)->first();
        return $users ? $users : null;
    }
}

if (!function_exists('getSubPlanById')) {
    function getSubPlanById($id)
    {
        $sub_plan = SubscriptionPlan::where('id', $id)->first();
        return $sub_plan ? $sub_plan->plan_name : null;
    }
}

if (!function_exists('post_views_save')) {
    function post_views_save($property_id,$user_id=null)
    {       

        $today_date = date('Y-m-d H:i:s');
        $view_info = PropertyViews::where('property_id', '=', $property_id)->where('date', '==', $today_date)->first();   

        if($view_info)
        { 
            $view_obj = PropertyViews::findOrFail($view_info->id);        
            $view_obj->increment('property_views');     
            $view_obj->save();
             
        }
        else
        {
            PropertyViews::create([
                'post_id' => $property_id,
                'views' => 1,
                'date' => $today_date,
            ]);
        }
 
    }
}
