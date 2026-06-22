<?php

namespace App\Repositories;

use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PropertyRepository extends BaseRepository
{
    /**
     * "List" shape — used by getProperty(), latest_list, related_list.
     */
    protected function getQuery(): Builder
    {
        return Property::query()
            ->join('property_types', 'properties.type_id', '=', 'property_types.id')
            ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
            ->leftJoin('property_locations', 'properties.id', '=', 'property_locations.property_id')
            ->leftJoin('countries', 'property_locations.country_id', '=', 'countries.id')
            ->leftJoin('provinces', 'property_locations.province_id', '=', 'provinces.id')
            ->leftJoin('favourites', 'properties.id', '=', 'favourites.property_id')
            ->leftJoin('agents', 'properties.agent_id', '=', 'agents.id')
            ->select(
                'properties.*',
                'property_types.name_en as type_name_en',
                'property_types.name_kh as type_name_kh',
                'agents.profile_image as agent_profile',
                DB::raw("CONCAT(agents.first_name, ' ', agents.last_name) as agent_name"),
                'countries.name as country',
                'provinces.name as city',
                DB::raw("CONCAT(countries.name, ', ', provinces.name, ', ', property_locations.address) as address"),
                DB::raw("(SELECT MAX(pv.view_count) FROM property_views pv WHERE pv.property_id = properties.id) AS property_views"),
                DB::raw("(SELECT MAX(pv.viewed_date) FROM property_views pv WHERE pv.property_id = properties.id) AS property_viewed_date"),
                DB::raw("IF(favourites.property_id IS NOT NULL, 1, 0) as is_favourite")
            )
            ->distinct();
    }

    /**
     * "Detail" shape — used by getPropertyDetails(). Extra joins + subqueries.
     */
    protected function getDetailQuery(): Builder
    {
        return Property::query()
            ->join('property_types', 'properties.type_id', '=', 'property_types.id')
            ->leftJoin('property_views', 'properties.id', '=', 'property_views.property_id')
            ->leftJoin('property_locations', 'properties.id', '=', 'property_locations.property_id')
            ->leftJoin('countries', 'property_locations.country_id', '=', 'countries.id')
            ->leftJoin('provinces', 'property_locations.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'property_locations.district_id', '=', 'districts.id')
            ->leftJoin('communes', 'property_locations.commune_id', '=', 'communes.id')
            ->leftJoin('favourites', 'properties.id', '=', 'favourites.property_id')
            ->leftJoin('agents', 'properties.agent_id', '=', 'agents.id')
            ->selectRaw("
                properties.*,
                property_types.name_en as type_name_en,
                property_types.name_kh as type_name_kh,
                agents.profile_image as agent_profile,
                CONCAT(agents.first_name, ' ', agents.last_name) as agent_name,
                countries.name as country,
                provinces.name as city,
                CONCAT(countries.name, ', ', provinces.name, ', ', districts.name, ', ', communes.name, ', ', property_locations.address) as address,
                (SELECT MAX(pv.view_count) FROM property_views pv WHERE pv.property_id = properties.id) AS property_views,
                (SELECT MAX(pv.viewed_date) FROM property_views pv WHERE pv.property_id = properties.id) AS property_viewed_date,
                IF(favourites.property_id IS NOT NULL, 1, 0) as is_favourite,
                (SELECT GROUP_CONCAT(DISTINCT a.name_en) FROM property_amenities pa JOIN amenities a ON pa.amenity_id = a.id WHERE pa.property_id = properties.id) AS amenities,
                (SELECT GROUP_CONCAT(DISTINCT f.name_en) FROM property_features pf JOIN features f ON pf.feature_id = f.id WHERE pf.property_id = properties.id) AS features
            ")
            ->distinct();
    }

    /**
     * Always exclude inactive/disabled properties.
     */
    protected function applyDefaultScope(Builder $query): void
    {
        $query->where('properties.status', 1);
    }

    /**
     * Single property using the DETAIL query shape (not the list shape).
     */
    public function getOneDetail(array $params = [])
    {
        $query = $this->getDetailQuery();
        $this->applyDefaultScope($query);

        return $this->applyFiltersSortSearch($query, $params)->first();
    }
}