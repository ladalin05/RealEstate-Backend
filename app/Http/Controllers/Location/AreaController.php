<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\Area;
use App\Models\Location\Province;
use App\Models\Location\District;
use App\Models\Location\Commune;
use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreAreaRequest;
use App\Http\Requests\Location\UpdateAreaRequest;
use App\Services\Base\BaseService;
use App\DataTables\Location\AreaDataTable;

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
        return $dataTable->render('location.areas.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreAreaRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_area_successfully'),
                    route: route('location.areas.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'location.areas.form',
                data:   [
                    'form'      => new Area(),
                    'provinces' => Province::orderBy('name')->get(),
                ],
                action: route('location.areas.add'),
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

            $area = Area::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateAreaRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('messages.update_area_successfully'),
                    route: route('location.areas.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'location.areas.form',
                data:   [
                    'form'      => $area,
                    'provinces' => Province::orderBy('name')->get(),
                    'districts' => $area->province_id
                        ? District::where('province_id', $area->province_id)->orderBy('name')->get()
                        : collect(),
                    'communes'  => $area->district_id
                        ? Commune::where('district_id', $area->district_id)->orderBy('name')->get()
                        : collect(),
                ],
                action: route('location.areas.edit', ['id' => $request->id]),
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

            $area = Area::findOrFail($request->id);
            $area->delete();

            return $this->redirectResponse(
                message: __('messages.delete_area_successfully'),
                route: route('location.areas.index'),
            );

        } catch (\Throwable $e) {

            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );

        }
    }

    /**
     * AJAX: get districts for a given province (for dependent dropdown).
     */
    public function getDistricts(Request $request)
    {
        $districts = District::where('province_id', $request->province_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    /**
     * AJAX: get communes for a given district (for dependent dropdown).
     */
    public function getCommunes(Request $request)
    {
        $communes = Commune::where('district_id', $request->district_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($communes);
    }

}