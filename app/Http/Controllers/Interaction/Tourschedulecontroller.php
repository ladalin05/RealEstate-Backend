<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\DataTables\Interaction\TourScheduleDataTable;
use App\Http\Requests\Interaction\UpdateTourScheduleStatusRequest;
use App\Models\Interaction\TourSchedule;
use App\Services\BaseService;
use Illuminate\Http\Request;

class TourScheduleController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery()
            {
                return TourSchedule::query();
            }
        };
    }

    public function index(TourScheduleDataTable $dataTable)
    {
        return $dataTable->render('interaction.tour-schedules.index');
    }

    /**
     * Admin endpoint — view a single tour schedule.
     * GET /tour-schedules/show?id=123
     */
    public function show(Request $request)
    {
        try {
            $id = $request->query('id');

            $tourSchedule = TourSchedule::findOrFail($id);
            $tourSchedule->load(['property', 'agent', 'user']);

            return $this->modalResponse(
                title: __('global.tour-schedule'),
                view:  'interaction.tour-schedules.show',
                data:  ['tourSchedule' => $tourSchedule],
            );
        } catch (\Throwable $ex) {
            report($ex);

            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }

    /**
     * Admin endpoint — confirm a pending tour request.
     * PATCH /tour-schedules/confirm  { id: 123 }
     */
    public function confirm(UpdateTourScheduleStatusRequest $request)
    {
        try {
            $data = $request->validated();

            $tourSchedule = $this->service->update([
                'status'     => 'confirmed',
                'handled_by' => auth('web')->id(),
                'handled_at' => now(),
            ], $data['id']);

            return $this->successResponse(
                message: __('messages.update_tour_status_success'),
                data: $tourSchedule,
            );
        } catch (\Throwable $ex) {
            report($ex);

            return $this->errorResponse(
                message: __('messages.something_went_wrong'),
                code: 500
            );
        }
    }

    /**
     * Admin endpoint — reject a pending tour request.
     * PATCH /tour-schedules/reject  { id: 123 }
     */
    public function reject(UpdateTourScheduleStatusRequest $request)
    {
        try {
            $data = $request->validated();

            $tourSchedule = $this->service->update([
                'status'     => 'rejected',
                'handled_by' => auth('web')->id(),
                'handled_at' => now(),
            ], $data['id']);

            return $this->successResponse(
                message: __('messages.update_tour_status_success'),
                data: $tourSchedule,
            );
        } catch (\Throwable $ex) {
            report($ex);

            return $this->errorResponse(
                message: __('messages.something_went_wrong'),
                code: 500
            );
        }
    }

    /**
     * Admin endpoint — delete a tour schedule.
     * GET /tour-schedules/delete?id=123
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->query('id');

            $this->service->delete($id);

            return $this->successResponse(
                message: __('messages.delete_success'),
            );
        } catch (\Throwable $ex) {
            report($ex);

            return $this->errorResponse(
                message: __('messages.something_went_wrong'),
                code: 500
            );
        }
    }
}