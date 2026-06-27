<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\DataTables\Property\AreaDataTable;
use App\Models\Property\Area;
use App\Http\Requests\Property\StoreAreaRequest;
use App\Http\Requests\Property\UpdateAreaRequest;
use App\Services\BaseService;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Area::query(); }
        };
    }

    public function index(AreaDataTable $dataTable)
    {
        return $dataTable->render('property.areas.index');
    }

    // Create new feature
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreAreaRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_area_successfully'),
                    route: route('property.areas.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'property.areas.form',
                data:   ['form' => new Area()],
                action: route('property.areas.add'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    // Update existing feature
    public function update(Request $request)
    {
        try {
            $form   = Area::findOrFail($request->id);
            
            if ($request->isMethod('post')) {
                $formRequest = app(UpdateAreaRequest::class);
                $this->service->update($formRequest->validated(), $form->id);

                return $this->redirectResponse(
                    message: __('messages.update_area_successfully'),
                    route: route('property.areas.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'property.areas.form',
                data:   ['form' => $form],
                action: route('property.areas.edit', ['id' => $request->id]),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    // Delete feature
    public function delete(Request $request)
    {
        try {
            $area = Area::findOrFail($request->id);
            $area->delete();

            return $this->redirectResponse(
                message: __('messages.delete_area_successfully'),
                route: route('property.areas.index'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}