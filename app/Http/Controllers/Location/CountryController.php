<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Http\Controllers\Controller;
use App\DataTables\Location\CountryDataTable;

class CountryController extends Controller
{

    public function index(CountryDataTable $dataTable)
    {
        return $dataTable->render('location.countries.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {
                $title = __('global.add_new');
                $form = new Country();
                $action = route('location.countries.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.countries.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'name' => 'required'
                ]);

                Country::create([
                    'name' => $request->name,
                    'status' => $request->status,
                    'status' => 1
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Country created successfully',
                    'redirect' => route('location.countries.index'),
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);

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
                $form = Country::findOrFail($request->id);
                $action = route('location.countries.edit', ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.countries.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'name' => 'required'
                ]);

                $country = Country::findOrFail($request->id);

                $country->update([
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Country updated successfully',
                    'redirect' => route('location.countries.index'),
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);

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

            $country = Country::findOrFail($request->id);
            $country->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Country deleted successfully',
                'redirect' => route('location.countries.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

}