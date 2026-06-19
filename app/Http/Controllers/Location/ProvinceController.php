<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreProvinceRequest;
use App\Http\Requests\Location\UpdateProvinceRequest;
use App\Services\Base\BaseService;
use App\DataTables\Location\ProvinceDataTable;

class ProvinceController extends Controller
{

    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Province::query(); }
        };
    }

    public function index(ProvinceDataTable $dataTable)
    {
        return $dataTable->render('location.provinces.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreProvinceRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_province_successfully'),
                    route: route('location.provinces.index'),
                );
            }
            
            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'location.provinces.form',
                data:   ['form' => new Province()],
                action: route('location.provinces.add'),
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

            $province = Province::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateProvinceRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('global.updated_province_successfully'),
                    route: route('location.provinces.index'),
                );
            }
            
            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'location.provinces.form',
                data:   ['form' => $province],
                action: route('location.provinces.edit', ['id' => $request->id]),
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

            $city = City::findOrFail($request->id);
            $city->delete();

            return $this->redirectResponse(
                message: __('global.deleted_province_successfully'),
                route: route('location.provinces.index'),
            );

        } catch (\Throwable $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );

        }
    }

}