<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Property\Property;
use App\Models\Property\Favourite;
use App\Models\Property\PropertyGallery;
use App\Models\Location\Location;
use App\Models\Reports;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
    public function getProperty(Request $request)
    {
        $property = Property::query()
                            ->join('property_type', 'properties.type_id', '=', 'property_type.id')
                            ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
                            ->leftJoin('property_locations', 'properties.id', '=', 'property_locations.property_id')
                            ->leftJoin('countries', 'property_locations.country_id', '=', 'countries.id')
                            ->leftJoin('cities', 'property_locations.city_id', '=', 'cities.id')
                            ->leftJoin('favourite', 'properties.id', '=', 'favourite.property_id')
                            ->leftJoin('users', 'properties.user_id', '=', 'users.id')
                            ->select(
                                'properties.*',
                                'property_type.type_name',
                                'users.avatar as user_image',
                                'users.name_en as user_name_en',
                                'users.name_kh as user_name_kh',
                                'countries.name as country',
                                'cities.name as city',
                                DB::raw("CONCAT(countries.name, ', ', cities.name, ', ', property_locations.address) as address"),
                                DB::raw("(SELECT MAX(pv.views) FROM property_views pv WHERE pv.property_id = properties.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM property_views pv WHERE pv.property_id = properties.id) AS post_date"),
                                DB::raw("IF(favourite.property_id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('properties.status', 1)
                            ->orderBy('properties.id', 'DESC')
                            ->distinct()
                            ->get();
              

        return response()->json([
            'status' => 'success',
            'property' => $property,
        ]);
    }

    
    public function getPropertyDetails($id)
    {
        $property = Property::join('property_type', 'properties.type_id', '=', 'property_type.id')
                            ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
                            ->leftJoin('property_locations', 'properties.id', '=', 'property_locations.property_id')
                            ->leftJoin('countries', 'property_locations.country_id', '=', 'countries.id')
                            ->leftJoin('cities', 'property_locations.city_id', '=', 'cities.id')
                            ->leftJoin('districts', 'property_locations.district_id', '=', 'districts.id')
                            ->leftJoin('communes', 'property_locations.commune_id', '=', 'communes.id')
                            ->leftJoin('favourite', 'properties.id', '=', 'favourite.property_id')
                            ->leftJoin('users', 'properties.user_id', '=', 'users.id')
                            ->selectRaw("
                                        properties.*,
                                        property_type.type_name,
                                        users.avatar as user_image,
                                        users.name_en as user_name,
                                        CONCAT(countries.name, ', ', cities.name, ', ', ', districts.name, ', ', ', communes.name, ', ', property_locations.address) as address,

                                        (SELECT MAX(pv.views)
                                            FROM property_views pv
                                            WHERE pv.property_id = properties.id) AS post_views,

                                        (SELECT MAX(pv.date)
                                            FROM property_views pv
                                            WHERE pv.property_id = properties.id) AS post_date,

                                        IF(favourite.property_id IS NOT NULL, 1, 0) as is_favourite,

                                        (SELECT GROUP_CONCAT(DISTINCT a.name_en)
                                            FROM property_amenities pa
                                            JOIN amenities a ON pa.amenity_id = a.id
                                            WHERE pa.property_id = properties.id) AS amenities,

                                        (SELECT GROUP_CONCAT(DISTINCT f.name_en)
                                            FROM property_features pf
                                            JOIN features f ON pf.feature_id = f.id
                                            WHERE pf.property_id = properties.id) AS features
                            ")
                            ->where('properties.status', 1)
                            ->where('properties.id', $id)
                            ->distinct()
                            ->first();

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $gallery_images = PropertyGallery::where('property_id', $property->id)
            ->orderBy('id')
            ->get();

        $latest_list = Property::join('property_type', 'properties.type_id', '=', 'property_type.id')
                            ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
                            ->leftJoin('property_locations', 'properties.id', '=', 'property_locations.property_id')
                            ->leftJoin('countries', 'property_locations.country_id', '=', 'countries.id')
                            ->leftJoin('cities', 'property_locations.city_id', '=', 'cities.id')
                            ->leftJoin('favourite', 'properties.id', '=', 'favourite.property_id')
                            ->leftJoin('users', 'properties.user_id', '=', 'users.id')
                            ->select(
                                'properties.*',
                                'property_type.type_name',
                                'users.avatar as user_image',
                                'users.name_en as user_name',
                                'countries.name as country',
                                'cities.name as city',
                                DB::raw("CONCAT(countries.name, ', ', cities.name, ', ', property_locations.address) as address"),
                                DB::raw("(SELECT MAX(pv.views) FROM property_views pv WHERE pv.property_id = properties.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM property_views pv WHERE pv.property_id = properties.id) AS post_date"),
                                DB::raw("IF(favourite.property_id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('properties.status', 1)
                            ->orderBy('id', 'DESC')
                            ->distinct()
                            ->limit(5)
                            ->get();

        $related_list = Property::join('property_type', 'properties.type_id', '=', 'property_type.id')
                            ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
                            ->leftJoin('property_locations', 'properties.id', '=', 'property_locations.property_id')
                            ->leftJoin('countries', 'property_locations.country_id', '=', 'countries.id')
                            ->leftJoin('cities', 'property_locations.city_id', '=', 'cities.id')
                            ->leftJoin('favourite', 'properties.id', '=', 'favourite.property_id')
                            ->leftJoin('users', 'properties.user_id', '=', 'users.id')
                            ->select(
                                'properties.*',
                                'property_type.type_name',
                                'users.avatar as user_image',
                                'users.name_en as user_name',
                                'countries.name as country',
                                'cities.name as city',
                                DB::raw("CONCAT(countries.name, ', ', cities.name, ', ', property_locations.address) as address"),
                                DB::raw("(SELECT MAX(pv.views) FROM property_views pv WHERE pv.property_id = properties.id) AS post_views"),
                                DB::raw("(SELECT MAX(pv.date) FROM property_views pv WHERE pv.property_id = properties.id) AS post_date"),
                                DB::raw("IF(favourite.property_id IS NOT NULL, 1, 0) as is_favourite")
                            )
                            ->where('properties.status', 1)
                            ->where('type_id', $property->type_id)
                            ->orderBy('id', 'DESC')
                            ->distinct()
                            ->limit(5)
                            ->get();

        // Save views
        property_views_save($id);

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
                'property_id' => 'required',
            ]);
            $isFavorite = Favourite::where('user_id',$request->user_id)->where('property_id', $request->property_id)->first();
            if(!$isFavorite){
                Favourite::create([
                            'user_id' =>  $request->user_id,
                            'property_id' => $request->property_id,
                        ]);
            } else {
                $isFavorite->delete();
            }

            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                ]);
            }
    }


}
