<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\PropertyRepository;
use App\Services\PropertyService;
use App\Http\Requests\Property\ToggleFavouriteRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Property\Property;
use App\Models\Property\Favourite;
use App\Models\Property\PropertyGallery;
use App\Models\Location\Location;
use App\Models\Reports;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Traits\FormatsDataCard;

class PropertyController extends Controller
{
    use FormatsDataCard;
    
    public function __construct(protected PropertyService $service, protected PropertyRepository $repository)
    {
    }

    public function getProperty(Request $request)
    {
        $params['filter_by'] = ['properties.status' => 1];
        $params['sort_by']   = 'properties.id';
        $params['sort_dir']  = 'desc';

        $property = $this->repository->getAll($params);

        return $this->successResponse('Property list', $this->transformProperties($property));
    }

    public function getPropertyDetails(Request $request)
    { 
        $property = $this->repository->getOneDetail($request->property_id);

        if (!$property) {
            return $this->errorResponse('Property not found', 404);
        }

        $gallery_images = PropertyGallery::where('property_id', $property->id)
            ->orderBy('id')
            ->get();

        $latest_list = $this->repository->getList([
            'filter_by' => ['properties.status' => 'active'],
            'sort_by'   => 'id',
            'sort_dir'  => 'desc',
            'limit'     => 5,
        ]);
        
        $related_list = $this->repository->getList([
            'filter_by' => ['properties.status' => 'active', 'category_id' => $property->category_id],
            'sort_by'   => 'id',
            'sort_dir'  => 'desc',
            'limit'     => 5,
        ]);

        if($request->user_id !== null){
            property_views_save($property->id, $request->user_id);
        }

        return $this->successResponse('Property details', [
            'property'       => $this->transformPropertyDetail($property, $gallery_images),
            'related_list'   => $this->transformProperties($related_list),
            'latest_list'    => $this->transformProperties($latest_list),
        ]);
    }

    public function filterProperties(Request $request): JsonResponse
    {
        $property = $this->repository->filterProperties($request->all());

        return $this->successResponse('Property list', $this->transformProperties($property));
    }

    public function getDataFillter(): JsonResponse
    {
        $data = $this->service->getDataFillter();

        return $this->successResponse('Data fillter', $data);
    }
    

    public function toggleFavourite(ToggleFavouriteRequest $request): JsonResponse
    {
        $isFavourite = $this->service->toggleFavourite($request->validated());

        return $this->successResponse(
            $isFavourite ? 'Property added to favourites' : 'Property removed from favourites',
            $isFavourite
        );
    }


}
