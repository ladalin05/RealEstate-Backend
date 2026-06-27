<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyCategoryRequest;
use App\Http\Requests\Property\UpdatePropertyCategoryRequest;
use App\DataTables\Property\PropertyCategoryDataTable;
use App\Models\Property\PropertyCategory;
use App\Services\BaseService;
use Illuminate\Http\Request;

class PropertyCategoryController extends Controller
{
    private BaseService $service;
    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return PropertyCategory::query(); }
        };
    }

    public function index(PropertyCategoryDataTable $dataTable)
    {
        return $dataTable->render('property.property-type.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StorePropertyCategoryRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_category_successfully'),
                    route: route('property.types.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'property.property-type.form',
                data:   ['form' => new PropertyCategory()],
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
            $type = PropertyCategory::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdatePropertyCategoryRequest::class);
                $this->service->update($formRequest->validated(), $type->id);

                return $this->redirectResponse(
                    message: __('messages.update_category_successfully'),
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
            $type = PropertyCategory::findOrFail($request->id);
            $type->delete();

            return $this->redirectResponse(
                message: __('messages.delete_category_successfully'),
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