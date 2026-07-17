<x-app-layout>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 mb-0">Agent Performance Report</h1>
            <span class="text-muted small">{{ $agents->total() }} total agents</span>
        </div>

        {{-- Filters --}}
        <form method="GET" class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name or email...">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach(['active','inactive','suspended','pending'] as $s)
                                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="min_rating" class="form-select form-select-sm">
                            <option value="">Any Rating</option>
                            @foreach([4.5, 4, 3.5, 3] as $r)
                                <option value="{{ $r }}" @selected(request('min_rating') == $r)>{{ $r }}+ stars</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm" title="From date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm" title="To date">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md-2">
                        <a href="{{ route('reports.agent-performance.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Report Table --}}
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Agent</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'properties_count', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Properties Listed
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'inquiries_count', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Inquiries
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'tours_count', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Tours
                                </a>
                            </th>
                            <th>Tours Completed</th>
                            <th>Sales</th>
                            <th>Rentals</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'rating', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Rating
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Status
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agents as $agent)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $agent->profile_image }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" onerror="this.onerror=null;this.src='http://localhost:9000/images/profiles/no-image-found.jpg';">
                                    <div>
                                        <div class="fw-semibold">{{ $agent->first_name }} {{ $agent->last_name }}</div>
                                        <div class="small text-muted">{{ $agent->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">{{ $agent->properties_count }}</td>
                            <td>{{ $agent->inquiries_count }}</td>
                            <td>{{ $agent->tours_count }}</td>
                            <td>{{ $agent->tours_completed_count }}</td>
                            <td>{{ $agent->total_sales }}</td>
                            <td>{{ $agent->total_rentals }}</td>
                            <td>
                                <span class="text-warning">★</span> {{ number_format($agent->rating, 2) }}
                                <span class="text-muted small">({{ $agent->review_count }})</span>
                            </td>
                            <td>
                                <span class="badge
                                    @switch($agent->status)
                                        @case('active') bg-success @break
                                        @case('pending') bg-warning text-dark @break
                                        @case('suspended') bg-danger @break
                                        @case('inactive') bg-secondary @break
                                        @default bg-dark
                                    @endswitch
                                ">
                                    {{ ucfirst($agent->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No agents found matching your filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $agents->links() }}
        </div>

    </div>
</x-app-layout>