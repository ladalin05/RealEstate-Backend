<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyTypeRequest;
use App\Http\Requests\Property\UpdatePropertyTypeRequest;
use App\DataTables\Property\PropertyTypeDataTable;
use App\Models\Property\PropertyType;
use App\Services\BaseService;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    private BaseService $service;
    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return PropertyType::query(); }
        };
    }

    public function index(PropertyTypeDataTable $dataTable)
    {
        return $dataTable->render('property.property-type.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = new StorePropertyTypeRequest();
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_type_successfully'),
                    route: route('property.types.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'property.property-type.form',
                data:   ['form' => new PropertyType()],
                action: route('property.types.add'),
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
            $type = PropertyType::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = new UpdatePropertyTypeRequest();
                $this->service->update($formRequest->validated(), $type->id);

                return $this->redirectResponse(
                    message: __('global.updated_type_successfully'),
                    route: route('property.types.index'),
                );
            }

            return $this->modalResponse(
                title: __('global.edit'),
                view: 'property.property-type.form',
                data: ['form' => $type],
                action: route('property.types.edit', ['id' => $type->id]),
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
            $type = PropertyType::findOrFail($request->id);
            $type->delete();

            return $this->redirectResponse(
                message: __('global.deleted_type_successfully'),
                route: route('property.types.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}