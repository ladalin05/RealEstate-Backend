<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
 
    public function property_report()
    { 
        $list = Reports::orderBy('id','DESC')->get();
        $page_title=trans('global.reports');
        $report_list = view('reports.reports.list',compact('list'))->render();

        return view('reports.reports.index',compact('page_title','list','report_list'));
    }

    public function report_filter(Request $request)
    {
        $list = Reports::orderBy('id','DESC')
                        ->when($request->search_text, function ($query, $search_text) {
                            return $query->where('message', 'like', '%' . $search_text . '%');
                        })
                        ->get();
        $report_list = view('report.reports.list',compact('list'))->render();
        return response()->json($report_list);
    }

    public function delete($post_id)
    {
        $report = Reports::find($post_id);
        if($report)
        {
            $report->delete();
            Session::flash('success',__('messages.report_deleted_successfully')); 
            return redirect()->route('pages.reports.index');
        }
    }
 
}
