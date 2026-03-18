<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UserManagement\AgentDataTable;
use App\Models\UserManagement\Agency;
use App\Models\UserManagement\Agent;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index(AgentDataTable $dataTable)
    {
        return $dataTable->render('user-management.agents.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title  = __('global.add_new');
                $form   = new Agent();
                $action = route('users-management.agents.add');

                return response()->json([
                    'title'   => $title,
                    'status'  => 'success',
                    'message' => 'success',
                    'html'    => view('user-management.agents.form', compact('title', 'form', 'action'))->render(),
                    'modal'   => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'user_id'          => 'required|exists:users,id',
                    'agency_id'        => 'nullable|exists:agencies,id',
                    'rating'           => 'nullable|numeric|min:0|max:5',
                    'experience_years' => 'nullable|integer|min:0',
                    'total_sales'      => 'nullable|integer|min:0',
                ]);

                Agent::create([
                    'user_id'          => $request->user_id,
                    'agency_id'        => $request->agency_id,
                    'license_number'   => $request->license_number,
                    'experience_years' => $request->experience_years,
                    'bio'              => $request->bio,
                    'rating'           => $request->rating ?? 0,
                    'total_sales'      => $request->total_sales ?? 0,
                ]);

                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Agent created successfully',
                    'redirect' => route('users-management.agents.index'),
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => __('messages.405'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title  = __('global.edit');
                $form   = Agent::findOrFail($request->id);
                $action = route('users-management.agents.edit', ['id' => $request->id]);

                return response()->json([
                    'title'   => $title,
                    'status'  => 'success',
                    'message' => 'success',
                    'html'    => view('user-management.agents.form', compact('title', 'form', 'action'))->render(),
                    'modal'   => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'user_id'          => 'required|exists:users,id',
                    'agency_id'        => 'nullable|exists:agencies,id',
                    'rating'           => 'nullable|numeric|min:0|max:5',
                    'experience_years' => 'nullable|integer|min:0',
                    'total_sales'      => 'nullable|integer|min:0',
                ]);

                $agent = Agent::findOrFail($request->id);

                $agent->user_id          = $request->user_id;
                $agent->agency_id        = $request->agency_id;
                $agent->license_number   = $request->license_number;
                $agent->experience_years = $request->experience_years;
                $agent->bio              = $request->bio;
                $agent->rating           = $request->rating ?? 0;
                $agent->total_sales      = $request->total_sales ?? 0;
                $agent->save();

                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Agent updated successfully',
                    'redirect' => route('users-management.agents.index'),
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => __('messages.405'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {

            $agent = Agent::findOrFail($request->id);
            $agent->delete();

            return response()->json([
                'status'   => 'success',
                'message'  => 'Agent deleted successfully',
                'redirect' => route('users-management.agents.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
