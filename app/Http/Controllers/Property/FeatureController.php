<?php

namespace App\Http\Controllers\Property;

use Exception;
use Illuminate\Support\Str;
use App\Models\Property\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Property\StoreFeatureRequest;
use App\Http\Requests\Property\UpdateFeatureRequest;
use App\Services\Base\BaseService;
use App\DataTables\Property\FeatureDataTable;
use App\Models\Property\Feature;

class FeatureController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Feature::query(); }
        };
    }

    public function index(FeatureDataTable $dataTable)
    {
        return $dataTable->render('property.features.index');
    }

    // Create new feature
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = new StoreFeatureRequest();
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.created_feature_successfully'),
                    route: route('property.features.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'property.features.form',
                data:   ['form' => new Feature()],
                action: route('property.features.add'),
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
            $form   = Feature::findOrFail($request->id);
            
            if ($request->isMethod('post')) {
                $formRequest = new UpdateFeatureRequest();
                $this->service->update($formRequest->validated(), $form->id);

                return $this->redirectResponse(
                    message: __('global.updated_feature_successfully'),
                    route: route('property.features.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'property.features.form',
                data:   ['form' => $form],
                action: route('property.features.edit', ['id' => $request->id]),
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
            $feature = Feature::findOrFail($request->id);
            $feature->delete();

            return $this->redirectResponse(
                message: __('global.deleted_feature_successfully'),
                route: route('property.features.index'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}
