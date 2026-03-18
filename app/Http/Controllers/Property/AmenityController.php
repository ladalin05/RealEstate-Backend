<?php

namespace App\Http\Controllers\Property;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Property\AmenityDataTable;
use App\Models\Property\Amenity;

class AmenityController extends Controller
{
    public function index(AmenityDataTable $dataTable)
    {
        return $dataTable->render('property.amenities.index');
    }

    public function create(Request $request)
    {
        try {
            // ✅ Show form (GET)
            if ($request->isMethod('get')) {

                $title  = __('global.add_new');
                $form   = new Amenity();
                $action = route('property.amenities.add');

                return response()->json([
                    'title'  => $title,
                    'status' => 'success',
                    'html'   => view('property.amenities.form', compact('title', 'form', 'action'))->render(),
                    'modal'  => 'action-modal',
                ]);
            }

            // ✅ Store data (POST)
            if ($request->isMethod('post')) {

                $request->validate([
                    'name_en' => 'required|string|max:255',
                    'name_kh' => 'nullable|string|max:255',
                    'icon'    => 'nullable|string|max:255', // fontawesome class
                    'status'  => 'required|in:0,1',
                ]);

                Amenity::create([
                    'name_en' => addslashes($request->name_en),
                    'name_kh' => addslashes($request->name_kh),
                    'icon'    => $request->icon, // ex: fa fa-wifi
                    'status'  => $request->status ?? 1,
                ]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Amenity created successfully',
                    'redirect'=> route('property.amenities.index'),
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

            // ✅ Show edit form (GET)
            if ($request->isMethod('get')) {

                $title  = __('global.edit');
                $form   = Amenity::findOrFail($request->id);
                $action = route('property.amenities.edit', ['id' => $request->id]);

                return response()->json([
                    'title'  => $title,
                    'status' => 'success',
                    'html'   => view('property.amenities.form', compact('title', 'form', 'action'))->render(),
                    'modal'  => 'action-modal',
                ]);
            }

            // ✅ Update data (POST)
            if ($request->isMethod('post')) {

                $request->validate([
                    'name_en' => 'required|string|max:255',
                    'name_kh' => 'nullable|string|max:255',
                    'icon'    => 'nullable|string|max:255',
                    'status'  => 'required|in:0,1',
                ]);

                $amenity = Amenity::findOrFail($request->id);

                $amenity->name_en = addslashes($request->name_en);
                $amenity->name_kh = addslashes($request->name_kh);
                $amenity->icon    = $request->icon;
                $amenity->status  = $request->status ?? 1;
                $amenity->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Amenity updated successfully',
                    'redirect'=> route('property.amenities.index'),
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

            $amenity = Amenity::findOrFail($request->id);
            $amenity->delete();

            return response()->json([
                'status'   => 'success',
                'message'  => 'Amenity deleted successfully',
                'redirect' => route('property.amenities.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
