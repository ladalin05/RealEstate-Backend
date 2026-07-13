<?php

namespace App\Services;

use App\Models\Property\Area;
use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;
use App\Repositories\CMSRepository;
use App\Models\UserManagement\Agent;
use App\Models\Property\PropertyCategory;
use App\Repositories\PropertyRepository;
use App\Models\Interaction\RequestInfo;
use App\Models\Interaction\TourSchedule;
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
            'featuredProperties' => $this->getFeaturedProperties(4),
            'propertyCategories' => $this->getPropertyCategories(),
            'propertiesForRent'  => $this->getPropertiesForRent(),
            'agents'             => $this->getAgents(),
        ];
    }

    public function getUserDashboard(): array
    {
        return [
            'favouriteProperties' => $this->getFavouriteProperties(),
            'tourSchedules' => $this->getTourSchedule(),
            'inquiries' => $this->getInquiry()
        ];
    }

    public function getAreas()
    {
        return Area::query()
            ->leftJoin('properties', function ($join) {
                $join->on('properties.area_id', '=', 'areas.id')
                    ->where('properties.status', 'active')
                    ->whereNull('properties.deleted_at');
            })
            ->where('areas.status', 1)
            ->orderBy('areas.name_en')
            ->groupBy('areas.id', 'areas.name_en', 'areas.name_km', 'areas.slug', 'areas.image')
            ->select([
                'areas.id',
                'areas.name_en',
                'areas.name_km',
                'areas.slug',
                'areas.image',
                DB::raw('COUNT(properties.id) as properties_count'),
            ])
            ->get();
    }

    public function getStats(): array
    {
        $totalProperties = Property::whereNull('deleted_at')->count();
        $totalAgents = Agent::count();
        $verifiedProperties = Property::where('verified', 1)->count();

        return [
            ['title' => formatCount($totalProperties), 'description_en' => 'Property listings actively managed on our platform.','description_km' => 'អចលនទ្រព្យដែលគ្រប់គ្រងយ៉ាងសកម្មនៅលើវេទិការបស់យើង។'],
            ['title' => formatCount($totalAgents), 'description_en' => 'Verified agents helping clients buy, sell, and rent.','description_km' => 'ភ្នាក់ងារអចលនទ្រព្យដែលផ្ទៀងផ្ទាត់ជួយអតិថិជនទិញ លក់ និងជួល។'],
            ['title' => formatCount($verifiedProperties), 'description_en' => 'Listings verified for accuracy and trust.','description_km' => 'បញ្ជីអចលនទ្រព្យដែលផ្ទៀងផ្ទាត់ភាពត្រឹមត្រូវ និងទុកចិត្ត។'],
            ['title' => '10+', 'description_en' => 'Years of experience serving the real estate market.','description_km' => '10+ ឆ្នាំនៃបទពិសោធន៍បម្រើទីផ្សារអចលនទ្រព្យ។'],
        ];
    }

    public function getFeaturedProperties(int $limit = 4)
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
            ->groupBy('property_categories.id', 'property_categories.name_en', 'property_categories.name_km', 'property_categories.slug', 'property_categories.image')
            ->orderByDesc('property_count')
            ->limit(6)
            ->select([
                'property_categories.id',
                'property_categories.name_en',
                'property_categories.name_km',
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
            ->leftJoin('properties', function ($join) {
                $join->on('properties.agent_id', '=', 'agents.id')
                    ->whereNull('properties.deleted_at');
            })
            ->where('agents.status', 'active')
            ->orderBy('agents.rating', 'desc')
            ->limit(3)
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
                'agents.rating', 'agents.review_count', 
                'agents.total_sales', 
                'agents.total_rentals', 
                'agents.social_links',
                DB::raw('COUNT(DISTINCT properties.id) as properties_count'),
            ])
            ->groupBy(
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
                'agents.social_links'
            )
            ->get();
        return $this->transformAgents($agents);
    }

    public function getFavouriteProperties()
    {
        $properties = $this->propertyRepository->getFavouriteProperties(auth('api')->id());
        return $this->transformProperties($properties);
    }

    public function getTourSchedule()
    {
        $tourSchedules = TourSchedule::query()
                            ->join('properties', 'tour_schedules.property_id', 'properties.id')
                            ->select('tour_schedules.*', 'properties.title_en as property_title_en', 'properties.title_kh as property_title_kh')
                            ->where('tour_schedules.user_id', auth('api')->id())
                            ->orderBy('tour_schedules.schedule_date', 'desc')
                            ->get();
        return $this->transformTourSchedules($tourSchedules);
    }

    public function getInquiry()
    { 
        $requestInfos = RequestInfo::query()
            ->where('user_id', auth('api')->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return $this->transformRequestInfos($requestInfos);
    }

}
