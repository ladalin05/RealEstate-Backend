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
                'gallery'    => [],
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

    public function transformPropertyCategories($propertyCategories)
    {
        return $propertyCategories->map(function ($propertyCategory) {
            return [
                'id'             => $propertyCategory->id,
                'name'           => $propertyCategory->name_en,
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