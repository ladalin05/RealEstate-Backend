<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\UserManagement\User;
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
        return $dataTable->render('user-management.users.index');
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
