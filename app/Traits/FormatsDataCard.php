<?php

namespace App\Traits;

trait FormatsDataCard
{
    public function transformProperties($properties)
    {
        return $properties->map(function ($property) {
            return [
                'id'         => $property->id,
                'code'       => $property->property_code,
                'featured'   => (bool) $property->featured,
                'status'     => $property->status,
                'image'      => $property->main_image,
                'gallery'    => $property->gallery,
                'year_built' => $property->year_built,
                'price'      => $property->price_label ?? '$' . number_format($property->price, 0),
                'favorite'   => (bool) $property->is_favourite,
                'name_en'    => $property->title_en,
                'name_km'    => $property->title_kh,
                'address_en' => $property->address_en,
                'address_km' => $property->address_kh,
                'bedrooms'   => $property->rooms ?? 0,
                'bathrooms'  => $property->bathrooms ?? 0,
                'size'       => $property->area_size ?? $property->land_size ?? 'N/A',
                'category_en'   => $property->category_name_en,
                'category_km'   => $property->category_name_km,
                'agent'      => [
                    'image'      => $property->agent_profile,
                    'name'       => $property->agent_name,
                    'experience' => $this->formatExperience($property->agent_experience ?? null),
                ],
            ];
        })->values();
    }

    public function transformPropertyDetail($property, $galleryImages = [])
    {
        return [
            'id'           => $property->id,
            'code'         => $property->property_code,
            'featured'     => (bool) $property->featured,
            'verified'     => (bool) $property->verified,
            'status'       => $property->status,
            'purpose'      => $property->purpose,
            'name_en'      => $property->title_en,
            'name_km'      => $property->title_kh,
            'description_en' => $property->description_en,
            'description_km' => $property->description_kh,
            'address_en'   => $property->address_en,
            'address_km'   => $property->address_kh,
            'image'        => $property->main_image,
            'gallery'      => $galleryImages->pluck('image')->values()->all(),
            'videoTour'    => $property->video_url,
            'virtualTour'  => $property->virtual_tour_url,
            'floorPlan'    => $property->floor_plan_image,

            'price'        => $property->price_label ?? '$' . number_format($property->price, 0),
            'priceNegotiable' => (bool) $property->price_negotiable,
            'rentalPeriod' => $property->rental_period,
            'currency'     => $property->currency,
            'category_en'   => $property->category_name_en,
            'category_km'   => $property->category_name_km,
            'bedrooms'     => $property->bedrooms ?? 0,
            'bathrooms'    => $property->bathrooms ?? 0,
            'garages'      => $property->garages ?? 0,
            'garage_size'  => $property->garage_size ?? 'N/A',
            'rooms'        => $property->rooms ?? 0,
            'size'         => $property->area_size ?? $property->land_size ?? 'N/A',
            'furnishing'   => $property->furnishing,
            'year_built'    => $property->year_built,
            'updateDate'   => $property->updated_at,
            'phone'        => $property->phone,
            'favorite'     => (bool) $property->is_favourite,
            'totalViews'   => (int) ($property->total_views ?? 0),

            'addressDetail' => [
                'area_en'      => $property->area_name_en,
                'area_km'      => $property->area_name_km,
                'state_en'     => $property->province_name_en,
                'state_km'     => $property->province_name_km,
                'province_en'  => $property->province_name_en,
                'province_km'  => $property->province_name_km,
                'district_en'  => $property->district_name_en,
                'district_km'  => $property->district_name_km,
                'commune_en'   => $property->commune_name_en,
                'commune_km'   => $property->commune_name_km,
                'zip_code'     => $property->zip_code,
                'country_en'   => $property->country_en ?? 'Cambodia',
                'country_km'   => $property->country_kh ?? 'កម្ពុជា',
            ],

            'latitude'   => $property->latitude,
            'longitude'  => $property->longitude,

            'amenities_en'  => collect($property->amenities ?? [])->pluck('name_en')->values()->all(),
            'amenities_km'  => collect($property->amenities ?? [])->pluck('name_kh')->values()->all(),
            'features_en'   => collect($property->features ?? [])->pluck('name_en')->values()->all(),
            'features_km'   => collect($property->features ?? [])->pluck('name_kh')->values()->all(),

            'agent'      => [
                'id'         => $property->agent_id,
                'image'      => $property->agent_profile,
                'name'       => $property->agent_name,
                'phone'      => $property->agent_phone,
                'email'      => $property->agent_email,
                'rating'     => $property->agent_rating,
                'experience' => $this->formatExperience($property->agent_experience ?? null),
            ],
        ];
    }

    public function transformPropertyCategories($propertyCategories)
    {
        return $propertyCategories->map(function ($propertyCategory) {
            return [
                'id'             => $propertyCategory->id,
                'name_en'        => $propertyCategory->name_en,
                'name_km'        => $propertyCategory->name_km,
                'slug'           => $propertyCategory->slug,
                'image'          => $propertyCategory->image,
                'property_count' => $propertyCategory->property_count,
            ];
        })->values();
    }

    public function transformAgents($agents)
    {
        return $agents->map(function ($agent) {
            return [
                'id'              => $agent->id,
                'name'            => trim($agent->first_name . ' ' . $agent->last_name),
                'company'         => $agent->company_name,
                'phone'           => $agent->phone,
                'officePhone'     => $agent->company_phone,
                'email'           => $agent->email,
                'profile_image'   => $agent->profile_image,
                'bio'             => $agent->bio,
                'experience'      => $this->formatExperience($agent->experience_years ?? null),
                'specializations' => $agent->specializations ?? [],
                'rating'          => (float) ($agent->rating ?? 0),
                'review_count'    => $agent->review_count ?? 0,
                'total_sales'     => $agent->total_sales ?? 0,
                'total_rentals'   => $agent->total_rentals ?? 0,
                'social_links'    => $agent->social_links ?? [],
            ];
        })->values();
    }

    public function formatExperience(?int $years): string
    {
        if ($years == 0) {
            return 'new_agent';
        }

        return $years . ' ' . ($years === 1 ? 'year' : 'years');
    }
}