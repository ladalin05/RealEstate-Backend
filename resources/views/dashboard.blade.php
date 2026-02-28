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

            <div class="row mb-4">
                @php
                    $stats = [
                        ['route' => 'type.index', 'count' => $typeCount, 'label' => 'global.type', 'icon' => 'fa-sitemap', 'color' => 'primary'],
                        ['route' => 'property.index', 'count' => $propertyCount, 'label' => 'global.property', 'icon' => 'fa-building', 'color' => 'success'],
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

            <h5 class="font-weight-bold mb-3 px-2">Financial Summary</h5>
            <div class="row mb-5">
                @php
                    $revenues = [
                        ['amount' => $dailyAmount, 'label' => 'daily_revenue', 'gradient' => 'grad-primary'],
                        ['amount' => $weeklyAmount, 'label' => 'weekly_revenue', 'gradient' => 'grad-info'],
                        ['amount' => $monthlyAmount, 'label' => 'monthly_revenue', 'gradient' => 'grad-warning'],
                        ['amount' => $yearlyAmount, 'label' => 'yearly_revenue', 'gradient' => 'grad-success'],
                    ];
                    $currencySymbol = html_entity_decode(getCurrencySymbols(getcong('currency_code')));
                @endphp

                @foreach($revenues as $rev)
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm revenue-card {{ $rev['gradient'] }} text-white">
                        <div class="card-body p-4">
                            <h3 class="mb-1 font-weight-bold">{{ $currencySymbol }}{{ number_format($rev['amount'], 0) }}</h3>
                            <p class="mb-0 opacity-8 small">{{ __('global.' . $rev['label']) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 font-weight-bold">🏡 Latest Properties</h6>
                        </div>
                        <div class="card-body p-0 scrollable-list">
                            @foreach($latestProperty as $lp)
                            <div class="list-item d-flex align-items-center p-3 border-bottom">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-dark font-weight-600 small">{{ Str::limit($lp->title, 35) }}</p>
                                    <span class="text-muted extra-small"><i class="fa fa-eye mr-1"></i>{{ number_format_short(post_views_count($lp->id, "Property")) }} views</span>
                                </div>
                                <span class="badge badge-soft-primary">New</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">🚨 Recent System Reports</h6>
                            <a href="{{ url('admin/reports') }}" class="btn btn-sm btn-light border text-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportLists as $report)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('admin_assets/images/user-default.png') }}" class="rounded-circle mr-2" width="30">
                                                <span class="font-weight-600 small">{{ \App\User::getUserFullname($report->user_id) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted small">{{ Str::limit($report->message, 50) }}</td>
                                        <td><span class="badge badge-light border">{{ date('M d', $report->date) }}</span></td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-sm btn-icon-only text-light"><i class="fa fa-ellipsis-v"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
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
        .scrollable-list { max-height: 400px; overflow-y: auto; }
        .extra-small { font-size: 0.75rem; }
        .font-weight-600 { font-weight: 600; }
        
        .table thead th {
            background-color: #f8f9fe;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 1px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .badge-soft-primary { background: #e7eaf3; color: #377dff; }
    </style>
</x-app-layout>