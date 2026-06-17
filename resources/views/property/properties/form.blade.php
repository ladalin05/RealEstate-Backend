<x-app-layout>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #f8f9fc;
            --border-color: #e3e6f0;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .card-header i { margin-right: 10px; }

        .form-label {
            font-weight: 600;
            color: #4e5154;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .select2-container .select2-selection--single {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            height: auto;
        }

        .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
            height: auto !important;
        }

        .select2-selection__arrow b {
            top: 50% !important;
        }

        .form-control {
            padding: 0.8rem 1rem !important;
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            background-color: #f1f3f9;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            transform: translateY(-1px);
        }

        .image-upload-wrapper {
            background: #fdfdfd;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            position: relative;
        }

        .image-upload-wrapper:hover {
            border-color: var(--primary-color);
            background: #f8faff;
        }

        .image-upload-wrapper.uploading {
            border-color: #f6c23e;
            background: #fffbf0;
        }

        .image-upload-wrapper.upload-done {
            border-color: var(--success-color);
            background: #f0fdf8;
        }

        .img-preview-custom {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            margin-top: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .img-preview-custom.hidden { display: none; }

        .upload-spinner {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.75);
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .upload-spinner.active { display: flex; }

        .spinner-border-sm { width: 1.2rem; height: 1.2rem; }

        .btn-remove-image {
            display: none;
            margin-top: 8px;
        }

        .section-divider {
            height: 1px;
            background: var(--border-color);
            margin: 2rem 0;
        }

        .gallery-image-item {
            background: #fff;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .gallery-image-item.uploading { border-color: #f6c23e; }
        .gallery-image-item.upload-done { border-color: var(--success-color); }
        .gallery-image-item.upload-error { border-color: #e74a3b; }

        .upload-status-badge {
            font-size: 0.72rem;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* Rental period row: hidden by default, shown when purpose = rent */
        #rental-period-row { display: none; }
        #price-label-row   { display: none; }
    </style>

    @php
        $isEdit      = isset($isEdit) && $isEdit;
        $property    = $property ?? null;

        $formAction  = $isEdit
            ? route('property.properties.edit', ['id' => $property->id])
            : route('property.properties.add');

        $pageTitle   = $isEdit ? 'Edit Property Details' : 'Add New Property';
        $submitLabel = $isEdit ? 'Save Changes'          : 'Create Property';

        // Helper: old() → model value → default
        $val = fn(string $field, mixed $default = '') => old($field, $property?->{$field} ?? $default);

        $selectedAmenities = $isEdit ? $property->amenities->pluck('id')->toArray() : [];
        $selectedFeatures  = $isEdit ? $property->features->pluck('id')->toArray()  : [];

        // Existing MinIO paths
        $mainImagePath  = $isEdit ? ($property->main_image       ?? '') : '';
        $floorPlanPath  = $isEdit ? ($property->floor_plan_image  ?? '') : '';

        $minioBase     = rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/';
        $mainImageUrl  = $mainImagePath ? $minioBase . $mainImagePath : null;
        $floorPlanUrl  = $floorPlanPath ? $minioBase . $floorPlanPath : null;

        // Enums from DB
        $purposes       = ['sale' => 'Sale', 'rent' => 'Rent', 'sale_rent' => 'Sale & Rent'];
        $furnishings    = ['unfurnished' => 'Unfurnished', 'semi-furnished' => 'Semi-Furnished', 'furnished' => 'Furnished'];
        $directions     = ['north' => 'North', 'south' => 'South', 'east' => 'East', 'west' => 'West',
                           'northeast' => 'Northeast', 'northwest' => 'Northwest', 'southeast' => 'Southeast', 'southwest' => 'Southwest'];
        $statusOptions  = ['draft' => 'Draft', 'active' => 'Active', 'inactive' => 'Inactive', 'sold' => 'Sold', 'rented' => 'Rented'];
        $rentalPeriods  = ['daily' => 'Daily', 'monthly' => 'Monthly', 'yearly' => 'Yearly'];
        $currencies     = ['USD' => 'USD ($)', 'KHR' => 'KHR (៛)'];
    @endphp

    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('property.properties.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h3 class="h4 mb-0 text-gray-800">{{ $pageTitle }}</h3>
                    </div>

                    <form action="{{ $formAction }}"
                          method="POST"
                          id="propertyForm"
                          class="ajax-form">
                        @csrf

                        {{-- ═══════════════════════════════════════════
                             CARD 1 – Basic Information
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-info-circle"></i> Basic Information
                            </div>
                            <div class="card-body">

                                {{-- Title --}}
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('global.property_title') }} *</label>
                                        <input type="text"
                                               name="title"
                                               value="{{ $val('title') }}"
                                               class="form-control"
                                               placeholder="e.g. Luxury Villa in Downtown"
                                               required>
                                    </div>
                                </div>

                                {{-- Type / Purpose / Status --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.type') }} *</label>
                                        <select class="  select2" name="type_id" required>
                                            <option value="">{{ __('global.select_type') }}</option>
                                            @foreach (getTypes() as $type_data)
                                                <option value="{{ $type_data->id }}"
                                                    {{ $type_data->id == ($property?->type_id) ? 'selected' : '' }}>
                                                    {{ $type_data->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.purpose') }} *</label>
                                        <select class="form-control" name="purpose" id="purpose" required>
                                            @foreach ($purposes as $pVal => $pLabel)
                                                <option value="{{ $pVal }}"
                                                    {{ $val('purpose', 'sale') === $pVal ? 'selected' : '' }}>
                                                    {{ $pLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Status *</label>
                                        <select class="form-control" name="status" required>
                                            @foreach ($statusOptions as $sVal => $sLabel)
                                                <option value="{{ $sVal }}"
                                                    {{ $val('status', 'draft') === $sVal ? 'selected' : '' }}>
                                                    {{ $sLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Rental period (visible only when purpose = rent or sale_rent) --}}
                                <div class="row mb-3" id="rental-period-row">
                                    <div class="col-md-4">
                                        <label class="form-label">Rental Period</label>
                                        <select class="form-control" name="rental_period">
                                            <option value="">— Select —</option>
                                            @foreach ($rentalPeriods as $rVal => $rLabel)
                                                <option value="{{ $rVal }}"
                                                    {{ $val('rental_period') === $rVal ? 'selected' : '' }}>
                                                    {{ $rLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('global.description') }}</label>
                                        <textarea id="elm1" name="description" class="form-control tinymce">{{ $val('description') }}</textarea>
                                    </div>
                                </div>

                                {{-- Internal Notes --}}
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Internal Notes
                                            <small class="text-muted fw-normal">(not shown publicly)</small>
                                        </label>
                                        <textarea name="notes" class="form-control" rows="2"
                                                  placeholder="Staff-only notes about this listing…">{{ $val('notes') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 2 – Location & Contact
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-map-marker-alt"></i> Location & Contact
                            </div>
                            <div class="card-body">

                                {{-- Phone --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.phone') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="text"
                                                   name="phone"
                                                   value="{{ $val('phone') }}"
                                                   class="form-control"
                                                   placeholder="+855 xx xxx xxx">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.country') }}</label>
                                        <select class="form-control select2" name="country_id" id="country">
                                            <option value="">{{ __('global.select_country') }}</option>
                                            @if ($isEdit && $property?->location?->country_id)
                                                <option value="{{ $property->location->country_id }}" selected>
                                                    {{ $property->location->country?->name ?? '' }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.province') }}</label>
                                        <select class="form-control select2" name="province_id" id="province">
                                            <option value="">{{ __('global.select_province') }}</option>
                                            @if ($isEdit && $property?->location?->province_id)
                                                <option value="{{ $property->location->province_id }}" selected>
                                                    {{ $property->location->province?->name ?? '' }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.district') }}</label>
                                        <select class="form-control select2" name="district_id" id="district">
                                            <option value="">{{ __('global.select_district') }}</option>
                                            @if ($isEdit && $property?->location?->district_id)
                                                <option value="{{ $property->location->district_id }}" selected>
                                                    {{ $property->location->district?->name ?? '' }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.commune') }}</label>
                                        <select class="form-control select2" name="commune_id" id="commune">
                                            <option value="">{{ __('global.select_commune') }}</option>
                                            @if ($isEdit && $property?->location?->commune_id)
                                                <option value="{{ $property->location->commune_id }}" selected>
                                                    {{ $property->location->commune?->name ?? '' }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.address') }}</label>
                                        <input type="text"
                                               name="address"
                                               value="{{ old('address', stripslashes($property?->location?->address ?? '')) }}"
                                               class="form-control"
                                               placeholder="Street / landmark">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Latitude</label>
                                        <input type="text"
                                               name="latitude"
                                               value="{{ old('latitude', $property?->location?->latitude ?? '') }}"
                                               class="form-control"
                                               placeholder="e.g. 11.562108">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Longitude</label>
                                        <input type="text"
                                               name="longitude"
                                               value="{{ old('longitude', $property?->location?->longitude ?? '') }}"
                                               class="form-control"
                                               placeholder="e.g. 104.888535">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 3 – Property Details
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-list"></i> Property Details
                            </div>
                            <div class="card-body">

                                {{-- Rooms / Bathrooms / Floors / Floor number --}}
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Rooms</label>
                                        <input type="number" min="0" name="rooms"
                                               value="{{ $val('rooms') }}"
                                               class="form-control" placeholder="Total rooms">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.bathrooms') }}</label>
                                        <input type="number" min="0" name="bathrooms"
                                               value="{{ $val('bathrooms', 0) }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Floors / Storeys</label>
                                        <input type="number" min="0" name="floors"
                                               value="{{ $val('floors') }}"
                                               class="form-control" placeholder="e.g. 2">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Floor Number
                                            <small class="text-muted fw-normal">(apartments)</small>
                                        </label>
                                        <input type="number" min="0" name="floor_number"
                                               value="{{ $val('floor_number') }}"
                                               class="form-control" placeholder="e.g. 5">
                                    </div>
                                </div>

                                {{-- Area / Land size / Year built / Direction --}}
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Built Area</label>
                                        <input type="text" name="area_size"
                                               value="{{ $val('area_size') }}"
                                               class="form-control" placeholder='e.g. "120 sqm"'>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Land Size</label>
                                        <input type="text" name="land_size"
                                               value="{{ $val('land_size') }}"
                                               class="form-control" placeholder='e.g. "300 sqm"'>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Year Built</label>
                                        <input type="number" min="1900" max="{{ date('Y') }}"
                                               name="year_built"
                                               value="{{ $val('year_built') }}"
                                               class="form-control" placeholder="e.g. 2020">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Facing Direction</label>
                                        <select class="form-control" name="direction">
                                            <option value="">— Any —</option>
                                            @foreach ($directions as $dVal => $dLabel)
                                                <option value="{{ $dVal }}"
                                                    {{ $val('direction') === $dVal ? 'selected' : '' }}>
                                                    {{ $dLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Furnishing --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.furnishing') }}</label>
                                        <select class="form-control" name="furnishing">
                                            @foreach ($furnishings as $fVal => $fLabel)
                                                <option value="{{ $fVal }}"
                                                    {{ $val('furnishing', 'unfurnished') === $fVal ? 'selected' : '' }}>
                                                    {{ $fLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Featured</label>
                                        <select class="form-control" name="featured">
                                            <option value="0" {{ $val('featured', 0) == 0 ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ $val('featured', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.verified') }}</label>
                                        <select class="form-control" name="verified">
                                            <option value="0" {{ $val('verified', 0) == 0 ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ $val('verified', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Amenities / Features --}}
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label"><strong>{{ __('global.amenities') }}</strong></label>
                                        <select name="amenities[]" class="form-control select2-multiple" multiple="multiple">
                                            @foreach (getAmenity() as $amenity)
                                                <option value="{{ $amenity->id }}"
                                                    {{ in_array($amenity->id, $selectedAmenities) ? 'selected' : '' }}>
                                                    {{ $amenity->{'name_'.app()->getLocale()} }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label"><strong>{{ __('global.features') }}</strong></label>
                                        <select name="features[]" class="form-control select2-multiple" multiple="multiple">
                                            @foreach (getFeature() as $feature)
                                                <option value="{{ $feature->id }}"
                                                    {{ in_array($feature->id, $selectedFeatures) ? 'selected' : '' }}>
                                                    {{ $feature->{'name_'.app()->getLocale()} }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 4 – Pricing
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-dollar-sign"></i> Pricing
                            </div>
                            <div class="card-body">

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Currency</label>
                                        <select class="form-control" name="currency">
                                            @foreach ($currencies as $cVal => $cLabel)
                                                <option value="{{ $cVal }}"
                                                    {{ $val('currency', 'USD') === $cVal ? 'selected' : '' }}>
                                                    {{ $cLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.price') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="currency-symbol">$</span>
                                            <input type="number" min="0" step="0.01"
                                                   name="price"
                                                   value="{{ $val('price') }}"
                                                   class="form-control"
                                                   placeholder="Numeric price for filtering">
                                        </div>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end pb-1">
                                        <div class="form-check">
                                            <input type="hidden" name="price_negotiable" value="0">
                                            <input class="form-check-input" type="checkbox"
                                                   name="price_negotiable" value="1"
                                                   id="price_negotiable"
                                                   {{ $val('price_negotiable', 0) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="price_negotiable">
                                                Negotiable
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Price Label
                                            <small class="text-muted fw-normal">(overrides numeric)</small>
                                        </label>
                                        <input type="text" name="price_label"
                                               value="{{ $val('price_label') }}"
                                               class="form-control"
                                               placeholder='e.g. "Contact us", "From $500/mo"'>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 5 – Media & Links
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-images"></i> Media & Links
                            </div>
                            <div class="card-body">

                                {{-- Single images row --}}
                                <div class="row mb-4">

                                    {{-- Main Image --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Main Image</label>
                                        <div class="image-upload-wrapper {{ $mainImageUrl ? 'upload-done' : '' }}" id="main-image-wrapper">
                                            <div class="upload-spinner" id="main-image-spinner">
                                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                                Uploading…
                                            </div>
                                            <input type="file" id="main_image_file" class="d-none" accept="image/*">
                                            <input type="hidden" name="main_image" id="main_image_path"
                                                   value="{{ $mainImagePath }}">
                                            <button type="button" class="btn btn-dark mb-2" id="main-image-btn">
                                                {{ $isEdit && $mainImagePath ? 'Change Image' : 'Select Image' }}
                                            </button>
                                            <p class="small text-muted mb-0">Recommended: 800×480px</p>
                                            <img id="main-image-preview"
                                                 src="{{ $mainImageUrl ?? '#' }}"
                                                 class="img-preview-custom {{ $mainImageUrl ? '' : 'hidden' }}">
                                            <div>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-remove-image mt-2"
                                                        id="main-image-remove"
                                                        style="{{ $mainImagePath ? 'display:inline-block' : '' }}">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Floor Plan --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Floor Plan</label>
                                        <div class="image-upload-wrapper {{ $floorPlanUrl ? 'upload-done' : '' }}" id="floor-plan-wrapper">
                                            <div class="upload-spinner" id="floor-plan-spinner">
                                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                                Uploading…
                                            </div>
                                            <input type="file" id="floor_plan_file" class="d-none" accept="image/*">
                                            <input type="hidden" name="floor_plan_image" id="floor_plan_path"
                                                   value="{{ $floorPlanPath }}">
                                            <button type="button" class="btn btn-dark mb-2" id="floor-plan-btn">
                                                {{ $isEdit && $floorPlanPath ? 'Change Plan' : 'Select Plan' }}
                                            </button>
                                            <p class="small text-muted mb-0">Upload architecture layout</p>
                                            <img id="floor-plan-preview"
                                                 src="{{ $floorPlanUrl ?? '#' }}"
                                                 class="img-preview-custom {{ $floorPlanUrl ? '' : 'hidden' }}">
                                            <div>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-remove-image mt-2"
                                                        id="floor-plan-remove"
                                                        style="{{ $floorPlanPath ? 'display:inline-block' : '' }}">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Video & Virtual Tour URLs --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fa fa-video me-1 text-muted"></i> Video URL
                                        </label>
                                        <input type="url" name="video_url"
                                               value="{{ $val('video_url') }}"
                                               class="form-control"
                                               placeholder="https://youtube.com/…">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fa fa-street-view me-1 text-muted"></i> Virtual Tour URL
                                            <small class="text-muted fw-normal">(360° / Matterport)</small>
                                        </label>
                                        <input type="url" name="virtual_tour_url"
                                               value="{{ $val('virtual_tour_url') }}"
                                               class="form-control"
                                               placeholder="https://matterport.com/…">
                                    </div>
                                </div>

                                <div class="section-divider"></div>

                                {{-- Gallery --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">{{ __('global.gallery_images') }}</label>
                                    <button type="button" id="add-gallery-image" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add Image
                                    </button>
                                </div>

                                <div id="gallery-images-wrapper">
                                    @if ($isEdit)
                                        @foreach ($property->property_image as $image)
                                            @php
                                                $gPath = $image->image ?? '';
                                                $gUrl  = $gPath ? $minioBase . $gPath : asset('/upload/placeholder_img.jpg');
                                            @endphp
                                            <div class="gallery-image-item upload-done" data-path="{{ $gPath }}">
                                                <input type="hidden" name="gallery_images[]" value="{{ $gPath }}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-7">
                                                        <input type="file" accept="image/*" class="gallery-file d-none">
                                                        <input type="text"
                                                               class="form-control gallery-image-name bg-white"
                                                               readonly
                                                               value="{{ $gPath }}">
                                                    </div>
                                                    <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-2">
                                                        <span class="badge bg-success upload-status-badge">Uploaded</span>
                                                        <button type="button" class="btn btn-dark select-gallery" style="height:36px">Change</button>
                                                        <button type="button" class="btn btn-outline-danger remove-gallery" style="height:36px"
                                                                data-path="{{ $gPath }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="preview mt-2">
                                                    <img src="{{ $gUrl }}" width="120" height="90"
                                                         style="object-fit:cover;border-radius:6px;">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow" id="submit-btn">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> {{ $submitLabel }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('property.properties.script')
        <script>
        $(document).ready(function () {

            // ── Select2 ────────────────────────────────────────────────────
            $('.select2').select2({ width: '100%' });
            $('.select2-multiple').select2({
                placeholder: 'Select options',
                allowClear: true,
                width: '100%'
            });

            // ── Currency symbol sync ───────────────────────────────────────
            $('select[name="currency"]').on('change', function () {
                $('#currency-symbol').text($(this).val() === 'KHR' ? '៛' : '$');
            }).trigger('change');

            // ── Show/hide rental period based on purpose ───────────────────
            function toggleRentalPeriod() {
                const p = $('#purpose').val();
                $('#rental-period-row').toggle(p === 'rent' || p === 'sale_rent');
            }
            $('#purpose').on('change', toggleRentalPeriod);
            toggleRentalPeriod(); // run on load for edit mode

            // ── CSRF token ─────────────────────────────────────────────────
            const CSRF = $('meta[name="csrf-token"]').attr('content');

            // ── uploadToMinio ──────────────────────────────────────────────
            function uploadToMinio(file, folder = 'properties') {
                const fd = new FormData();
                fd.append('file', file);
                fd.append('folder', folder);
                return $.ajax({
                    url: '{{ route("uploads.store") }}',
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': CSRF }
                }).then(res => res.data);
            }

            // ── deleteFromMinio ────────────────────────────────────────────
            function deleteFromMinio(path) {
                if (!path) return;
                $.ajax({
                    url: '{{ route("uploads.destroy") }}',
                    method: 'DELETE',
                    data: JSON.stringify({ path }),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': CSRF }
                });
            }

            // ── Single-image uploader ──────────────────────────────────────
            function initSingleUploader(cfg) {
                const $file    = $(cfg.fileInputId);
                const $path    = $(cfg.pathInputId);
                const $preview = $(cfg.previewId);
                const $wrapper = $(cfg.wrapperId);
                const $spinner = $(cfg.spinnerId);
                const $remove  = $(cfg.removeId);
                const $btn     = $(cfg.triggerBtnId);

                $btn.on('click', () => $file.trigger('click'));

                $file.on('change', async function () {
                    const file = this.files[0];
                    if (!file) return;

                    $spinner.addClass('active');
                    $wrapper.addClass('uploading').removeClass('upload-done');
                    $btn.prop('disabled', true);

                    try {
                        const prevPath = $path.val();
                        if (prevPath) deleteFromMinio(prevPath);

                        const result = await uploadToMinio(file, cfg.folder);

                        $path.val(result.path);
                        $preview.attr('src', result.public_url).removeClass('hidden');
                        $remove.show();
                        $wrapper.removeClass('uploading').addClass('upload-done');
                        $btn.text('Change Image');
                    } catch (err) {
                        console.error('Upload failed', err);
                        alert('Upload failed. Please try again.');
                        $wrapper.removeClass('uploading');
                    } finally {
                        $spinner.removeClass('active');
                        $btn.prop('disabled', false);
                        $file.val('');
                    }
                });

                $remove.on('click', function () {
                    const path = $path.val();
                    if (path && confirm('Remove this image?')) {
                        deleteFromMinio(path);
                        $path.val('');
                        $preview.attr('src', '#').addClass('hidden');
                        $remove.hide();
                        $wrapper.removeClass('upload-done uploading');
                        $btn.text('Select Image');
                    }
                });
            }

            initSingleUploader({
                fileInputId:  '#main_image_file',
                pathInputId:  '#main_image_path',
                previewId:    '#main-image-preview',
                wrapperId:    '#main-image-wrapper',
                spinnerId:    '#main-image-spinner',
                removeId:     '#main-image-remove',
                triggerBtnId: '#main-image-btn',
                folder:       'properties/main',
            });

            initSingleUploader({
                fileInputId:  '#floor_plan_file',
                pathInputId:  '#floor_plan_path',
                previewId:    '#floor-plan-preview',
                wrapperId:    '#floor-plan-wrapper',
                spinnerId:    '#floor-plan-spinner',
                removeId:     '#floor-plan-remove',
                triggerBtnId: '#floor-plan-btn',
                folder:       'properties/floor-plan',
            });

            // ── Gallery helpers ────────────────────────────────────────────
            function galleryRowHtml() {
                return `
                    <div class="gallery-image-item">
                        <input type="hidden" name="gallery_images[]" value="">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <input type="file" accept="image/*" class="gallery-file d-none">
                                <input type="text" class="form-control gallery-image-name bg-white"
                                       readonly placeholder="No file selected">
                            </div>
                            <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-2">
                                <span class="badge bg-secondary upload-status-badge">Pending</span>
                                <button type="button" class="btn btn-dark select-gallery" style="height:36px">Select</button>
                                <button type="button" class="btn btn-outline-danger remove-gallery" style="height:36px" data-path="">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="preview mt-2"></div>
                    </div>`;
            }

            $('#add-gallery-image').on('click', function () {
                $('#gallery-images-wrapper').append(galleryRowHtml());
            });

            $(document).on('click', '.select-gallery', function () {
                $(this).closest('.gallery-image-item').find('.gallery-file').trigger('click');
            });

            $(document).on('change', '.gallery-file', async function () {
                const file = this.files[0];
                if (!file) return;

                const $item    = $(this).closest('.gallery-image-item');
                const $path    = $item.find('input[name="gallery_images[]"]');
                const $badge   = $item.find('.upload-status-badge');
                const $nameBox = $item.find('.gallery-image-name');
                const $select  = $item.find('.select-gallery');

                const prevPath = $path.val();
                if (prevPath) deleteFromMinio(prevPath);

                $badge.text('Uploading…').removeClass('bg-success bg-danger bg-secondary').addClass('bg-warning text-dark');
                $item.addClass('uploading').removeClass('upload-done upload-error');
                $select.prop('disabled', true);

                try {
                    const result = await uploadToMinio(file, 'properties/gallery');

                    $path.val(result.path);
                    $item.attr('data-path', result.path);
                    $item.find('.remove-gallery').attr('data-path', result.path);
                    $nameBox.val(result.path);
                    $badge.text('Uploaded').removeClass('bg-warning text-dark').addClass('bg-success');
                    $item.removeClass('uploading').addClass('upload-done');
                    $item.find('.preview').html(
                        `<img src="${result.public_url}" width="120" height="90" style="object-fit:cover;border-radius:6px;">`
                    );
                } catch (err) {
                    console.error('Gallery upload failed', err);
                    $badge.text('Failed').removeClass('bg-warning text-dark').addClass('bg-danger');
                    $item.removeClass('uploading').addClass('upload-error');
                    alert('Gallery image upload failed. Please try again.');
                } finally {
                    $select.prop('disabled', false);
                    $(this).val('');
                }
            });

            $(document).on('click', '.remove-gallery', function () {
                const path = $(this).attr('data-path') || '';
                const doRemove = () => {
                    if (path) deleteFromMinio(path);
                    $(this).closest('.gallery-image-item').remove();
                };
                path ? (confirm('Delete this image?') && doRemove()) : doRemove();
            });

            // ── Submit guard ───────────────────────────────────────────────
            $('#propertyForm').on('submit', function (e) {
                if ($('.gallery-image-item.uploading').length > 0 ||
                    $('#main-image-wrapper.uploading').length > 0 ||
                    $('#floor-plan-wrapper.uploading').length > 0) {
                    e.preventDefault();
                    alert('Please wait — some images are still uploading.');
                    return false;
                }
            });

        }); // end ready
        </script>
    @endpush
</x-app-layout>