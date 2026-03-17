<?php

namespace App\Http\Controllers;

use App\Models\Location\City;
use App\Models\Location\Commune;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Product\Category;
use App\Models\Setting\Unit;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function getCountry()
    {
        return response()->json(
            Country::select('id', 'name')->get()
        );
    }

    public function getCity(Request $request)
    {
        $country_id = $request->country_id;

        return response()->json(
            City::when($country_id, function ($q) use ($country_id) {
                    $q->where('country_id', $country_id);
                })
                ->select('id', 'name', 'country_id')
                ->get()
        );
    }

    public function getDistrict(Request $request)
    {
        $city_id = $request->city_id;

        return response()->json(
            District::when($city_id, function ($q) use ($city_id) {
                    $q->where('city_id', $city_id);
                })
                ->select('id', 'name', 'city_id')
                ->get()
        );
    }

    public function getCommune(Request $request)
    {
        $district_id = $request->district_id;

        return response()->json(
            Commune::when($district_id, function ($q) use ($district_id) {
                    $q->where('district_id', $district_id);
                })
                ->select('id', 'name', 'district_id')
                ->get()
        );
    }
}