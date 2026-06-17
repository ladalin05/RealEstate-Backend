<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Http\Controllers\Controller;
use App\Services\BaseService;
use App\Http\Requests\Location\UpdateDistrictRequest;
use App\Http\Requests\Location\StoreDistrictRequest;
use App\DataTables\Location\DistrictDataTable;

class DistrictController extends Controller
{
    
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return District::query(); }
        };
    }

    public function index(DistrictDataTable $dataTable)
    {
        return $dataTable->render('location.districts.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = new StoreDistrictRequest();
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_district_successfully'),
                    route: route('location.districts.index'),
                );
            }
            
            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'location.districts.form',
                data:   ['form' => new District()],
                action: route('location.districts.add'),
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );

        }
    }

    public function update(Request $request)
    {
        try {

            $district = District::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = new UpdateDistrictRequest();
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('global.updated_district_successfully'),
                    route: route('location.districts.index'),
                );
            }
            
            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'location.districts.form',
                data:   ['form' => $district],
                action: route('location.districts.edit', ['id' => $request->id]),
            );

        } catch (\Throwable $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );

        }
    }

    public function destroy(Request $request)
    {
        try {

            $district = District::findOrFail($request->id);
            $district->delete();

            return $this->redirectResponse(
                message: __('global.deleted_district_successfully'),
                route: route('location.districts.index'),
            );

        } catch (\Throwable $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );

        }
    }

}