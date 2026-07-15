<x-app-layout>
    <div class="dashboard-wrapper bg-light py-4">
        <div class="container-fluid">

            <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                <div>
                    <h2 class="h3 font-weight-bold text-dark mb-0">Dashboard Overview</h2>
                    <p class="text-muted small">Welcome back, Admin. Here's what's happening today.</p>
                </div>
                <button class="btn btn-white shadow-sm border rounded-pill px-3">
                    <i class="fa fa-calendar-alt mr-2 text-primary"></i> {{ date('M d, Y') }}
                </button>
            </div>

            {{-- ==================== STAT CARDS ==================== --}}
            <div class="row mb-4">
                @php
                    $stats = [
                        ['route' => 'property.categories.index', 'count' => $typeCount, 'label' => 'global.category', 'icon' => 'fa-sitemap', 'color' => 'primary'],
                        ['route' => 'property.properties.index', 'count' => $propertyCount, 'label' => 'global.property', 'icon' => 'fa-building', 'color' => 'success'],
                        ['url' => 'admin/users', 'count' => $userCount, 'label' => 'global.users', 'icon' => 'fa-users', 'color' => 'info'],
                        ['url' => 'admin/reports', 'count' => $reportCount, 'label' => 'global.reports', 'icon' => 'fa-exclamation-triangle', 'color' => 'danger'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="col-xl-3 col-md-6 mb-4">
                    <a href="{{ isset($stat['route']) ? route($stat['route']) : url($stat['url']) }}" class="text-decoration-none">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-uppercase text-muted small font-weight-bold mb-2">{{ __($stat['label']) }}</h6>
                                        <h2 class="mb-0 font-weight-bold" data-plugin="counterup">{{ $stat['count'] }}</h2>
                                    </div>
                                    <div class="icon-shape bg-{{ $stat['color'] }}-light text-{{ $stat['color'] }} rounded-circle">
                                        <i class="fa {{ $stat['icon'] }} fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- ==================== FINANCIAL SUMMARY ==================== --}}
            <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                <h5 class="font-weight-bold mb-0"><i class="fa fa-dollar-sign text-success mr-1"></i> Financial Performance Summary</h5>
                <span class="text-muted extra-small">Values calculated in real-time</span>
            </div>

            <div class="row mb-5">
                @php
                    $currencySymbol = html_entity_decode(getCurrencySymbols(getcong('currency_code')) ?? '$');

                    $revenues = [
                        ['amount' => $revenue['daily'],   'label' => 'daily_revenue',   'note' => 'vs yesterday',        'gradient' => 'grad-primary'],
                        ['amount' => $revenue['weekly'],  'label' => 'weekly_revenue',  'note' => 'since week start',    'gradient' => 'grad-info'],
                        ['amount' => $revenue['monthly'], 'label' => 'monthly_revenue', 'note' => 'of monthly goal',     'gradient' => 'grad-warning'],
                        ['amount' => $revenue['yearly'],  'label' => 'yearly_revenue',  'note' => 'projected for year',  'gradient' => 'grad-success'],
                    ];
                @endphp

                @foreach($revenues as $rev)
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm revenue-card {{ $rev['gradient'] }} text-white">
                        <div class="card-body p-4">
                            <h3 class="mb-1 font-weight-bold">{{ $currencySymbol }}{{ number_format($rev['amount'], 0) }}</h3>
                            <p class="mb-0 opacity-8 small">{{ __('global.' . $rev['label']) }}</p>
                            <p class="mb-0 opacity-8 extra-small mt-1">{{ $rev['note'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ==================== LATEST PROPERTIES + RECENT REPORTS ==================== --}}
            <div class="row">

                {{-- Latest Properties --}}
                <div class="col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">🏡 Latest Properties</h6>
                            <a href="{{ route('property.properties.index') }}" class="small text-primary font-weight-600 text-decoration-none">Manage All</a>
                        </div>
                        <div class="card-body p-0 scrollable-list">
                            @forelse($latestProperty as $lp)
                            <div class="list-item d-flex align-items-center p-3 border-bottom">
                                <img src="{{ $lp->main_image ?: 'http://localhost:9000/images/properties/no-image-found.jpg' }}"
                                    alt="{{ $lp->title }}"
                                    class="rounded mr-3 property-thumb"
                                    onerror="this.onerror=null;this.src='http://localhost:9000/images/properties/no-image-found.jpg';">
                                <div class="flex-grow-1 min-width-0">
                                    <p class="mb-0 text-dark font-weight-600 small text-truncate">{{ Str::limit($lp->title_en, 30) }}</p>
                                    <p class="mb-0 text-muted extra-small text-truncate">{{ $lp->location ?? '—' }}</p>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="text-primary font-weight-700 extra-small mr-2">
                                            {{ $lp->price ? ($lp->currency . ' ' . number_format($lp->price)) : ($lp->price_label ?: 'Ask agent') }}
                                        </span>
                                        @if($lp->category)
                                            <span class="text-muted extra-small">{{ $lp->category->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $statusColors = [
                                        'active'   => 'success',
                                        'inactive' => 'secondary',
                                        'draft'    => 'secondary',
                                        'sold'     => 'danger',
                                        'rented'   => 'info',
                                    ];
                                @endphp
                                <span class="badge badge-soft-{{ $statusColors[$lp->status] ?? 'secondary' }} ml-2 text-capitalize">
                                    {{ $lp->status }}
                                </span>
                            </div>
                            @empty
                            <div class="p-4 text-center text-muted small">No properties listed yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Recent System Reports --}}
                <div class="col-xl-8 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">🚨 Recent System Reports</h6>
                            <a href="{{ url('admin/reports') }}" class="btn btn-sm btn-light border text-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Reporter</th>
                                        <th>Message</th>
                                        <th>Severity</th>
                                        <th>Date</th>
                                        <th>Status</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportLists as $report)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $report->user->avatar ?? asset('admin_assets/images/user-default.png') }}" class="rounded-circle mr-2" width="30" height="30">
                                                <div>
                                                    <p class="mb-0 font-weight-600 small">{{ $report->user->name ?? 'Unknown User' }}</p>
                                                    <span class="text-primary extra-small">{{ $report->user->email ?? '' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small" style="max-width: 260px;" title="{{ $report->message }}">
                                            {{ Str::limit($report->message, 50) }}
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-{{ $report->severity === 'High' ? 'danger' : ($report->severity === 'Medium' ? 'warning' : 'info') }} text-uppercase extra-small">
                                                {{ $report->severity }}
                                            </span>
                                        </td>
                                        <td><span class="badge badge-light text-dark border">{{ $report->date?->format('M d') }}</span></td>
                                        <td>
                                            <span class="badge badge-soft-{{ $report->status === 'Pending' ? 'warning' : 'success' }}">
                                                {{ $report->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted small py-4">No reports to show.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --info-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .dashboard-wrapper { font-family: 'Inter', -apple-system, sans-serif; min-height: 100vh; }

        /* Card Styles */
        .stat-card { transition: transform 0.2s ease; border-radius: 12px; }
        .stat-card:hover { transform: translateY(-5px); }

        .icon-shape {
            width: 48px; height: 48px;
            display: flex; align-items: center; justify-content: center;
        }

        /* Color Variants */
        .bg-primary-light { background: rgba(102, 126, 234, 0.1); }
        .bg-success-light { background: rgba(56, 249, 215, 0.1); }
        .bg-info-light { background: rgba(0, 158, 253, 0.1); }
        .bg-danger-light { background: rgba(255, 0, 0, 0.05); }

        /* Revenue Gradients */
        .grad-primary { background: var(--primary-gradient); }
        .grad-info { background: var(--info-gradient); }
        .grad-warning { background: var(--warning-gradient); }
        .grad-success { background: var(--success-gradient); }

        .revenue-card { border-radius: 15px; }
        .opacity-8 { opacity: 0.8; }

        /* Table and Lists */
        .scrollable-list { max-height: 420px; overflow-y: auto; }
        .extra-small { font-size: 0.75rem; }
        .font-weight-600 { font-weight: 600; }
        .font-weight-700 { font-weight: 700; }
        .min-width-0 { min-width: 0; }

        .property-thumb {
            width: 48px; height: 48px;
            object-fit: cover;
            flex-shrink: 0;
        }

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
    </style>
</x-app-layout>