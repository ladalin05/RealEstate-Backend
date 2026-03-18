<?php

namespace App\Http\Controllers\Property;

use Exception;
use App\Models\Property\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\DataTables\Property\PropertyTypeDataTable;

class PropertyTypeController extends Controller
{
    public function index(PropertyTypeDataTable $dataTable)
    {
        return $dataTable->render('property.property-type.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new PropertyType();
                $action = route('property.types.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('property.property-type.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {
                $request->validate([
                    'type_name'  => 'required',
                    'status'     => 'required',
                ]);

                $type_image = null;
                if ($request->hasFile('type_image')) {
                    $type_image = uploadImage($request->file('type_image'), null, 'images/types');
                };
                $type_slug = Str::slug($request->type_name, '-', null);
                $data_obj = PropertyType::create([
                    'type_name'  => addslashes($request->type_name),
                    'type_slug'  => $type_slug,
                    'type_image' => $type_image,
                    'status'     => $request->status ?? 0,
                ]);
            
                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.create_type_successfully'),
                    'redirect' => route('property.types.index'),
                ]);
            }
            
            return response()->json([
                'status' => 'error',
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

                $title = __('global.edit');
                $form = PropertyType::findOrFail($request->id);
                $action = route('property.types.edit', ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('property.property-type.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'type_name'  => 'required',
                    'status'     => 'required',
                ]);

                $data_obj = PropertyType::findOrFail($request->id);
                
                $type_image = updateImage($request->file('type_image'), $data_obj->type_image, 'images/types');

                $type_slug = Str::slug($request->type_name, '-', null);

                $data_obj->type_name  = addslashes($request->type_name);
                $data_obj->type_slug  = $type_slug;
                $data_obj->type_image = $type_image;
                $data_obj->status     = $request->status ?? 0;
                $data_obj->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.updated_type_successfully'),
                    'redirect' => route('property.types.index'),
                ]);
            }
            
            return response()->json([
                'status' => 'error',
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

            $country = PropertyType::findOrFail($request->id);
            $country->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Property deleted successfully',
                'redirect' => route('property.types.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }

    }
}
