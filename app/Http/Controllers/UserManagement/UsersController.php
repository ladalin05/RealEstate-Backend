<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Models\Admin\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\DataTables\UserManagement\UserDataTable;

class UsersController extends Controller
{
    private BaseService $service;

    public function __construct() {
        $this->service = new class extends BaseService {
            protected function getQuery() { return User::query(); }
        };
    }

    public function index(UserDataTable $dataTable) {
        return $dataTable->render('admin.users.index');
    }

    public function create(Request $request) {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreUserRequest::class);
                $form = $this->service->create($formRequest->validated());
                $form->roles()->sync($request->role_id);
                revoke_session($form->id);

                return $this->redirectResponse(
                    message: __('global.create_user_successfully'),
                    route: route('users-management.internal-users.index'),
                );
            }
            return $this->viewResponse(
                view:   'admin.users.form',
                data:   ['form' => new Admin()],
                action: route('users-management.internal-users.create'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request) {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(UpdateUserRequest::class);
                $form = $this->service->update($formRequest->validated());
                $form->roles()->sync($request->role_id);
                revoke_session($form->id);

                return $this->redirectResponse(
                    message: __('global.update_user_successfully'),
                    route: route('users-management.internal-users.index'),
                );
            }
            return $this->viewResponse(
                view:   'admin.users.form',
                data:   ['form' => $this->service->find($request->id)],
                action: route('users-management.internal-users.update', $request->id),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete($id) {
        try {
            if ($id == 1) {
                return $this->errorResponse(
                    message: __('messages.user_cannot_delete'),
                    code: 403,
                );
            }
            $form = User::find($id);
            $form->delete();
            return $this->successResponse(
                message: __('messages.user_deleted'),
                route: route('settings.users-management.users.index'),
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}
