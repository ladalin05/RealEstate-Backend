<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\District;
use App\Models\Location\City;
use App\Http\Controllers\Controller;
use App\DataTables\Location\DistrictDataTable;

class DistrictController extends Controller
{

    public function index(DistrictDataTable $dataTable)
    {
        return $dataTable->render('location.districts.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new District();
                $action = route('location.districts.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.districts.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'city_id' => 'required',
                    'name' => 'required'
                ]);

                District::create([
                    'city_id' => $request->city_id,
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'District created successfully',
                    'redirect' => route('location.districts.index'),
                ]);
            }

        } catch (\Exception $e) {

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
                $form = District::findOrFail($request->id);
                $action = route('location.districts.edit', ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.districts.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $district = District::findOrFail($request->id);

                $district->update([
                    'city_id' => $request->city_id,
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'District updated successfully',
                    'redirect' => route('location.districts.index'),
                ]);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function destroy($id)
    {
        try {

            $district = District::findOrFail($id);
            $district->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'District deleted successfully',
                'redirect' => route('location.districts.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

}