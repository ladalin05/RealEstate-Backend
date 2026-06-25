<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\UserManagement\AgentDataTable;
use App\Http\Requests\UserManagement\StoreAgentRequest;
use App\Http\Requests\UserManagement\UpdateAgentRequest;
use App\Models\UserManagement\Agent;
use App\Services\BaseService;

class AgentController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Agent::query(); }
        };
    }

    public function index(AgentDataTable $dataTable)
    {
        return $dataTable->render('user-management.agents.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreAgentRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('global.create_user_successfully'),
                    route: route('users-management.agents.index'),
                );
            }

            return $this->modalResponse(
                title: __('global.add_new'),
                view:   'user-management.agents.form',
                data:   ['form' => new Agent()],
                action: route('users-management.agents.add'),
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

            $agent = Agent::findOrFail($request->id);
            if ($request->isMethod('post')) {
                $formRequest = app(UpdateAgentRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('global.update_user_successfully'),
                    route: route('users-management.agents.index'),
                );
            }
            
            return $this->modalResponse(
                title: __('global.edit'),
                view:   'user-management.agents.form',
                data:   ['form' => $agent],
                action: route('users-management.agents.edit', ['id' => $request->id]),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete(Request $request)
    {
        try {

            $agent = Agent::findOrFail($request->id);
            $agent->delete();

            return $this->redirectResponse(
                message: __('global.delete_user_successfully'),
                route: route('users-management.agents.index'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}
