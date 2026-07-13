<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\DataTables\Interaction\RequestInfoDataTable;
use App\Http\Requests\Interaction\StoreRequestInfoRequest;
use App\Http\Requests\Interaction\ReplyRequestInfoRequest;
use App\Models\Interaction\RequestInfo;
use App\Services\BaseService;
use Illuminate\Http\Request;

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

    public function show(Request $request, string $id)
    {
        try {
            $requestInfo = RequestInfo::find($id);
            $requestInfo->load(['property', 'agent', 'user', 'messages']);
    
            $requestInfo->messages()
                ->where('sender', 'user')
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
    
            return $this->modalResponse(
            title: __('global.request-info'),
                view:  'interaction.request-infos.show',
                data:  ['requestInfo' => $requestInfo],
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
     * Admin endpoint — mark an inquiry's unread user messages as read.
     */
    public function markAsRead(string $id)
    {
        try {
            $requestInfo = $this->service->find($id);

            $requestInfo->messages()
                ->where('sender', 'user')
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

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

    public function reply(ReplyRequestInfoRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $requestInfo = $this->service->find($id);

            $message = $requestInfo->messages()->create([
                'sender'  => 'agent',
                'message' => $data['message'],
                'is_read' => true,
                'read_at' => now(),
            ]);

            if ($requestInfo->status === 'pending') {
                $requestInfo = $this->service->update(['status' => 'active'], $id);
            }

            return $this->successResponse(
                message: __('messages.reply_request_info_success'),
                data: $message,
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