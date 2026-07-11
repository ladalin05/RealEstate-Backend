<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Interaction\StoreTourScheduleRequest;
use App\Http\Requests\Interaction\StoreRequestInfoRequest;
use App\Models\Interaction\Tourschedule;
use App\Models\Interaction\Requestinfo;
use App\Services\BaseService;
use App\Traits\FormatsDataCard;

class InteractionController extends Controller
{
    use FormatsDataCard;

    private BaseService $service;

    public function __construct()
    {
        $this->tour_service = new class extends BaseService {
            protected function getQuery() { return Tourschedule::query(); }
        };
        $this->request_service = new class extends BaseService {
            protected function getQuery() { return Requestinfo::query(); }
        };
    }

    public function inquiry()
    {
        try {
            if(!auth('api')->user()) {
                return $this->errorResponse(
                    message: __('messages.unauthorized'),
                    code: 401
                );
            }
            $inquiry = $this->service->all();

            return $this->successResponse(
                message: __('messages.schedule_tour_success'),
                data: $this->formatDataCard($inquiry),
            );
        } catch (\Exception $ex) {
            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }

    public function scheduleTour(StoreTourScheduleRequest $request)
    {
        try {
            if(!auth('api')->user()) {
                return $this->errorResponse(
                    message: __('messages.unauthorized'),
                    code: 401
                );
            }
            $data = $request->validated();
            $data['user_id'] = auth('api')->id();
            $data['type'] = 'schedule-tour'; 
            $tour_schedule = $this->tour_service->create($data);

            return $this->successResponse(
                message: __('messages.schedule_tour_success'),
                data: null,
            );
        } catch (\Exception $ex) {
            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }

    public function requestInfo(StoreRequestInfoRequest $request)
    {
        try {
            if(!auth('api')->user()) {
                return $this->errorResponse(
                    message: __('messages.unauthorized'),
                    code: 401
                );
            }
            $data = $request->validated();
            $data['user_id'] = auth('api')->id();
            $inquiry = $this->request_service->create($data);

            return $this->successResponse(
                message: __('messages.request_info_success'),
                data: null,
            );
        } catch (\Exception $exception) {
            return $this->errorResponse(
                message: __('messages.internal_server_error'),
                code: 500
            );
        }
    }
}