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
     * Admin endpoint — view a single inquiry.
     */
    public function show(string $id)
    {
        try {
            $requestInfo = $this->service->find($id);

            return view('interaction.request-infos.show', compact('requestInfo'));
        } catch (\Throwable $ex) {
            report($ex);

            return $this->errorResponse(
                message: __('messages.something_went_wrong'),
                code: 500
            );
        }
    }

    /**
     * Admin endpoint — mark an inquiry as read.
     */
    public function markAsRead(string $id)
    {
        try {
            $requestInfo = $this->service->update(['status' => 'read'], $id);

            return $this->successResponse(
                message: __('messages.mark_as_read_success'),
                data: $requestInfo,
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
     * Admin endpoint — reply to an inquiry.
     */
    public function reply(ReplyRequestInfoRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'replied';
            $data['replied_by'] = auth()->id();
            $data['replied_at'] = now();

            $requestInfo = $this->service->update($data, $id);

            return $this->successResponse(
                message: __('messages.reply_request_info_success'),
                data: $requestInfo,
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
     * Admin endpoint — close an inquiry.
     */
    public function close(string $id)
    {
        try {
            $requestInfo = $this->service->update(['status' => 'closed'], $id);

            return $this->successResponse(
                message: __('messages.close_request_info_success'),
                data: $requestInfo,
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
     * Admin endpoint — delete an inquiry.
     */
    public function destroy(string $id)
    {
        try {
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