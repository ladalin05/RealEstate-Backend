<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\DataTables\LocationDataTable;

class LocationController extends Controller
{
    
    public function index(LocationDataTable $dataTable)
    {
        return $dataTable->render('pages.location.index');
    }

    /**
     * Store or update a location.
     */
    public function store(Request $request)
    {
        try {
            if ($request->isMethod('get')) {
                $title = __('global.add_new');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'html' => view('pages.location.create')->render(),
                ]);
            }
            if (request()->isMethod('post')) {
                // Validation rules
                $request->validate([
                    'name' => 'required|string|max:255',
                    'status' => 'required|boolean',
                ]);

                // Create new location
                Location::create([
                    'name' => addslashes($request->name),
                    'status' => $request->status,
                ]);

                
                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.create_type_success'),
                    'redirect' => route('type.index'),
                ]);
            }

        } catch(\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing location.
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->isMethod('get')) {
                $title = __('global.edit');
                $location = Location::findOrFail($id);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'html' => view('pages.location.edit', compact('location'))->render(),
                ]);
            }
            if (request()->isMethod('post')) {
                // Validation rules
                $request->validate([
                    'id' => 'required|integer|exists:locations,id',
                    'name' => 'required|string|max:255',
                    'status' => 'required|boolean',
                ]);

                // Find and update
                $location = Location::findOrFail($request->id);

                $location->update([
                    'name' => addslashes($request->name),
                    'status' => $request->status,
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'title'  => __('global.edit'),
                    'html'   => view('pages.location.edit', compact('location'))->render(),
                ]);
            }

        } catch(\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
