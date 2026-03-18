<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UserManagement\AgencyDataTable;
use App\Models\UserManagement\Agency;
use Illuminate\Support\Facades\Storage;

class AgencyController extends Controller
{
    public function index(AgencyDataTable $dataTable)
    {
        return $dataTable->render('user-management.agency.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new Agency();
                $action = route('users-management.agencies.add');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('user-management.agency.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'name' => 'required',
                    'email' => 'nullable|email',
                    'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                ]);

                $logo = null;
                if ($request->hasFile('logo')) {
                    $logo = uploadImage($request->file('logo'), null, 'images/agencies');
                }

                Agency::create([
                    'name'        => addslashes($request->name),
                    'logo'        => $logo,
                    'phone'       => $request->phone,
                    'email'       => $request->email,
                    'website'     => $request->website,
                    'address'     => $request->address,
                    'description' => $request->description,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Agency created successfully',
                    'redirect' => route('users-management.agencies.index'),
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function update(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.edit');
                $form = Agency::findOrFail($request->id);
                $action = route('users-management.agencies.edit', ['id' => $request->id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('user-management.agency.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'name' => 'required',
                    'email' => 'nullable|email',
                    'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                ]);

                $agency = Agency::findOrFail($request->id);

                // update logo
                $logo = updateImage(
                    $request->file('logo'),
                    $agency->logo,
                    'images/agencies'
                );

                $agency->name        = addslashes($request->name);
                $agency->logo        = $logo;
                $agency->phone       = $request->phone;
                $agency->email       = $request->email;
                $agency->website     = $request->website;
                $agency->address     = $request->address;
                $agency->description = $request->description;
                $agency->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Agency updated successfully',
                    'redirect' => route('users-management.agencies.index'),
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function delete(Request $request)
    {
        try {

            $agency = Agency::findOrFail($request->id);

            // delete logo if exists
            if ($agency->logo && file_exists(public_path('storage/' . $agency->logo))) {
                unlink(public_path('storage/' . $agency->logo));
            }

            $agency->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Agency deleted successfully',
                'redirect' => route('users-management.agencies.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }
}
