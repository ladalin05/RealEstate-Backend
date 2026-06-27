<?php

namespace App\Services;

use App\Models\Property\Area;
use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;
use App\Repositories\CMSRepository;
use App\Models\UserManagement\Agent;
use App\Models\Property\PropertyCategory;
use App\Repositories\PropertyRepository;
use App\Traits\FormatsDataCard;

class CMSService
{
    use FormatsDataCard;

    private PropertyRepository $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getHomeData(): array
    {
        return [
            'areas'              => $this->getAreas(),
            'stats'              => $this->getStats(),
            'featuredProperties' => $this->getFeaturedProperties(8),
            'propertyCategories' => $this->getPropertyCategories(),
            'propertiesForRent'  => $this->getPropertiesForRent(),
            'agents'             => $this->getAgents(),
        ];
    }

    public function getAreas()
    {
        return Area::query()
            ->where('status', 1)
            ->orderBy('name')
            ->select(['id', 'name', 'slug', 'image'])
            ->get();
    }

    public function getStats(): array
    {
        $totalProperties = Property::whereNull('deleted_at')->count();
        $totalAgents = Agent::count();
        $verifiedProperties = Property::where('verified', 1)->count();

        return [
            ['title' => formatCount($totalProperties), 'description' => 'Property listings actively managed on our platform.'],
            ['title' => formatCount($totalAgents), 'description' => 'Verified agents helping clients buy, sell, and rent.'],
            ['title' => formatCount($verifiedProperties), 'description' => 'Listings verified for accuracy and trust.'],
            ['title' => '10+', 'description' => 'Years of experience serving the real estate market.'],
        ];
    }

    public function getFeaturedProperties(int $limit = 8)
    {
        $properties = $this->propertyRepository->getProperties()
                            ->where('properties.status', 'active')
                            ->where('properties.featured', 1)
                            ->whereNull('properties.deleted_at')
                            ->where(function ($q) {
                                $q->whereNull('properties.expires_at')
                                ->orWhere('properties.expires_at', '>', now());
                            })
                            ->orderBy('properties.published_at', 'desc')
                            ->limit($limit)
                            ->get();
        return $this->transformProperties($properties);
    }

    public function getPropertyCategories()
    {
        $categories = PropertyCategory::query()
            ->where('property_categories.status', 1)
            ->leftJoin('properties', function ($join) {
                $join->on('properties.category_id', '=', 'property_categories.id')
                    ->where('properties.status', 'active')
                    ->whereNull('properties.deleted_at');
            })
            ->groupBy('property_categories.id', 'property_categories.name_en', 'property_categories.slug', 'property_categories.image')
            ->orderByDesc('property_count')
            ->limit(6)
            ->select([
                'property_categories.id',
                'property_categories.name_en',
                'property_categories.slug',
                'property_categories.image',
                DB::raw('COUNT(properties.id) as property_count'),
            ])
            ->get();

        return $this->transformPropertyCategories($categories);
    }

    public function getPropertiesForRent()
    {
        $properties = $this->propertyRepository->getProperties()
                            ->where('properties.status', 'active')
                            ->where('properties.purpose', 'rent')
                            ->whereNull('properties.deleted_at')
                            ->where(function ($q) {
                                $q->whereNull('properties.expires_at')
                                ->orWhere('properties.expires_at', '>', now());
                            })
                            ->orderBy('properties.published_at', 'desc')
                            ->limit(10)
                            ->get();
        return $this->transformProperties($properties);
    }

    public function getAgents()
    {
        $agents = Agent::query()
            ->where('status', 'active')
            ->orderBy('rating', 'desc')
            ->limit(3)
            ->select([
                'id', 'first_name', 'last_name', 'email', 'phone',
                'profile_image', 'bio', 'experience_years', 'specializations',
                'rating', 'review_count', 'total_sales', 'total_rentals', 'social_links',
            ])
            ->get();
        return $this->transformAgents($agents);
    }

}
