<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\Admin\UserDataTable;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }
    public function add()
    {
        $title = __('global.add_new');
        $form = new User();
        $roles = Role::all();
        return view('admin.users.form', compact('title', 'form', 'roles'));
    }
    public function account()
    {
        $form = new User();
        $roles = Role::all();
        return view('admin.users.account', compact('form', 'roles'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = User::find($id);
        $roles = Role::all();
        return view('admin.users.form', compact('title', 'form', 'roles'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            // svae data:image/jpeg;base64, for avatar
            $avatar = $request->avatar;
            if(!empty($avatar)){ 
                $request->validate([
                    'avatar' => 'required|string|starts_with:data:image/jpeg;base64,',
                ]);
                
                $avatar = base64_decode(preg_replace('/^data:image\/jpeg;base64,/', '', $avatar));

                // Generate a unique file name
                $avatar_name = 'uploads/' . Str::random(10) . '.jpg';

                // Save the image to storage
                Storage::disk('public')->put($avatar_name, $avatar);

                // Get the public URL of the stored image
                $imageUrl = Storage::url($avatar_name);


            }
            $request->validate([
                'first_name_en' => 'required',
                'last_name_en' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'role_id' => 'required',
            ]);
            $data = [
                'first_name_en' => $request->first_name_en,
                'last_name_en' => $request->last_name_en,
                'first_name_kh' => $request->first_name_kh,
                'last_name_kh' => $request->last_name_kh,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'address_kh' => $request->address_kh,
            ];
            if ($request->password) {
                $request->validate([
                    'password' => 'required|min:6'
                ]);
                $data['password'] = Hash::make($request->password);
            }
            $form = User::updateOrCreate(['id' => $id], $data);
            $form->roles()->sync($request->role_id);
            revoke_session($form->id);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('settings.users-management.users.index'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function permission($id)
    {
        try {
            $form = User::find($id);
            $roles = Role::all();
            if (request()->isMethod('get')) {
                return json([
                    'title' => __('global.permission'),
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('admin.users.permission', compact('form', 'roles'))->render(),
                ]);
            }
            if (request()->isMethod('post')) {
                $form->roles()->sync(request()->role_id);
                return json([
                    'status' => 'success',
                    'message' => __('messages.user_updated'),
                    'redirect' => 'modal',
                    'modal' => 'action-modal',
                ]);
            }
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    // delete user
    public function changePassword($id)
    {
        try {
            $form = User::find($id);
            if (request()->isMethod('get')) {
                return json([
                    'title' => __('global.change_password'),
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('admin.users.change-password', compact('form'))->render(),
                ]);
            }
            if (request()->isMethod('post')) {
                $request = request();
                $request->validate([
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]);
                $form->password = Hash::make($request->new_password);
                $form->save();
                return json([
                    'status' => 'success',
                    'message' => __('messages.password_changed'),
                    'redirect' => 'modal',
                    'modal' => 'action-modal',
                ]);
            }
            return json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function delete($id)
    {
        try {
            if ($id == 1) {
                return json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }
            $form = User::find($id);
            $form->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
