<x-app-layout>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 mb-0">Inquiry Report</h1>
            <span class="text-muted small">{{ $inquiries->total() }} total records</span>
        </div>

        {{-- Filters --}}
        <form method="GET" class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name, email, phone...">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach(['pending','active','closed'] as $s)
                                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="agent_id" class="form-select form-select-sm">
                            <option value="">All Agents</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" @selected(request('agent_id') == $agent->id)>
                                    {{ $agent->first_name }} {{ $agent->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="property_id" class="form-select form-select-sm">
                            <option value="">All Properties</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" @selected(request('property_id') == $property->id)>
                                    {{ Str::limit($property->title_en, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-2">
                        <select name="registered" class="form-select form-select-sm">
                            <option value="">All Users</option>
                            <option value="1" @selected(request('registered') === '1')>Registered</option>
                            <option value="0" @selected(request('registered') === '0')>Guest</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="role" value="{{ request('role') }}" class="form-control form-control-sm" placeholder="Role e.g. buyer">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm" title="From date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm" title="To date">
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('reports.inquiries.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
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
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Name
                                </a>
                            </th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Property</th>
                            <th>Agent</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Status
                                </a>
                            </th>
                            <th class="text-center">Registered</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Created
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inquiry)
                        <tr>
                            <td class="fw-semibold">{{ $inquiry->name }}</td>
                            <td class="small">
                                {{ $inquiry->email }}<br>
                                <span class="text-muted">{{ $inquiry->phone ?? '-' }}</span>
                            </td>
                            <td>{{ $inquiry->role ?? '-' }}</td>
                            <td>{{ $inquiry->property->title_en ?? '-' }}</td>
                            <td>{{ $inquiry->agent ? $inquiry->agent->first_name . ' ' . $inquiry->agent->last_name : '-' }}</td>
                            <td>
                                <span class="badge
                                    @switch($inquiry->status)
                                        @case('pending') bg-warning text-dark @break
                                        @case('active') bg-primary @break
                                        @case('closed') bg-secondary @break
                                        @default bg-dark
                                    @endswitch
                                ">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($inquiry->user_id)
                                    <i class="fa fa-check-circle text-success" title="{{ $inquiry->user->name ?? '' }}"></i>
                                    {{ $inquiry->user->name ?? '' }}
                                @else
                                    <i class="fa fa-times-circle text-muted"></i>
                                @endif
                            </td>
                            <td class="text-nowrap small text-muted">{{ $inquiry->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No inquiries found matching your filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $inquiries->links() }}
        </div>

    </div>
</x-app-layout>