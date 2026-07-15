<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Property\Property;
use App\Models\UserManagement\Agent;
use App\Models\Property\PropertyCategory;
use App\Models\Interaction\RequestInfo;
use App\Models\Property\Area;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function propertyReport(Request $request)
    {
        $query = Property::query()
            ->with(['agent', 'category', 'area']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }
        if ($request->filled('verified')) {
            $query->where('verified', $request->verified);
        }
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('property_code', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['created_at', 'price', 'title_en', 'status', 'property_code'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $properties = $query->paginate(20)->withQueryString();

        // For filter dropdowns
        $agents = Agent::select('id', 'first_name', 'last_name')->get();
        $categories = PropertyCategory::select('id', 'name_en', 'name_km as name_kh')->get();
        $areas = Area::select('id', 'name_en', 'name_km as name_kh')->get();

        return view('reports.properties', compact('properties', 'agents', 'categories', 'areas'));
    }

    public function inquiryReport(Request $request)
    {
        $query = RequestInfo::query()
            ->with(['property', 'agent', 'user']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }
        if ($request->filled('registered')) {
            if ($request->registered == '1') {
                $query->whereNotNull('user_id');
            } elseif ($request->registered == '0') {
                $query->whereNull('user_id');
            }
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['created_at', 'name', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $inquiries = $query->paginate(20)->withQueryString();

        // For filter dropdowns
        $properties = Property::select('id', 'title_en')->get();
        $agents = Agent::select('id', 'first_name', 'last_name')->get();

        return view('reports.inquiries', compact('inquiries', 'properties', 'agents'));
    }
}