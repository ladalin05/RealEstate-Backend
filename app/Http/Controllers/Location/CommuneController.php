<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location\Commune;
use App\Models\Location\District;
use App\Services\BaseService;
use App\DataTables\Location\CommuneDataTable;
use App\Http\Requests\Location\StoreCommuneRequest;
use App\Http\Requests\Location\UpdateCommuneRequest;

class CommuneController extends Controller
{

    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Commune::query(); }
        };
    }

    public function index(CommuneDataTable $dataTable)
    {
        return $dataTable->render('location.communes.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreCommuneRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_commune_successfully'),
                    route:   route('location.communes.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'location.communes.form',
                data:   ['form' => new Commune()],
                action: route('location.communes.add'),
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                status: 500
            );

        }
    }

    public function update(Request $request)
    {
        try {

            $commune = Commune::findOrFail($request->id);
            
            if ($request->isMethod('post')) {
                $formRequest = app(UpdateCommuneRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('global.update_commune_successfully'),
                    route:   route('location.communes.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'location.communes.form',
                data:   ['form' => $commune],
                action: route('location.communes.edit', ['id' => $request->id]),
            ); 

        } catch (\Exception $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                status: 500
            );

        }
    }

    public function destroy(Request $request)
    {
        try {

            $commune = Commune::findOrFail($request->id);
            $commune->delete();

            return $this->redirectResponse(
                message: __('global.deleted_commune_successfully'),
                route:   route('location.communes.index'),
            );

        } catch (\Throwable $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                status: 500
            );

        }
    }

}