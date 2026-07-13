<style>
    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
    }
    .info-label {
        font-size: 12px;
        color: #868e96;
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 14px;
        margin-bottom: 12px;
    }
    .property-main-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 8px;
    }
    .gallery-thumb {
        width: 100%;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
    }
    .price-tag {
        font-size: 22px;
        font-weight: 700;
        color: #0d6efd;
    }
    .price-tag small {
        font-size: 12px;
        color: #868e96;
        font-weight: 400;
    }
    .badge-pill {
        font-size: 11px;
        padding: 4px 10px;
    }
    .feature-chip {
        display: inline-block;
        background: #eef2ff;
        color: #3b4fd1;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        margin: 0 6px 6px 0;
    }
    .amenity-chip {
        display: inline-block;
        background: #e6f7ee;
        color: #1a8f5c;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        margin: 0 6px 6px 0;
    }
    .agent-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
    }
    .stat-box {
        text-align: center;
        border-right: 1px solid #e9ecef;
    }
    .stat-box:last-child {
        border-right: none;
    }
    .stat-box .stat-value {
        font-size: 16px;
        font-weight: 600;
    }
    .stat-box .stat-label {
        font-size: 11px;
        color: #868e96;
    }
</style>

@php
    $isKhmer = app()->getLocale() === 'km';

    $title      = $isKhmer && $propertyInfo['name_km'] ? $propertyInfo['name_km'] : $propertyInfo['name_en'];
    $desc       = $isKhmer && $propertyInfo['description_km'] ? $propertyInfo['description_km'] : $propertyInfo['description_en'];
    $address    = $isKhmer && $propertyInfo['address_km'] ? $propertyInfo['address_km'] : $propertyInfo['address_en'];
    $categoryNm = $isKhmer && $propertyInfo['category_km'] ? $propertyInfo['category_km'] : $propertyInfo['category_en'];

    $addr = $propertyInfo['addressDetail'];
    $areaNm     = $isKhmer && $addr['area_km'] ? $addr['area_km'] : $addr['area_en'];
    $provinceNm = $isKhmer && $addr['province_km'] ? $addr['province_km'] : $addr['province_en'];
    $districtNm = $isKhmer && $addr['district_km'] ? $addr['district_km'] : $addr['district_en'];
    $communeNm  = $isKhmer && $addr['commune_km'] ? $addr['commune_km'] : $addr['commune_en'];
    $countryNm  = $isKhmer && $addr['country_km'] ? $addr['country_km'] : $addr['country_en'];

    $agent = $propertyInfo['agent'];

    // amenities/features are parallel en/km string arrays, so zip them by index
    $featureLabels = collect($propertyInfo['features_en'])
        ->map(fn ($en, $i) => ($isKhmer && !empty($propertyInfo['features_km'][$i])) ? $propertyInfo['features_km'][$i] : $en);

    $amenityLabels = collect($propertyInfo['amenities_en'])
        ->map(fn ($en, $i) => ($isKhmer && !empty($propertyInfo['amenities_km'][$i])) ? $propertyInfo['amenities_km'][$i] : $en);

    $statusColors = [
        'available' => 'success',
        'pending'   => 'warning',
        'sold'      => 'dark',
        'rented'    => 'secondary',
    ];
@endphp

