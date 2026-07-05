<x-app-layout>
    <div class="dashboard-wrapper bg-light py-4">
        <div class="container-fluid">

            <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                <div>
                    <h2 class="h3 font-weight-bold text-dark mb-0">System Logs &amp; Reports</h2>
                    <p class="text-muted small">Review external data-integrations, API telemetry, and platform warnings.</p>
                </div>
            </div>

            {{-- ==================== FILTER TOOLBAR ==================== --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-3">

                    <div class="btn-group" role="group">
                        <a href="{{ route('reports.index', array_filter(['search' => $search])) }}"
                           class="btn btn-sm {{ $statusFilter === 'All' ? 'btn-dark' : 'btn-light border' }}">
                            All Reports ({{ $counts['All'] }})
                        </a>
                        <a href="{{ route('reports.index', array_filter(['status' => 'Pending', 'search' => $search])) }}"
                           class="btn btn-sm {{ $statusFilter === 'Pending' ? 'btn-warning text-white' : 'btn-light border' }}">
                            Pending ({{ $counts['Pending'] }})
                        </a>
                        <a href="{{ route('reports.index', array_filter(['status' => 'Resolved', 'search' => $search])) }}"
                           class="btn btn-sm {{ $statusFilter === 'Resolved' ? 'btn-success text-white' : 'btn-light border' }}">
                            Resolved ({{ $counts['Resolved'] }})
                        </a>
                    </div>

                    <form action="{{ route('reports.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        @if($statusFilter !== 'All')
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                        @endif
                        <div class="input-group input-group-sm" style="max-width: 260px;">
                            <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search reporter or message...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        @if($search)
                            <a href="{{ route('reports.index', array_filter(['status' => $statusFilter !== 'All' ? $statusFilter : null])) }}" class="small text-muted">Clear</a>
                        @endif
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success py-2 small">{{ session('success') }}</div>
            @endif

            {{-- ==================== REPORTS TABLE ==================== --}}
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>User Details</th>
                                <th>Report &amp; Message Details</th>
                                <th>Severity</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $report->user->avatar ?? asset('admin_assets/images/user-default.png') }}" class="rounded-circle mr-2" width="34" height="34">
                                        <div>
                                            <p class="mb-0 font-weight-600 small">{{ $report->user->name ?? 'Unknown User' }}</p>
                                            <span class="text-primary extra-small">{{ $report->user->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="small" style="max-width: 320px;">
                                    <p class="mb-0 font-weight-600 text-dark">{{ Str::limit($report->message, 60) }}</p>
                                    <span class="text-primary extra-small font-weight-600">Ref ID: {{ $report->id }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-{{ $report->severity === 'High' ? 'danger' : ($report->severity === 'Medium' ? 'warning' : 'info') }} text-uppercase extra-small">
                                        {{ $report->severity }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $report->date?->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-{{ $report->status === 'Pending' ? 'warning' : 'success' }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <form action="{{ route('reports.toggle', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-light border text-primary font-weight-600">Toggle</button>
                                    </form>
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this system log report completely?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon-only text-danger" title="Delete Report">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted small py-5">
                                    No reports match your current filter{{ $search ? ' and search' : '' }}.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reports->hasPages())
                <div class="card-footer bg-white border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">

                    <span class="text-muted extra-small">
                        Showing {{ $reports->firstItem() }}–{{ $reports->lastItem() }} of {{ $reports->total() }}
                    </span>

                    <nav class="d-flex align-items-center gap-1">
                        {{-- Prev --}}
                        @if($reports->onFirstPage())
                            <span class="page-pill page-pill-disabled"><i class="fa fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $reports->previousPageUrl() }}" class="page-pill">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page numbers (compact: current ± 1, plus first/last) --}}
                        @php
                            $current = $reports->currentPage();
                            $last    = $reports->lastPage();
                            $window  = 1;
                        @endphp

                        @for($page = 1; $page <= $last; $page++)
                            @if($page === 1 || $page === $last || ($page >= $current - $window && $page <= $current + $window))
                                <a href="{{ $reports->url($page) }}" class="page-pill {{ $page === $current ? 'page-pill-active' : '' }}">
                                    {{ $page }}
                                </a>
                            @elseif($page === $current - $window - 1 || $page === $current + $window + 1)
                                <span class="page-pill page-pill-disabled">…</span>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if($reports->hasMorePages())
                            <a href="{{ $reports->nextPageUrl() }}" class="page-pill">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="page-pill page-pill-disabled"><i class="fa fa-chevron-right"></i></span>
                        @endif
                    </nav>

                </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .dashboard-wrapper { font-family: 'Inter', -apple-system, sans-serif; min-height: 100vh; }
        .extra-small { font-size: 0.75rem; }
        .font-weight-600 { font-weight: 600; }

        .table thead th {
            background-color: #f8f9fe;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 1px;
            border-bottom: 1px solid #e9ecef;
        }

        .badge-soft-primary   { background: #e7eaf3; color: #377dff; }
        .badge-soft-success   { background: #e5f9ed; color: #12b76a; }
        .badge-soft-warning   { background: #fff3e0; color: #f59e0b; }
        .badge-soft-danger    { background: #fdeceb; color: #e5484d; }
        .badge-soft-info      { background: #e4f5ff; color: #0ea5e9; }
        .badge-soft-secondary { background: #f1f2f4; color: #6b7280; }

        /* Compact pagination pills */
        .page-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            padding: 0 6px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            background: #f8f9fe;
            border: 1px solid #eef0f5;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .page-pill:hover {
            background: #eef1fb;
            color: #377dff;
            text-decoration: none;
        }
        .page-pill-active {
            background: #377dff;
            border-color: #377dff;
            color: #fff;
        }
        .page-pill-active:hover {
            background: #2f6de0;
            color: #fff;
        }
        .page-pill-disabled {
            opacity: 0.4;
            cursor: default;
            pointer-events: none;
        }
    </style>
</x-app-layout>