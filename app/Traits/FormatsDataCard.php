<?php

namespace App\Traits;

trait FormatsDataCard
{
    public function transformProperties($properties)
    {
        return $properties->map(function ($property) {
            return [
                'id'         => $property->id,
                'featured'   => (bool) $property->featured,
                'status'     => $property->status,
                'image'      => $property->main_image,
                'gallery'    => $property->gallery,
                'price'      => $property->price_label ?? '$' . number_format($property->price, 0),
                'favorite'   => (bool) $property->is_favourite,
                'name'       => $property->title,
                'address'    => $property->address,
                'bedrooms'   => $property->rooms ?? 0,
                'bathrooms'  => $property->bathrooms ?? 0,
                'size'       => $property->area_size ?? $property->land_size ?? 'N/A',
                'category'   => $property->type_name_en,
                'agent'      => [
                    'image'      => $property->agent_profile,
                    'name'       => $property->agent_name,
                    'experience' => $this->formatExperience($property->agent_experience_years ?? null),
                ],
            ];
        })->values();
    }

    public function transformPropertyDetail($property, $galleryImages = [])
    {
        return [
            'id'           => $property->id,
            'code'         => $property->id, // swap for a dedicated property_code field if you have one
            'featured'     => (bool) $property->featured,
            'verified'     => (bool) $property->verified,
            'status'       => $property->status,
            'purpose'      => $property->purpose,
            'name'         => $property->title,
            'description'  => $property->description,
            'address'      => $property->address,
            'image'        => $property->main_image,
            'gallery'      => $galleryImages->pluck('image')->values()->all(),
            'videoTour'    => $property->video_url,
            'virtualTour'  => $property->virtual_tour_url,
            'floorPlan'    => $property->floor_plan_image,

            'price'        => $property->price_label ?? '$' . number_format($property->price, 0),
            'priceNegotiable' => (bool) $property->price_negotiable,
            'rentalPeriod' => $property->rental_period,
            'currency'     => $property->currency,

            'category'     => $property->type_name_en,
            'bedrooms'     => $property->bedrooms ?? 0,
            'bathrooms'    => $property->bathrooms ?? 0,
            'garages'      => $property->garages ?? 0,
            'rooms'        => $property->rooms ?? 0,
            'size'         => $property->area_size ?? $property->land_size ?? 'N/A',
            'furnishing'   => $property->furnishing,
            'yearBuilt'    => $property->year_built,
            'updateDate'   => $property->updated_at,
            'phone'        => $property->phone,
            'favorite'     => (bool) $property->is_favourite,
            'totalViews'   => (int) ($property->total_views ?? 0),

            'addressDetail' => [
                'area'    => $property->area_name,
                'state'   => $property->province_name,
                'province'    => $property->province_name,
                'district'=> $property->district_name,
                'commune' => $property->commune_name,
                'zip'     => $property->zip ?? '',
                'country' => $property->country ?? 'Cambodia',
            ],

            'latitude'   => $property->latitude,
            'longitude'  => $property->longitude,

            'amenities'  => collect($property->amenities ?? [])->pluck('name_en')->values()->all(),
            'features'   => collect($property->features ?? [])->pluck('name_en')->values()->all(),

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
        if (!$years) {
            return 'New agent';
        }

        return $years . ' ' . ($years === 1 ? 'year' : 'years');
    }
}