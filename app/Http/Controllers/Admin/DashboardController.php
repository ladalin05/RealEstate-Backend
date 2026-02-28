<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserManagement\User;
use App\Models\Type;
use App\Models\Property;
use App\Models\Reports;
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
    public function index()
    {
        $user = Auth::user();

        // Counts
        $typeCount     = Type::count();
        $propertyCount = Property::count();
        $userCount     = User::count();
        $reportCount   = Reports::count();

        // Revenue
        $startDay   = date('Y-m-d 00:00:00');
        $endDay     = date('Y-m-d 23:59:59');
        $dailyAmount = Transactions::whereBetween('date', [strtotime($startDay), strtotime($endDay)])
            ->sum('payment_amount');

        $startWeek  = date('D') !== 'Mon' ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
        $endWeek    = date('D') !== 'Sat' ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
        $weeklyAmount = Transactions::whereBetween('date', [strtotime($startWeek), strtotime($endWeek)])
            ->sum('payment_amount');

        $startMonth = date('Y-m-01');
        $endMonth   = date('Y-m-t');
        $monthlyAmount = Transactions::whereBetween('date', [strtotime($startMonth), strtotime($endMonth)])->sum('payment_amount');

        $currentYear   = date('Y');
        $startYear     = strtotime("January 1st, {$currentYear}");
        $endYear       = strtotime("December 31st, {$currentYear}");
        $yearlyAmount  = Transactions::whereBetween('date', [$startYear, $endYear])->sum('payment_amount');

        // Latest Properties
        $latestProperty = Property::where('status', 1)->orderBy('id', 'DESC')->take(10)->get();

        // Trending Properties (last 30 days)
        $trendingStart = strtotime('-30 days');
        $trendingEnd   = strtotime('today');

        $trendingNow = DB::table('post_views')
            ->join('property', 'property.id', '=', 'post_views.post_id')
            ->where('property.status', 1)
            ->whereBetween('post_views.date', [$trendingStart, $trendingEnd])
            ->select('post_views.post_id', 'post_views.post_type', 'property.id')
            ->selectRaw('SUM(post_views) as total_views')
            ->groupBy('property.id', 'post_views.post_id', 'post_views.post_type')
            ->orderBy('total_views', 'DESC')
            ->limit(10)
            ->get();

        // Latest Transactions
        $latestTransactions = Transactions::orderBy('id', 'DESC')->take(10)->get();

        // Latest Reports
        $reportLists = Reports::orderBy('id', 'DESC')->take(10)->get();

        $pageTitle = trans('words.dashboard_text') ?: 'Dashboard';

        return view(
            'dashboard',
            compact(
                'pageTitle',
                'typeCount',
                'propertyCount',
                'userCount',
                'reportCount',
                'latestProperty',
                'trendingNow',
                'reportLists',
                'dailyAmount',
                'weeklyAmount',
                'monthlyAmount',
                'yearlyAmount',
                'latestTransactions'
            )
        );
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
