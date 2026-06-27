<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Models\Admin\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreInternalUserRequest;
use App\Http\Requests\UpdateInternalUserRequest;
use App\DataTables\UserManagement\InternalUserDataTable;

class InternalUserController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Admin::query(); }
        };
    }

    public function index(InternalUserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = new StoreInternalUserRequest();
                $form = $this->service->create($formRequest->validated());
                $form->roles()->sync($request->role_id);
                revoke_session($form->id);

                return $this->redirectResponse(
                    message: __('global.create_admin_successfully'),
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
                $formRequest = new UpdateInternalUserRequest();
                $form = $this->service->update($formRequest->validated());
                $form->roles()->sync($request->role_id);
                revoke_session($form->id);

                return $this->redirectResponse(
                    message: __('global.update_admin_successfully'),
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
    
    public function account()
    {
        $form = new Admin();
        $roles = Role::all();
        return view('admin.users.account', compact('form', 'roles'));
    }

    public function permission($id)
    {
        try {
            $form = Admin::find($id);
            $roles = Role::all();
            if (request()->isMethod('post')) {
                $form->roles()->sync(request()->role_id);
                return $this->jsonResponse(
                    message: __('messages.user_updated'),
                    type: 'success',
                    redirect: 'modal',
                    modal: 'action-modal',
                );
            }
            return $this->modalResponse(
                title: __('global.permission'),
                view:   'admin.users.permission',
                data:   ['form' => $form, 'roles' => $roles],
                action: route('users-management.permissions.edit', ['id' => $form->id]),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
    // delete user
    public function changePassword(Request $request, $id)
    {
        try {
            $form = User::find($id);
            if (request()->isMethod('post')) {
                $request->validate([
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]);
                $form->password = Hash::make($request->new_password);
                $form->save();
                
                return $this->jsonResponse(
                    message: __('messages.password_changed'),
                    type: 'success',
                    redirect: 'modal',
                    modal: 'action-modal',
                );
            }
            return $this->modalResponse(
                title: __('global.change_password'),
                view:   'admin.users.change-password',
                data:   ['form' => $form],
                action: route('settings.users-management.users.change-password', ['id' => $form->id]),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
    public function delete($id)
    {
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
                message: __('messages.delete_admin_successfully'),
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
