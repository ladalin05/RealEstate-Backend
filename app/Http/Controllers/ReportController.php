<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'All'); // All | Pending | Resolved
        $search       = $request->query('search');

        $query = Report::with('user')->latest('date');

        if (in_array($statusFilter, ['Pending', 'Resolved'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->paginate(10)->withQueryString();

        // Counts for the filter tabs (independent of the current filter/search)
        $counts = [
            'All'      => Report::count(),
            'Pending'  => Report::pending()->count(),
            'Resolved' => Report::where('status', 'Resolved')->count(),
        ];

        return view('reports.index', [
            'reports'      => $reports,
            'counts'       => $counts,
            'statusFilter' => $statusFilter,
            'search'       => $search,
        ]);
    }

    public function toggleStatus(Report $report): RedirectResponse
    {
        $report->update([
            'status' => $report->status === 'Pending' ? 'Resolved' : 'Pending',
        ]);

        return back()->with('success', 'Report status updated.');
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return back()->with('success', 'Report deleted.');
    }
}