{{-- Header: image + title + price --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <img src="{{ $propertyInfo['image'] ?? asset('images/no-image.png') }}"
             alt="{{ $title }}"
             class="property-main-image">

        @if(count($propertyInfo['gallery'] ?? []) > 0)
            <div class="row g-2 mt-1">
                @foreach(array_slice($propertyInfo['gallery'], 0, 4) as $img)
                    <div class="col-3">
                        <img src="{{ $img }}" class="gallery-thumb" alt="gallery image">
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-start">
            <h5 class="mb-1 w-75">{{ $title }}</h5>
            @if($propertyInfo['featured'])
                <span class="badge bg-warning text-dark badge-pill">Featured</span>
            @endif
        </div>

        <p class="text-muted small mb-2">
            <i class="fa fa-map-marker-alt"></i>
            {{ $address ?? '-' }}
            @if($areaNm), {{ $areaNm }}@endif
            @if($communeNm), {{ $communeNm }}@endif
            @if($districtNm), {{ $districtNm }}@endif
            @if($provinceNm), {{ $provinceNm }}@endif
            @if($countryNm), {{ $countryNm }}@endif
        </p>

        <div class="mb-2">
            <span class="price-tag">
                {{ $propertyInfo['price'] }}
                @if($propertyInfo['purpose'] === 'rent' && $propertyInfo['rentalPeriod'])
                    <small>/ {{ $propertyInfo['rentalPeriod'] }}</small>
                @endif
            </span>
            @if($propertyInfo['priceNegotiable'])
                <span class="badge bg-light text-dark border ms-2">Negotiable</span>
            @endif
        </div>

        <div class="mb-2">
            <span class="badge bg-{{ $statusColors[$propertyInfo['status']] ?? 'secondary' }} badge-pill">
                {{ ucfirst($propertyInfo['status']) }}
            </span>
            <span class="badge bg-info text-dark badge-pill">{{ ucfirst($propertyInfo['purpose']) }}</span>
            @if($propertyInfo['verified'])
                <span class="badge bg-primary badge-pill"><i class="fa fa-check-circle"></i> Verified</span>
            @endif
            @if($propertyInfo['favorite'])
                <span class="badge bg-danger badge-pill"><i class="fa fa-heart"></i> Favourite</span>
            @endif
        </div>

        <div class="info-card">
            <div class="info-label">Property Code</div>
            <div class="info-value fw-semibold mb-0">{{ $propertyInfo['code'] }}</div>
        </div>
    </div>
</div>

{{-- Quick stats --}}
<div class="info-card mb-3">
    <div class="row">
        <div class="col stat-box">
            <div class="stat-value">{{ $propertyInfo['bedrooms'] }}</div>
            <div class="stat-label">Bedrooms</div>
        </div>
        <div class="col stat-box">
            <div class="stat-value">{{ $propertyInfo['bathrooms'] }}</div>
            <div class="stat-label">Bathrooms</div>
        </div>
        <div class="col stat-box">
            <div class="stat-value">{{ $propertyInfo['rooms'] }}</div>
            <div class="stat-label">Rooms</div>
        </div>
        <div class="col stat-box">
            <div class="stat-value">{{ $propertyInfo['garages'] }}</div>
            <div class="stat-label">Garages</div>
        </div>
        <div class="col stat-box">
            <div class="stat-value">{{ $propertyInfo['size'] }}</div>
            <div class="stat-label">Size (sqm)</div>
        </div>
        <div class="col stat-box">
            <div class="stat-value">{{ $propertyInfo['garage_size'] }}</div>
            <div class="stat-label">Garage Size</div>
        </div>
    </div>
</div>

{{-- Description --}}
@if($desc)
    <div class="mb-3">
        <h6 class="mb-2">Description</h6>
        <p class="text-muted small mb-0">{{ $desc }}</p>
    </div>
@endif

{{-- Details grid --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-card h-100">
            <div class="info-label">Category</div>
            <div class="info-value">{{ $categoryNm ?? '-' }}</div>

            <div class="info-label">Furnishing</div>
            <div class="info-value">{{ $propertyInfo['furnishing'] ?? '-' }}</div>

            <div class="info-label">Year Built</div>
            <div class="info-value">{{ $propertyInfo['year_built'] ?? '-' }}</div>

            <div class="info-label">Zip Code</div>
            <div class="info-value mb-0">{{ $addr['zip_code'] ?? '-' }}</div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-card h-100">
            <div class="info-label">Contact Phone</div>
            <div class="info-value">{{ $propertyInfo['phone'] ?? '-' }}</div>

            <div class="info-label">Coordinates</div>
            <div class="info-value">
                @if($propertyInfo['latitude'] && $propertyInfo['longitude'])
                    {{ $propertyInfo['latitude'] }}, {{ $propertyInfo['longitude'] }}
                @else
                    -
                @endif
            </div>

            <div class="info-label">Updated</div>
            <div class="info-value mb-0">
                {{ $propertyInfo['updateDate'] ? \Carbon\Carbon::parse($propertyInfo['updateDate'])->format('Y-m-d H:i') : '-' }}
            </div>
        </div>
    </div>
</div>

{{-- Media links --}}
@if($propertyInfo['videoTour'] || $propertyInfo['virtualTour'] || $propertyInfo['floorPlan'])
    <div class="mb-3">
        <h6 class="mb-2">Media</h6>
        <div class="d-flex flex-wrap gap-2">
            @if($propertyInfo['videoTour'])
                <a href="{{ $propertyInfo['videoTour'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-video"></i> Video Tour
                </a>
            @endif
            @if($propertyInfo['virtualTour'])
                <a href="{{ $propertyInfo['virtualTour'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-vr-cardboard"></i> Virtual Tour
                </a>
            @endif
            @if($propertyInfo['floorPlan'])
                <a href="{{ $propertyInfo['floorPlan'] }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-drafting-compass"></i> Floor Plan
                </a>
            @endif
        </div>
    </div>
@endif

{{-- Features --}}
@if($featureLabels->isNotEmpty())
    <div class="mb-3">
        <h6 class="mb-2">Features</h6>
        <div>
            @foreach($featureLabels as $label)
                <span class="feature-chip">{{ $label }}</span>
            @endforeach
        </div>
    </div>
@endif

{{-- Amenities --}}
@if($amenityLabels->isNotEmpty())
    <div class="mb-3">
        <h6 class="mb-2">Amenities</h6>
        <div>
            @foreach($amenityLabels as $label)
                <span class="amenity-chip">{{ $label }}</span>
            @endforeach
        </div>
    </div>
@endif

{{-- Agent --}}
@if($agent['id'])
    <div class="info-card mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ $agent['image'] ?? asset('images/no-avatar.png') }}"
                 class="agent-avatar me-3" alt="agent">
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ trim($agent['name']) ?: 'Unassigned' }}</div>
                <div class="text-muted small">
                    {{ $agent['phone'] ?? '-' }}
                    @if($agent['email']) &middot; {{ $agent['email'] }} @endif
                </div>
                <div class="text-muted small">
                    @if($agent['rating'])
                        <i class="fa fa-star text-warning"></i> {{ number_format($agent['rating'], 1) }}
                    @endif
                    @if($agent['experience'])
                        &middot; {{ $agent['experience'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Stats footer --}}
<p class="text-muted small mb-0">
    <i class="fa fa-eye"></i> {{ $propertyInfo['totalViews'] }} views
</p>