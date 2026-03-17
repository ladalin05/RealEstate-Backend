<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Http\Controllers\Controller;
use App\DataTables\Location\CityDataTable;

class CityController extends Controller
{

    public function index(CityDataTable $dataTable)
    {
        return $dataTable->render('location.cities.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {
                $title = __('global.add_new');
                $form = new City();
                $action = route('location.cities.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.cities.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'country_id' => 'required',
                    'name' => 'required'
                ]);

                City::create([
                    'country_id' => $request->country_id,
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'City created successfully',
                    'redirect' => route('location.cities.index'),
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
                $form = City::findOrFail($request->id);
                $action = route('location.cities.edit', ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.cities.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $city = City::findOrFail($request->id);

                $city->update([
                    'country_id' => $request->country_id,
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'City updated successfully',
                    'redirect' => route('location.cities.index'),
                ]);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function destroy(Request $request)
    {
        try {

            $city = City::findOrFail($request->id);
            $city->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'City deleted successfully',
                'redirect' => route('location.cities.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

}