<?php

namespace App\Services;

use App\Models\UserManagement\Agent;
use App\Traits\FormatsDataCard;
use App\Repositories\PropertyRepository;
use App\Models\Property\PropertyCategory;
use Illuminate\Support\Facades\DB;

class AgentService
{
    use FormatsDataCard;

    protected PropertyRepository $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAgentsData()
    {
        return [
            'agents' => $this->getAgents(),
            'featuredProperties' => $this->getFeaturedProperties(),
            'categories' => $this->getCategories(),
        ];
    }

    public function getAgents()
    {
        $agents = Agent::query()
                            ->where('status', 'active')
                            ->orderBy('agents.rating', 'desc')
                            ->crossJoin('company_profile', function($join) {
                                $join->where('company_profile.id', 1);
                            })
                            ->select([
                                'agents.id',
                                'agents.first_name',
                                'agents.last_name',
                                'agents.email',
                                'agents.phone',
                                'agents.profile_image',
                                'agents.bio',
                                'agents.experience_years',
                                'agents.specializations',
                                'agents.rating',
                                'agents.review_count',
                                'agents.total_sales',
                                'agents.total_rentals',
                                'agents.social_links',
                                'company_profile.name as company_name',
                                'company_profile.phone as company_phone',
                            ])
                            ->get();
        return $this->transformAgents($agents);
    }
    
    public function getFeaturedProperties()
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
                            ->limit(4)
                            ->get();
        return $this->transformProperties($properties);
    }

    public function getCategories()
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

}
