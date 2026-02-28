<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Property;
use App\Models\Favourite;
use App\Models\PropertyGallery;
use App\Models\Location;
use App\Models\Reports;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
    public function getProperty(Request $request)
    {
        $property = Property::query()
                            ->join('type', 'property.type_id', '=', 'type.id')
                            ->leftJoin('locations', 'property.location_id', '=', 'locations.id')
                            ->leftJoin('users', 'property.user_id', '=', 'users.id')
                            ->join('user_inform', 'users.id', '=', 'user_inform.user_id')
                            ->leftJoin('favourite', 'property.id', '=', 'favourite.post_id')
                            ->select(
                                'property.*',
                                'type.type_name',
                                'locations.name as location_name',
                                'users.display_name as user_name',
                                'user_inform.image as user_image',
                                DB::raw("(SELECT MAX(pv.post_views) FROM post_views pv WHERE pv.post_id = property.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM post_views pv WHERE pv.post_id = property.id) AS post_date"),
                                DB::raw("IF(favourite.id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('property.status', 1)
                            ->orderBy('property.featured')
                            ->orderBy('property.id', 'DESC')
                            ->get();
              

        return response()->json([
            'status' => 'success',
            'property' => $property,
        ]);
    }

    
    public function getPropertyDetails($id)
    {
        $property = Property::join('type', 'property.type_id', '=', 'type.id')
                            ->leftJoin('locations', 'property.location_id', '=', 'locations.id')
                            ->leftJoin('users', 'property.user_id', '=', 'users.id')
                            ->join('user_inform', 'users.id', '=', 'user_inform.user_id')
                            ->leftJoin('favourite', 'property.id', '=', 'favourite.post_id')
                            ->select(
                                'property.*',
                                'type.type_name',
                                'locations.name as location_name',
                                'users.display_name as user_name',
                                'user_inform.image as user_image',
                                DB::raw("(SELECT MAX(pv.post_views) FROM post_views pv WHERE pv.post_id = property.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM post_views pv WHERE pv.post_id = property.id) AS post_date"),
                                DB::raw("IF(favourite.id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('property.status', 1)
                            ->where('property.id', $id)
                            ->first();

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $gallery_images = PropertyGallery::where('post_id', $property->id)
            ->orderBy('id')
            ->get();

        $latest_list = Property::join('type', 'property.type_id', '=', 'type.id')
                            ->leftJoin('locations', 'property.location_id', '=', 'locations.id')
                            ->leftJoin('users', 'property.user_id', '=', 'users.id')
                            ->join('user_inform', 'users.id', '=', 'user_inform.user_id')
                            ->leftJoin('favourite', 'property.id', '=', 'favourite.post_id')
                            ->select(
                                'property.*',
                                'type.type_name',
                                'locations.name as location_name',
                                'users.display_name as user_name',
                                'user_inform.image as user_image',
                                DB::raw("(SELECT MAX(pv.post_views) FROM post_views pv WHERE pv.post_id = property.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM post_views pv WHERE pv.post_id = property.id) AS post_date"),
                                DB::raw("IF(favourite.id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('property.status', 1)
                            ->where('property.id', $id)
                            ->orderBy('id', 'DESC')
                            ->limit(5)
                            ->get();

        $related_list = Property::join('type', 'property.type_id', '=', 'type.id')
                            ->leftJoin('locations', 'property.location_id', '=', 'locations.id')
                            ->leftJoin('users', 'property.user_id', '=', 'users.id')
                            ->join('user_inform', 'users.id', '=', 'user_inform.user_id')
                            ->leftJoin('favourite', 'property.id', '=', 'favourite.post_id')
                            ->select(
                                'property.*',
                                'type.type_name',
                                'locations.name as location_name',
                                'users.display_name as user_name',
                                'user_inform.image as user_image',
                                DB::raw("(SELECT MAX(pv.post_views) FROM post_views pv WHERE pv.post_id = property.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM post_views pv WHERE pv.post_id = property.id) AS post_date"),
                                DB::raw("IF(favourite.id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('property.status', 1)
                            ->where('type_id', $property->type_id)
                            ->orderBy('id', 'DESC')
                            ->limit(5)
                            ->get();

        // Save views
        post_views_save($id, 'Property');

        return response()->json([
            'success'        => true,
            'property'       => $property,
            'gallery_images' => $gallery_images,
            'related_list'   => $related_list,
            'latest_list'    => $latest_list,
            'user_id'        => $property->user_id,
        ]);
    }

    public function is_favourit(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'post_id' => 'required',
            ]);
            $isFavorite = Favourite::where('user_id',$request->user_id)->where('post_id', $request->post_id)->first();
            if(!$isFavorite){
                $favorit = Favourite::create([
                            'user_id' =>  $request->user_id,
                            'post_id' => $request->post_id,
                            'post_type' => 'Property',
                        ]);
            } else {
                $isFavorite->delete();
            }

            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $e) {
                \Log::error($e->getMessage());
                            dd($e->getMessage());
                Session::flash('error_flash_message', $e->getMessage());
            }
    }


}
