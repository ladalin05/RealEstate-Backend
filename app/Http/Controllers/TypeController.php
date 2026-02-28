<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\DataTables\TypeDataTable;

class TypeController extends Controller
{
    public function index(TypeDataTable $dataTable)
    {
        return $dataTable->render('pages.type.index');
    }

    public function create(Request $request)
    {


        if ($request->isMethod('post')) {

            try {
                $request->validate([
                    'type_name'  => 'required',
                    'status'     => 'required',
                ]);

                $type_image = null;
                if ($request->hasFile('type_image')) {
                    $type_image = uploadImage($request->file('type_image'), null, 'images/types');
                };
                $type_slug = Str::slug($request->type_name, '-', null);
                $data_obj = Type::create([
                    'type_name'  => addslashes($request->type_name),
                    'type_slug'  => $type_slug,
                    'type_image' => $type_image,
                    'status'     => $request->status ?? 0,
                ]);
            
                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.create_type_successfully'),
                    'redirect' => route('type.index'),
                ]);

            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        $page_title = __('global.create_types');
        return view('pages.type.create', compact('page_title'));

    }

    public function edit($page_id, Request $request)
    {
        $page_title = __('global.edit_type');
        $data_obj = Type::findOrFail($request->id);

        if ($request->isMethod('post')) {
            try {
                $request->validate([
                    'type_name'  => 'required',
                    'status'     => 'required',
                ]);
                
                $type_image = updateImage($request->file('type_image'), $data_obj->type_image, 'images/types');

                $type_slug = Str::slug($request->type_name, '-', null);

                $data_obj->type_name  = addslashes($request->type_name);
                $data_obj->type_slug  = $type_slug;
                $data_obj->type_image = $type_image;
                $data_obj->status     = $request->status ?? 0;

                $data_obj->save(); // save changes

                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.updated_type_successfully'),
                    'redirect' => route('type.index'),
                ]);

            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return view('pages.type.edit', compact('page_title', 'data_obj'));
    }

    public function delete($id)
    {

        $data_obj = Type::findOrFail($id);
        $data_obj->delete();

        Session::flash('flash_message', __('global.delete_type_successfully'));
        return redirect()->route('type.index');
    }
}
