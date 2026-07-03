<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\DataTables\Interaction\RequestInfoDataTable;
use App\Http\Requests\Interaction\StoreRequestInfoRequest;
use App\Http\Requests\Interaction\ReplyRequestInfoRequest;
use App\Models\Interaction\RequestInfo;
use App\Services\BaseService;

class RequestInfoController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery()
            {
                return RequestInfo::query();
            }
        };
    }

    public function index(RequestInfoDataTable $dataTable)
    {
        return $dataTable->render('interaction.request-infos.index');
    }

    /**
     * Admin endpoint — reply to an inquiry.
     */
    public function reply(ReplyRequestInfoRequest $request, string $id)
    {
        try {
            $requestInfo = $this->service->update($request->validated(), $id);

            return $this->successResponse(
                message: __('messages.reply_request_info_success'),
                data: $requestInfo,
            );
        } catch (\Exception $ex) {
            return $this->errorResponse(
                message: $ex->getMessage(),
                code: 500
            );
        }
    }
}