<?php

use Illuminate\Support\Facades\DB;

use App\Models\Property\PropertyCategory;
use App\Models\Location\Location;
use App\Models\Admin\Menu;
use App\Models\SubscriptionPlan;
use App\Models\Property\PropertyViews;
use App\Models\UserManagement\User;
use App\Models\Admin\Permission;
use App\Models\Property\Area;
use App\Models\Property\Amenity;
use App\Models\Property\Feature;
use App\Models\UserManagement\Agency;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

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
            $data['menus'] = Menu::with('children')->whereNull('parent_id')->where('status', 1)->orderBy('order')->get();
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
        $data['menus']       = Menu::with('children')->whereNull('parent_id')->where('status', 1)->orderBy('order')->get();

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
        $type_info = PropertyCategory::where('status', 1)->get();
        return $type_info;
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

if (!function_exists('property_views_save')) {
    function property_views_save($property_id, $user_id = null)
    {
        $today_date = date('Y-m-d');

        $view_info = PropertyViews::where('property_id', $property_id)
            ->whereDate('viewed_date', $today_date)
            ->first();

        if ($view_info) {
            $view_info->increment('view_count');
        } else {
            PropertyViews::create([
                'property_id' => $property_id,
                'user_id'     => $user_id,
                'view_count'  => 1,
                'viewed_date' => $today_date,
            ]);
        }
    }
}

if (!function_exists('formatCount')) {
    function formatCount($count)
    {
        if ($count >= 1000) {
            $rounded = floor($count / 1000) * 1000;
            return number_format($rounded) . '+';
        }

        return $count . '+';
    }
}

if (!function_exists('sincePosted')) {
    function sincePosted($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }
}

if (!function_exists('getCountry')) {
    function getCountry()
    {
        return Country::select('id', 'name')->get();
    }
}

if (!function_exists('getCity')) {
    function getCity()
    {
        return City::select('id', 'name', 'country_id')
            ->get();
    }
}

if (!function_exists('getDistrict')) {
    function getDistrict($city_id = null)
    {
        return District::select('id', 'name', 'city_id')
            ->get();
    }
}

if (!function_exists('getCommune')) {
    function getCommune($district_id = null)
    {
        return Commune::select('id', 'name', 'district_id')
            ->get();
    }
}

if (!function_exists('getAmenity')) {
    function getAmenity()
    {
        return Amenity::where('status', 1)->select('id', 'name_en', 'name_kh')
            ->get();
    }
}

if (!function_exists('getFeature')) {
    function getFeature()
    {
        return Feature::where('status', 1)->select('id', 'name_en', 'name_kh')
            ->get();
    }
}

if (!function_exists('getUser')) {
    function getUser()
    {
        return User::all();
    }
}

if (!function_exists('getAgency')) {
    function getAgency()
    {
        return Agency::all();
    }
}

if (!function_exists('getPurposes')) {
    function getPurposes()
    {
        return [ 
            ['value' => 'sale', 'name' => 'Sale',], 
            ['value' => 'rent', 'name' => 'Rent',], 
            ['value' => 'sale_rent', 'name' => 'Sale / Rent',] 
        ];
    }
}

if (!function_exists('getStatuses')) {
    function getStatuses()
    {
        return [ 
            ['value' => 'active', 'name' => 'Active',], 
            ['value' => 'inactive', 'name' => 'Inactive',], 
            ['value' => 'draft', 'name' => 'Draft',], 
            ['value' => 'sold', 'name' => 'Sold',], 
            ['value' => 'rented', 'name' => 'Rented',] 
        ];
    }
}

if (!function_exists('getRentalPeriods')) {
    function getRentalPeriods()
    {
        return [ 
            ['value' => 'daily', 'name' => 'Daily'], 
            ['value' => 'monthly', 'name' => 'Monthly',], 
            ['value' => 'yearly', 'name' => 'Yearly',], 
        ];
    }
}

if (!function_exists('getAreas')) {
    function getAreas()
    {
        $areas = Area::select('id', 'name')->get();
        return $areas;
    }
}

if (!function_exists('getDirections')) {
    function getDirections()
    {
        return [
            ['value' => 'north', 'name' => 'North'],
            ['value' => 'east', 'name' => 'East'],
            ['value' => 'south', 'name' => 'South'],
            ['value' => 'west', 'name' => 'West'],
            ['value' => 'north_east', 'name' => 'North East'],
            ['value' => 'north_west', 'name' => 'North West'],
            ['value' => 'south_east', 'name' => 'South East'],
            ['value' => 'south_west', 'name' => 'South West'],
        ];
    }
}

if (!function_exists('getFurnishing')) {
    function getFurnishing()
    {
        return [
            ['value' => 'unfurnished', 'name' => 'Unfurnished'],
            ['value' => 'semi_furnished', 'name' => 'Semi Furnished'],
            ['value' => 'fully_furnished', 'name' => 'Fully Furnished'],
        ];
    }
}


