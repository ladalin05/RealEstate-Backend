<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\StoreTourScheduleRequest;
use App\Http\Requests\Interaction\UpdateTourScheduleStatusRequest;
use App\Models\Interaction\TourSchedule;
use App\Services\BaseService;

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

    /**
     * Public endpoint — a visitor (guest or logged-in user) books a property tour.
     */
    public function store(StoreTourScheduleRequest $request)
    {
        try {
            $tourSchedule = $this->service->create($request->validated());

            return $this->successResponse(
                message: __('messages.schedule_tour_success'),
                data: $tourSchedule,
            );
        } catch (\Exception $ex) {
            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }

    /**
     * Admin endpoint — confirm or reject a pending tour request.
     */
    public function updateStatus(UpdateTourScheduleStatusRequest $request, string $id)
    {
        try {
            $tourSchedule = $this->service->update($request->validated(), $id);

            return $this->successResponse(
                message: __('messages.update_tour_status_success'),
                data: $tourSchedule,
            );
        } catch (\Exception $ex) {
            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }
}