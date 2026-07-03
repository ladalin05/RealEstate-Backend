<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\BaseService;
use App\Models\Interest;
use App\Http\Requests\Property\StoreInquiryRequest;
use App\Traits\FormatsDataCard;

class InterestController extends Controller
{
    use FormatsDataCard;
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Area::query(); }
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

    public function scheduleTour(StoreInquiryRequest $request)
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
            $inquiry = $this->service->create($data);

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

    public function requestInfo(StoreInquiryRequest $request)
    {
        try {
            $data = $request->all();
            $data['user_id'] = auth('api')->id();
            $data['type'] = 'request-info';
            $inquiry = $this->service->create($data);

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