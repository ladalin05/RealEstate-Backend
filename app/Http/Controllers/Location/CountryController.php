<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Http\Controllers\Controller;
use App\Services\BaseService;
use App\Http\Requests\Location\StoreCountryRequest;
use App\Http\Requests\Location\UpdateCountryRequest;
use App\DataTables\Location\CountryDataTable;

class CountryController extends Controller
{

    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Country::query(); }
        };
    }

    public function index(CountryDataTable $dataTable)
    {
        return $dataTable->render('location.countries.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {

                $formRequest = app(StoreCountryRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_country_successfully'),
                    route: route('location.countries.index'),
                );

            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'location.countries.form',
                data:   ['form' => new Country()],
                action: route('location.countries.add'),
            );
            
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function update(Request $request)
    {
        try {

            $country = Country::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateCountryRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('global.update_country_successfully'),
                    route: route('location.countries.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'location.countries.form',
                data:   ['form' => $country],
                action: route('location.countries.edit', ['id' => $request->id]),
            );

        } catch (\Exception $e) {

            return $this->jsonResponse(
                status: 'error',
                message: $e->getMessage()
            );

        }
    }

    public function destroy(Request $request)
    {
        try {

            $country = Country::findOrFail($request->id);
            $country->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Country deleted successfully',
                'redirect' => route('location.countries.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

}