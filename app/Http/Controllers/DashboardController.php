<?php

namespace App\Http\Controllers;

use App\Models\UserManagement\User;
use App\Models\Property\PropertyCategory;
use App\Models\Property\Property;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Property\PropertyViews;
use App\Models\Admin\Transactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    
    public function index(Request $request)
    {
        $typeCount     = PropertyCategory::count();
        $propertyCount = Property::count();
        $userCount     = User::where('active', 1)->count();
        $reportCount   = Report::pending()->count();
 
        $revenue = [
            'daily'   => $this->revenueFor('daily'),
            'weekly'  => $this->revenueFor('weekly'),
            'monthly' => $this->revenueFor('monthly'),
            'yearly'  => $this->revenueFor('yearly'),
        ];
 
        // Left column: latest 5 properties
        $latestProperty = Property::with('category')
            ->latest('created_at')
            ->take(5)
            ->get();
 
        // Right column: recent reports table
        $reportLists = Report::with('user')
            ->latest('date')
            ->take(10)
            ->get();
 
        return view('dashboard', compact(
            'typeCount',
            'propertyCount',
            'userCount',
            'reportCount',
            'revenue',
            'latestProperty',
            'reportLists'
        ));
    }
 
    protected function revenueFor(string $period): float
    {
        $query = Property::where('status', 'sold');
 
        return (float) match ($period) {
            'daily'   => $query->whereDate('updated_at', today())->sum('price'),
            'weekly'  => $query->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('price'),
            'monthly' => $query->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->sum('price'),
            'yearly'  => $query->whereYear('updated_at', now()->year)->sum('price'),
            default   => 0,
        };
    }


    /**
     * Clear cache and logs.
     */
    public function cache()
    {
        Artisan::call('optimize:clear');
        removeFile(storage_path('logs/laravel.log'));

        session()->flash('flash_message', trans('words.cache_cleared'));
        return redirect()->back();
    }
}
