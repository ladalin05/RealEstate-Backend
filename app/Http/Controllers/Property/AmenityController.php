<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StoreAmenityRequest;
use App\Http\Requests\Property\UpdateAmenityRequest;
use App\DataTables\Property\AmenityDataTable;
use App\Models\Property\Amenity;
use App\Services\BaseService;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Amenity::query(); }
        };
    }

    public function index(AmenityDataTable $dataTable)
    {
        return $dataTable->render('property.amenities.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = new StoreAmenityRequest();
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_amenity_successfully'),
                    route: route('property.amenities.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'property.amenities.form',
                data:   ['form' => new Amenity()],
                action: route('property.amenities.add'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $amenity = Amenity::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = new UpdateAmenityRequest();
                $this->service->update($formRequest->validated(), $amenity->id);

                return $this->redirectResponse(
                    message: __('global.updated_amenity_successfully'),
                    route: route('property.amenities.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'property.amenities.form',
                data:   ['form' => $amenity],
                action: route('property.amenities.edit', ['id' => $request->id]),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete(Request $request)
    {
        try {
            $amenity = Amenity::findOrFail($request->id);
            $amenity->delete();

            return $this->redirectResponse(
                message: __('global.deleted_amenity_successfully'),
                route: route('property.amenities.index'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}