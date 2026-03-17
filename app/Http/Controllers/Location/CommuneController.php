<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\Commune;
use App\Models\Location\District;
use App\Http\Controllers\Controller;
use App\DataTables\Location\CommuneDataTable;

class CommuneController extends Controller
{

    public function index(CommuneDataTable $dataTable)
    {
        return $dataTable->render('location.communes.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {
                
                $title = __('global.add_new');
                $form = new Commune();
                $action = route('location.communes.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.communes.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'district_id' => 'required',
                    'name' => 'required'
                ]);

                Commune::create([
                    'district_id' => $request->district_id,
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Commune created successfully',
                    'redirect' => route('location.communes.index'),
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
                $form = Commune::findOrFail($request->id);
                $action = route('location.communes.edit', ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('location.communes.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $commune = Commune::findOrFail($request->id);

                $commune->update([
                    'district_id' => $request->district_id,
                    'name' => $request->name,
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Commune updated successfully',
                    'redirect' => route('location.communes.index'),
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

            $commune = Commune::findOrFail($id);
            $commune->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Commune deleted successfully',
                'redirect' => route('location.communes.index'),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

}