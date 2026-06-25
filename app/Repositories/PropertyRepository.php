<?php

namespace App\Repositories;

use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PropertyRepository extends BaseRepository
{
    protected function getQuery(): Builder
    {
        return Property::query()
            ->join('property_types', 'properties.type_id', '=', 'property_types.id')
            ->leftJoin('areas', 'properties.area_id', '=', 'areas.id')
            ->leftJoin('provinces', 'areas.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'areas.district_id', '=', 'districts.id')
            ->leftJoin('communes', 'areas.commune_id', '=', 'communes.id')
            ->leftJoin('agents', 'properties.agent_id', '=', 'agents.id')
            ->select(
                'properties.id',
                'properties.title',
                'properties.status',
                'properties.purpose',
                'properties.featured',
                'properties.verified',
                'properties.price',
                'properties.price_label',
                'properties.currency',
                'properties.rental_period',
                'properties.rooms',
                'properties.bathrooms',
                'properties.area_size',
                'properties.land_size',
                'properties.furnishing',
                'properties.address',
                'properties.main_image',
                'property_types.name_en as type_name_en',
                'property_types.name_kh as type_name_kh',
                'areas.name as area_name',
                'provinces.name as province_name',
                'districts.name as district_name',
                'communes.name as commune_name',
                'agents.profile_image as agent_profile',
                'agents.experience_years as agent_experience',
                DB::raw("CONCAT(COALESCE(agents.first_name,''), ' ', COALESCE(agents.last_name,'')) as agent_name"),
                DB::raw("EXISTS(
                    SELECT 1 FROM favourites
                    WHERE favourites.property_id = properties.id
                      AND favourites.user_id = " . (auth()->id() ?? 'NULL') . "
                ) as is_favourite"),
            );
    }

    protected function getDetailQuery(): Builder
    {
        return Property::query()
            ->join('property_types', 'properties.type_id', '=', 'property_types.id')
            ->leftJoin('areas', 'properties.area_id', '=', 'areas.id')
            ->leftJoin('provinces', 'areas.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'areas.district_id', '=', 'districts.id')
            ->leftJoin('communes', 'areas.commune_id', '=', 'communes.id')
            ->leftJoin('agents', 'properties.agent_id', '=', 'agents.id')
            ->select(
                'properties.id',
                'properties.title',
                'properties.description',
                'properties.status',
                'properties.purpose',
                'properties.featured',
                'properties.verified',
                'properties.price',
                'properties.price_label',
                'properties.currency',
                'properties.price_negotiable',
                'properties.rental_period',
                'properties.rooms',
                'properties.bathrooms',
                'properties.floors',
                'properties.floor_number',
                'properties.area_size',
                'properties.land_size',
                'properties.furnishing',
                'properties.direction',
                'properties.year_built',
                'properties.phone',
                'properties.main_image',
                'properties.floor_plan_image',
                'properties.video_url',
                'properties.virtual_tour_url',
                'properties.address',
                'properties.latitude',
                'properties.longitude',
                'properties.published_at',
                'properties.updated_at',
                'property_types.name_en as type_name_en',
                'property_types.name_kh as type_name_kh',
                'areas.name as area_name',
                'provinces.name as province_name',
                'districts.name as district_name',
                'communes.name as commune_name',
                'agents.id as agent_id',
                'agents.profile_image as agent_profile',
                'agents.phone as agent_phone',
                'agents.email as agent_email',
                'agents.rating as agent_rating',
                'agents.experience_years as agent_experience',
                DB::raw("CONCAT(COALESCE(agents.first_name,''), ' ', COALESCE(agents.last_name,'')) as agent_name"),
                DB::raw("(
                    SELECT JSON_ARRAYAGG(pg.image ORDER BY pg.`order` ASC, pg.id ASC)
                    FROM property_gallery pg
                    WHERE pg.property_id = properties.id
                ) AS gallery"),
                DB::raw("(
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id',      f.id,
                            'name_en', f.name_en,
                            'name_kh', f.name_kh,
                            'icon',    f.icon
                        )
                        ORDER BY f.id ASC
                    )
                    FROM property_features pf
                    JOIN features f ON pf.feature_id = f.id
                    WHERE pf.property_id = properties.id
                      AND f.status = 1
                ) AS features"),
                DB::raw("(
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id',      a.id,
                            'name_en', a.name_en,
                            'name_kh', a.name_kh,
                            'icon',    a.icon
                        )
                        ORDER BY a.id ASC
                    )
                    FROM property_amenities pa
                    JOIN amenities a ON pa.amenity_id = a.id
                    WHERE pa.property_id = properties.id
                      AND a.status = 1
                ) AS amenities"),
                DB::raw("(
                    SELECT COALESCE(SUM(pv.view_count), 0)
                    FROM property_views pv
                    WHERE pv.property_id = properties.id
                ) AS total_views"),

                DB::raw("(
                    SELECT MAX(pv.viewed_date)
                    FROM property_views pv
                    WHERE pv.property_id = properties.id
                ) AS last_viewed_date"),
                DB::raw("EXISTS(
                    SELECT 1 FROM favourites
                    WHERE favourites.property_id = properties.id
                      AND favourites.user_id = " . (auth()->id() ?? 'NULL') . "
                ) as is_favourite"),
            );
    }

    protected function applyDefaultScope(Builder $query): void
    {
        $query
            ->where('properties.status', 'active')
            ->whereNull('properties.deleted_at');
    }

    public function getProperties(): Builder
    {
        $query = $this->getQuery();
        $this->applyDefaultScope($query);
        return $query;
    }

    public function getOneDetail(int $id): ?object
    {
        $query = $this->getDetailQuery();
        $this->applyDefaultScope($query);

        $property = $query
            ->where('properties.id', $id)
            ->first();

        if (!$property) return null;

        // Decode JSON subquery columns
        $property->gallery   = json_decode($property->gallery   ?? '[]') ?? [];
        $property->features  = json_decode($property->features  ?? '[]') ?? [];
        $property->amenities = json_decode($property->amenities ?? '[]') ?? [];

        return $property;
    }

    public function filterProperties(array $requestParams): mixed
    {
        $filters = [];

        if (!empty($requestParams['type_id'])) {
            $filters['properties.type_id'] = $requestParams['type_id'];
        }

        if (!empty($requestParams['area_id'])) {
            $filters['properties.area_id'] = $requestParams['area_id'];
        }

        if (!empty($requestParams['purpose'])) {
            $filters['properties.purpose'] = $requestParams['purpose'];
        }

        if (!empty($requestParams['furnishing'])) {
            $filters['properties.furnishing'] = $requestParams['furnishing'];
        }

        if (!empty($requestParams['bathrooms'])) {
            $filters['properties.bathrooms'] = $requestParams['bathrooms'];
        }

        if (!empty($requestParams['rooms'])) {
            $filters['properties.rooms'] = $requestParams['rooms'];
        }

        $params = [
            'filter_by' => $filters,
            'search'    => $requestParams['search']    ?? null,
            'columns'   => ['properties.title', 'properties.description', 'properties.address'],
            'sort_by'   => $requestParams['sort_by']   ?? 'properties.id',
            'sort_dir'  => $requestParams['sort_dir']  ?? 'desc',
            'limit'     => $requestParams['limit']     ?? 12,
            'min_price' => $requestParams['min_price'] ?? null,
            'max_price' => $requestParams['max_price'] ?? null,
        ];

        return $this->getList($params);
    }
}