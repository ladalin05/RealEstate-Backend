<x-app-layout>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 mb-0">Property Report</h1>
            <span class="text-muted small">{{ $properties->total() }} total records</span>
        </div>

        {{-- Filters --}}
        <form method="GET" class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search code or title...">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach(['active','inactive','draft','sold','rented'] as $s)
                                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="purpose" class="form-select form-select-sm">
                            <option value="">All Purpose</option>
                            @foreach(['sale','rent','sale_rent'] as $p)
                                <option value="{{ $p }}" @selected(request('purpose') == $p)>{{ ucfirst(str_replace('_',' ', $p)) }}</option>
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
                    <div class="col-md-2">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->{'name_'.app()->getLocale()} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-2">
                        <select name="area_id" class="form-select form-select-sm">
                            <option value="">All Areas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" @selected(request('area_id') == $area->id)>{{ $area->{'name_'.app()->getLocale()} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="verified" class="form-select form-select-sm">
                            <option value="">Verified?</option>
                            <option value="1" @selected(request('verified') === '1')>Verified</option>
                            <option value="0" @selected(request('verified') === '0')>Not Verified</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="featured" class="form-select form-select-sm">
                            <option value="">Featured?</option>
                            <option value="1" @selected(request('featured') === '1')>Featured</option>
                            <option value="0" @selected(request('featured') === '0')>Not Featured</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm" title="From date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm" title="To date">
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('reports.properties') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
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
                            <th>Image</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'property_code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Code
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'title_en', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Title
                                </a>
                            </th>
                            <th>Category</th>
                            <th>Purpose</th>
                            <th>Area</th>
                            <th>Agent</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Price
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Status
                                </a>
                            </th>
                            <th class="text-center">Verified</th>
                            <th class="text-center">Featured</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-decoration-none">
                                    Created
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties as $property)
                        <tr>
                            <td>
                                <img src="{{ $property->main_image }}" class="rounded" style="width:48px;height:48px;object-fit:cover;" onerror="this.onerror=null;this.src='http://localhost:9000/images/properties/no-image-found.jpg';">
                            </td>
                            <td class="fw-semibold">{{ $property->property_code }}</td>
                            <td>
                                {{-- <a href="{{ route('admin.properties.show', $property->id) }}" class="text-decoration-none">
                                    {{ Str::limit($property->title_en, 40) }}
                                </a> --}}
                            </td>
                            <td>{{ $property->category->name ?? '-' }}</td>
                            <td class="text-capitalize">{{ str_replace('_', ' / ', $property->purpose) }}</td>
                            <td>{{ $property->area->name ?? '-' }}</td>
                            <td>{{ $property->agent ? $property->agent->first_name . ' ' . $property->agent->last_name : '-' }}</td>
                            <td>
                                @if($property->price)
                                    {{ $property->currency }} {{ number_format($property->price, 0) }}
                                @else
                                    {{ $property->price_label ?? '-' }}
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @switch($property->status)
                                        @case('active') bg-success @break
                                        @case('sold') bg-primary @break
                                        @case('rented') bg-info @break
                                        @case('draft') bg-secondary @break
                                        @default bg-dark
                                    @endswitch
                                ">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($property->verified)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-x-circle text-muted"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($property->featured)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            </td>
                            <td class="text-nowrap small text-muted">{{ $property->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">No properties found matching your filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $properties->links() }}
        </div>

    </div>
</x-app-layout>