<?php

namespace App\Http\Controllers\Property;

use Exception;
use Illuminate\Support\Str;
use App\Models\Property\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\DataTables\Property\FeatureDataTable;
use App\Models\Property\Feature;

class FeatureController extends Controller
{
    public function index(FeatureDataTable $dataTable)
    {
        return $dataTable->render('property.features.index');
    }

    // Create new feature
    public function create(Request $request)
    {
        try {
            // Show form (GET)
            if ($request->isMethod('get')) {
                $title  = __('global.add_new');
                $form   = new Feature();
                $action = route('property.features.add');

                return response()->json([
                    'title'  => $title,
                    'status' => 'success',
                    'html'   => view('property.features.form', compact('title', 'form', 'action'))->render(),
                    'modal'  => 'action-modal',
                ]);
            }

            // Store data (POST)
            if ($request->isMethod('post')) {
                $request->validate([
                    'name_en' => 'required|string|max:255',
                    'name_kh' => 'nullable|string|max:255',
                ]);

                Feature::create([
                    'name_en' => addslashes($request->name_en),
                    'name_kh' => addslashes($request->name_kh),
                    'icon'    => $request->icon,
                    'status'  => $request->status ?? 1,
                ]);

                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Feature created successfully',
                    'redirect' => route('property.features.index'),
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => __('messages.405'),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Update existing feature
    public function update(Request $request)
    {
        try {
            // Show edit form (GET)
            if ($request->isMethod('get')) {
                $title  = __('global.edit');
                $form   = Feature::findOrFail($request->id);
                $action = route('property.features.edit', ['id' => $request->id]);

                return response()->json([
                    'title'  => $title,
                    'status' => 'success',
                    'html'   => view('property.features.form', compact('title', 'form', 'action'))->render(),
                    'modal'  => 'action-modal',
                ]);
            }

            // Update data (POST)
            if ($request->isMethod('post')) {
                $request->validate([
                    'name_en' => 'required|string|max:255',
                    'name_kh' => 'nullable|string|max:255',
                    'icon'    => 'nullable|string|max:255',
                    'status'  => 'required|in:0,1',
                ]);

                $feature = Feature::findOrFail($request->id);
                $feature->name_en = addslashes($request->name_en);
                $feature->name_kh = addslashes($request->name_kh);
                $feature->icon    = $request->icon;
                $feature->status  = $request->status ?? 1;
                $feature->save();

                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Feature updated successfully',
                    'redirect' => route('property.features.index'),
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => __('messages.405'),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete feature
    public function delete(Request $request)
    {
        try {
            $feature = Feature::findOrFail($request->id);
            $feature->delete();

            return response()->json([
                'status'   => 'success',
                'message'  => 'Feature deleted successfully',
                'redirect' => route('property.features.index'),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
