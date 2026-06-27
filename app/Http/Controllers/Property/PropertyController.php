<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\DataTables\Property\PropertyDataTable;
use App\Models\Property\Property;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(protected PropertyService $service) {}

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

        return view('property.properties.form', [
            'page_title' => __('global.add_property'),
            'isEdit'     => false,
            'property'   => null,
        ]);
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
}