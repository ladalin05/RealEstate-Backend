<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\DataTables\Property\PropertyDataTable;
use App\Repositories\PropertyRepository;
use App\Models\Property\PropertyGallery;
use App\Models\Property\Property;
use App\Services\PropertyService;
use App\Traits\FormatsDataCard;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    use FormatsDataCard;

    public function __construct(protected PropertyService $service, protected PropertyRepository $repository) {}

    public function index(PropertyDataTable $dataTable)
    {
        return $dataTable->render('property.properties.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $formRequest = app(StorePropertyRequest::class);
            $this->service->create($formRequest->validated());

            return $this->redirectResponse(
                message: __('messages.create_property_successfully'),
                route: route('property.properties.index'),
            );
        }

        return $this->viewResponse(
            view:   'property.properties.form',
            action: route('property.properties.add'),
            data:   [
                'page_title' => __('global.create_property'),
                'property' => new Property(),
            ],
        );
    }

    public function update(Request $request)
    {
        $property = Property::findOrFail($request->id);

        if ($request->isMethod('post')) {
            $formRequest = app(UpdatePropertyRequest::class);
            $this->service->update($formRequest->validated(), $property->id);

            return $this->redirectResponse(
                message: __('messages.update_property_successfully'),
                route: route('property.properties.index'),
            );
        }

        // Eager-load relations the edit form reads directly (amenities/features
        // multi-selects, gallery list) to avoid N+1 lazy loads in the view.
        $property->load(['amenities', 'features', 'property_image']);

        return $this->viewResponse(
            view:   'property.properties.form',
            action: route('property.properties.edit', ['id' => $property->id]),
            data:   [
                'page_title' => __('global.update_property'),
                'property' => $property,
            ],
        );
    }

    public function delete(Request $request)
    {
        $property = Property::findOrFail($request->id);
        $property->delete();

        return $this->redirectResponse(
            message: __('messages.delete_property_successfully'),
            route: route('property.properties.index'),
        );
    }

    public function showProperty(Request $request)
    {
        try {

            $property = $this->repository->getOneDetail($request->id);

            $gallery_images = PropertyGallery::where('property_id', $property->id)
                ->orderBy('id')
                ->get();

            return $this->modalResponse(
                title: __('global.property'),
                view:  'property.properties.show',
                data:  ['propertyInfo' => $this->transformPropertyDetail($property, $gallery_images)],
            );
        } catch (\Throwable $ex) {
            report($ex);
    
            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }
}