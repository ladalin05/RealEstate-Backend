<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Agent;
use App\Services\AgentService;

class AgentController extends Controller
{

    private AgentService $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function getAllAgents(Request $request)
    {
        $agents = $this->agentService->getAgentsData();

        return $this->successResponse('Agents fetched successfully', $agents);
    }

    public function getAgentDetail(Request $request)
    {
        $agent = $this->agentService->getAgentDetailData($request->id);

        return $this->successResponse('Agent fetched successfully', $agent);
    }
}