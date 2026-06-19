<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\DataTables\UserManagement\RoleDataTable;
use App\Http\Requests\UserManagement\StoreRoleRequest;
use App\Http\Requests\UserManagement\UpdateRoleRequest;
use App\Services\BaseService;

class RoleController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Role::query(); }
        };
    }

    public function index(RoleDataTable $dataTable) {
        return $dataTable->render('admin.roles.index');
    }

    public function create(Request $request) {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreRoleRequest::class);
                $permissions = array_filter(array_unique(json_decode($request->permissions, true) ?? []));
                $administrator = $request->boolean('administrator');

                $role = $this->service->create($formRequest->validated());

                if (!$administrator && $permissions) {
                    $rolePermissions = collect($permissions)
                        ->filter(fn($p) => $p !== 'all')
                        ->map(fn($permission_id) => [
                            'role_id'       => $role->id,
                            'permission_id' => $permission_id,
                            'created_at'    => now(),
                        ])->toArray();

                    DB::table('role_permission')->insert($rolePermissions);
                }

                return $this->redirectResponse(
                    message: __('messages.role_saved'),
                    route:   route('users-management.roles.index'),
                );
            }

            $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();

            return $this->viewResponse(
                view:   'admin.roles.form',
                action: route('users-management.roles.add'),
                data:   [
                    'menus' => $menus,
                    'form' => new Role(),
                ],
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code:    500,
            );
        }
    }

    public function update(Request $request) {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateRoleRequest::class);

                $permissions = json_decode($request->input('permissions', '[]'), true) ?: [];
                $administrator = $request->boolean('administrator');

                $role = $this->service->update($formRequest->validated(), $request->id);

                // Revoke sessions for all users assigned to this role
                if ($role->user->count() > 0) {
                    foreach ($role->user as $user) {
                        revoke_session($user->id);
                    }
                }
                DB::table('role_permission')->where('role_id', $request->id)->delete();
                if (!$administrator && $permissions) {
                    $rolePermissions = collect($permissions)
                        ->filter(fn($p) => $p !== 'all')
                        ->map(fn($permission_id) => [
                            'role_id'       => $request->id,
                            'permission_id' => $permission_id,
                            'created_at'    => now(),
                        ])->toArray();

                    DB::table('role_permission')->insert($rolePermissions);
                }

                return $this->redirectResponse(
                    message: __('messages.role_updated'),
                    route:   route('users-management.roles.index'),
                );
            }

            $form = Role::findOrFail($request->id);
            $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();

            $access = [];

            if($form->permissions) {
                foreach ($form->permissions as $permission) {
                    $access[$permission->slug]   = $permission->id;
                    $access[$permission->menu_id] = $permission->id;
                }
            }
                

            return $this->viewResponse(
                view:   'admin.roles.form',
                action: route('users-management.roles.edit', ['id' => $request->id]),
                data:   [
                    'form' => $form,
                    'access' => $access,
                    'menus' => $menus
                ],
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code:    500,
            );
        }
    }

    public function permission(Request $request)
    {
        return view('admin.roles.permission');
    }

    public function detele(Request $request)
    {
        try {
            if($request->id == 1) {
                return $this->errorResponse(
                    message: __('messages.role_cannot_delete'),
                    code: 403,
                );
            }
            $role = Role::find($request->id);
            $role->delete();
            return $this->successResponse(
                message: __('messages.role_deleted'),
                route: route('settings.users-management.roles.index'),
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code:    500,
            );
        }
    }
}
