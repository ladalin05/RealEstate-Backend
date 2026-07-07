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

        .section-divider {
            height: 1px;
            background: var(--border-color);
            margin: 2rem 0;
        }

        #rental-period-row { display: none; }

        /* Bilingual field tabs */
        .lang-tabs {
            display: inline-flex;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .lang-tab-btn {
            border: none;
            background: #fff;
            padding: 0.25rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--secondary-color);
            cursor: pointer;
        }

        .lang-tab-btn.active {
            background: var(--primary-color);
            color: #fff;
        }

        .lang-pane { display: none; }
        .lang-pane.active { display: block; }

        .form-control,
        .select2-container .select2-selection--single {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.35rem 1rem;
            height: auto;
        }

        .form-control,
        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.35rem 1rem;
            height: auto;
            min-height: 42px;
        }

        .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
            height: auto !important;
        }

        .select2-selection__arrow b {
            top: 50% !important;
        }

        .price-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .price-input-wrapper:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .price-prefix {
            padding-left: 12px;
            color: #6c757d;
            font-weight: 600;
            flex-shrink: 0;
        }

        .price-input-wrapper .price-input {
            border: none !important;
            box-shadow: none !important;
            background: transparent;
            flex: 1 1 auto;
            min-width: 0;
        }

        .price-input-wrapper .price-input:focus {
            box-shadow: none;
        }

        .price-input-wrapper .price-input:disabled {
            color: #495057;
            font-style: italic;
            background: transparent;
        }

        .negotiable-inline {
            display: flex;
            align-items: center;
            padding-right: 12px;
            flex-shrink: 0;
            border-left: 1px solid #eee;
            padding-left: 10px;
            white-space: nowrap;
        }

        .negotiable-inline .form-check-input {
            cursor: pointer;
            width: 2em;
            height: 1.1em;
            margin-top: 0;
        }

        .negotiable-inline .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .negotiable-inline .form-check-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #6c757d;
            margin-left: 6px;
            cursor: pointer;
            user-select: none;
        }

        .toggle-switch-group {
            display: flex;
            align-items: center;
            padding: 8px 14px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            height: 38px; /* aligns visually with select2's height */
        }

        .toggle-switch-group .form-check-input {
            cursor: pointer;
            width: 2.4em;
            height: 1.3em;
            margin-top: 0;
        }

        .toggle-switch-group .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .toggle-switch-group .form-check-label {
            margin-left: 10px;
            cursor: pointer;
            user-select: none;
        }

        .toggle-status-text {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
        }

        .toggle-switch-group .form-check-input:checked ~ .form-check-label .toggle-status-text {
            color: #198754;
        }

    </style>

    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('property.properties.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h3 class="h4 mb-0 text-gray-800">{{ $page_title }}</h3>
                    </div>

                    <form action="{{ $action }}" method="POST" id="propertyForm" class="ajax-form">
                        @csrf

                        {{-- ═══════════════════════════════════════════
                             CARD 1 – Basic Information
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-info-circle"></i> Basic Information
                            </div>
                            <div class="card-body p-4">

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <x-basic.form.lang-input
                                            name="title"
                                            :model="$property"
                                            label="Property Title"
                                            placeholder-en="Title"
                                            placeholder-kh="ចំណងជើង" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.input
                                            label="Property Code"
                                            name="property_code"
                                            :value="$property->property_code"
                                            placeholder="e.g. PP-1024"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="Agent"
                                            name="agent_id"
                                            class="select2"
                                            placeholder="— Unassigned —"
                                            :options="getAgents()->pluck('agency_name', 'id')->toArray()"
                                            :value="$property?->agent_id"
                                        />
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="{{ __('global.type') }}"
                                            name="category_id"
                                            class="select2"
                                            placeholder="{{ __('global.select_type') }}"
                                            :options="getTypes()->pluck('name_en', 'id')->toArray()"
                                            :value="$property?->category_id"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="{{ __('global.purpose') }}"
                                            name="purpose"
                                            class="select2"
                                            id="purpose"
                                            :placeholder="null"
                                            :options="collect(getPurposes())->pluck('name', 'value')->toArray()"
                                            :value="$property?->purpose"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="Status"
                                            name="status"
                                            class="select2"
                                            :placeholder="null"
                                            :options="collect(getStatuses())->pluck('name', 'value')->toArray()"
                                            :value="$property?->status"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-4" id="rental-period-row">
                                        <x-basic.form.select
                                            label="Rental Period"
                                            name="rental_period"
                                            class="select2"
                                            placeholder="— Select —"
                                            :options="collect(getRentalPeriods())->pluck('name', 'value')->toArray()"
                                            :value="$property?->rental_period"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-basic.form.input
                                            label="Published At"
                                            name="published_at"
                                            type="datetime-local"
                                            :value="$property->published_at ? \Carbon\Carbon::parse($property->published_at)->format('Y-m-d\TH:i') : ''"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.input
                                            label="Expires At"
                                            name="expires_at"
                                            type="datetime-local"
                                            :value="$property->expires_at ? \Carbon\Carbon::parse($property->expires_at)->format('Y-m-d\TH:i') : ''"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                </div>

                                {{-- Description (EN / KH) --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <x-basic.form.textarea
                                            name="notes"
                                            :rows="4"
                                            placeholder="Staff-only notes about this listing…"
                                            :value="$property ? $property?->notes : ''"
                                        >
                                            <x-slot:label>
                                                Internal Notes <small class="text-muted fw-normal">(not shown publicly)</small>
                                            </x-slot:label>
                                        </x-basic.form.textarea>
                                    </div>
                                    <div class="col-md-8">
                                        <x-basic.form.lang-input
                                            name="description"
                                            :model="$property"
                                            label="{{ __('global.description') }}"
                                            type="textarea"
                                            layout="stacked"
                                            :tinymce="true"
                                            id-en="elm1"
                                            id-kh="elm1_kh"
                                            :rows="4" />
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
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="{{ __('global.area') }}"
                                            name="area_id"
                                            class="select2"
                                            id="area"
                                            placeholder="{{ __('global.select_area') }}"
                                            :options="getAreas()->pluck('name_' . app()->getLocale(), 'id')->toArray()"
                                            :value="$property?->area_id"
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.lang-input
                                            name="address"
                                            :model="$property"
                                            label="Address"
                                            placeholder-en="Street / landmark"
                                            placeholder-kh="អាសយដ្ឋាន" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.phone') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="phone" value="{{ $property ? $property?->phone : '' }}" class="form-control" placeholder="+855 xx xxx xxx">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-basic.form.input
                                            label="Latitude"
                                            name="latitude"
                                            :value="old('latitude', $property?->latitude ?? '')"
                                            placeholder="e.g. 11.562108"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.input
                                            label="Longitude"
                                            name="longitude"
                                            :value="old('longitude', $property?->longitude ?? '')"
                                            placeholder="e.g. 104.888535"
                                        />
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

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Bedrooms"
                                            name="bedrooms"
                                            type="number"
                                            :value="$property->bedrooms ?? ''"
                                            placeholder="e.g. 3"
                                            min="0"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Rooms"
                                            name="rooms"
                                            type="number"
                                            :value="$property->rooms ?? ''"
                                            placeholder="Total rooms"
                                            min="0"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="{{ __('global.bathrooms') }}"
                                            name="bathrooms"
                                            type="number"
                                            :value="$property->bathrooms ?? ''"
                                            min="0"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Garages"
                                            name="garages"
                                            type="number"
                                            :value="$property->garages ?? ''"
                                            placeholder="e.g. 1"
                                            min="0"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Garage Size"
                                            name="garage_size"
                                            :value="$property->garage_size ?? ''"
                                            placeholder='e.g. "2 cars"'
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Built Area"
                                            name="area_size"
                                            :value="$property->area_size ?? ''"
                                            placeholder='e.g. "120 sqm"'
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Land Size"
                                            name="land_size"
                                            :value="$property->land_size ?? ''"
                                            placeholder='e.g. "300 sqm"'
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.input
                                            label="Year Built"
                                            name="year_built"
                                            type="number"
                                            :value="$property->year_built ?? ''"
                                            placeholder="e.g. 2020"
                                            min="1900"
                                            :max="date('Y')"
                                        />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">{{ __('global.price') }}</label>

                                        <input type="hidden" name="price" id="price_numeric" value="{{ $property->price }}">
                                        <input type="hidden" name="price_label" id="price_label" value="{{ $property->price_label }}">
                                        <input type="hidden" name="price_negotiable" value="0">

                                        <div class="price-input-wrapper">
                                            <input
                                                type="text"
                                                id="price_display"
                                                class="form-control price-input"
                                                placeholder='e.g. 300 or "Contact us"'
                                                value="{{ $property->price_label ?: ($property->price ? number_format($property->price, 0) : '') }}"
                                                {{ $property->price_negotiable == 1 ? 'disabled' : '' }}
                                            >

                                            <div class="negotiable-inline form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="price_negotiable" value="1"
                                                    id="price_negotiable" role="switch"
                                                    {{ $property->price_negotiable == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="price_negotiable">
                                                    Negotiable
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="{{ __('global.furnishing') }}"
                                            name="furnishing"
                                            class="select2"
                                            :placeholder="null"
                                            :options="collect(getFurnishing())->pluck('name', 'value')->toArray()"
                                            :value="$property?->furnishing"
                                        />
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold d-block">Featured</label>
                                        <div class="toggle-switch-group">
                                            <input type="hidden" name="featured" value="0">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="featured" value="1" id="featured_toggle"
                                                    role="switch"
                                                    {{ (string) $property->featured === '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="featured_toggle">
                                                    <span class="toggle-status-text">
                                                        {{ (string) $property->featured === '1' ? 'Yes' : 'No' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold d-block">{{ __('global.verified') }}</label>
                                        <div class="toggle-switch-group">
                                            <input type="hidden" name="verified" value="0">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="verified" value="1" id="verified_toggle"
                                                    role="switch"
                                                    {{ (string) $property->verified === '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verified_toggle">
                                                    <span class="toggle-status-text">
                                                        {{ (string) $property->verified === '1' ? 'Yes' : 'No' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <x-basic.form.multiple-select
                                            name="amenities[]"
                                            class="select2-multiple"
                                            :options="getAmenity()->pluck('name_'.app()->getLocale(), 'id')->toArray()"
                                            :value="$property->amenities->pluck('id')->toArray()"
                                        >
                                            <x-slot:label><strong>{{ __('global.amenities') }}</strong></x-slot:label>
                                        </x-basic.form.multiple-select>
                                    </div>

                                    <div class="col-6">
                                        <x-basic.form.multiple-select
                                            name="features[]"
                                            class="select2-multiple"
                                            :options="getFeature()->pluck('name_'.app()->getLocale(), 'id')->toArray()"
                                            :value="$property->features->pluck('id')->toArray()"
                                        >
                                            <x-slot:label><strong>{{ __('global.features') }}</strong></x-slot:label>
                                        </x-basic.form.multiple-select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 4 – Media & Links
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-images"></i> Media & Links
                            </div>
                            <div class="card-body">

                                @php
                                    $mainImgRaw = $property->main_image ?? '';
                                    $mainImgUrl = $mainImgRaw ? $mainImgRaw : '';

                                    $floorPlanRaw = $property->floor_plan_image ?? '';
                                    $floorPlanUrl = $floorPlanRaw ? $floorPlanRaw : '';
                                @endphp

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <x-basic.form.input
                                            name="video_url"
                                            type="url"
                                            :value="$property->video_url"
                                            placeholder="https://youtube.com/…"
                                        >
                                            <x-slot:label><i class="fa fa-video me-1 text-muted"></i> Video URL</x-slot:label>
                                        </x-basic.form.input>
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.input
                                            name="virtual_tour_url"
                                            type="url"
                                            :value="$property->virtual_tour_url"
                                            placeholder="https://matterport.com/…"
                                        >
                                            <x-slot:label>
                                                <i class="fa fa-street-view me-1 text-muted"></i> Virtual Tour URL
                                                <small class="text-muted fw-normal">(360° / Matterport)</small>
                                            </x-slot:label>
                                        </x-basic.form.input>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-4 px-5">
                                        <label class="form-label">Main Image</label>
                                        <div class="w-100 d-flex justify-content-center align-items-center">
                                            <x-basic.uploader
                                                input-name="main_image"
                                                :url="$mainImgUrl"
                                                :path="$mainImgRaw"
                                                :folder="'properties/main'"
                                                width="200px"
                                                height="150px"
                                                caption="Recommended: 800×480px"
                                            />
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4 px-5">
                                        <label class="form-label">Floor Plan</label>
                                        <div class="w-100 d-flex justify-content-center align-items-center">
                                            <x-basic.uploader
                                                input-name="floor_plan_image"
                                                :url="$floorPlanUrl"
                                                :path="$floorPlanRaw"
                                                :folder="'properties/floor_plan'"
                                                width="200px"
                                                height="150px"
                                                caption="Upload architecture layout"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="section-divider"></div>

                                <x-basic.uploader-group
                                    label="{{ __('global.gallery_images') }}"
                                    folder="properties/gallery"
                                    input-name="gallery_images[]"
                                    :items="$property->id
                                        ? $property->property_image->map(fn ($image) => [
                                            'url' => $image->image
                                                ? (str_starts_with($image->image, 'http://') || str_starts_with($image->image, 'https://')
                                                    ? $image->image
                                                    : Storage::url($image->image))
                                                : '',
                                            'path' => $image->image ?? '',
                                        ])->toArray()
                                        : []"
                                    width="150px"
                                    height="110px"
                                />

                            </div>

                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow" id="submit-btn">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> {{ $property->id ? 'Update' : 'Save' }}
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
            $(function () {

                // Select2
                $('.select2').select2({
                    width: '100%'
                });

                $('.select2-multiple').select2({
                    placeholder: 'Select options',
                    allowClear: true,
                    width: '100%'
                });

                // Language tab toggler
                $('.lang-tab-btn').on('click', function () {
                    const group = $(this).closest('.lang-tabs').data('lang-group');
                    const lang = $(this).data('lang');

                    $('.lang-tab-btn').filter(function () {
                        return $(this).closest('.lang-tabs').data('lang-group') === group;
                    }).removeClass('active');

                    $(this).addClass('active');

                    $('.lang-pane[data-lang-group="' + group + '"]').removeClass('active');
                    $('.lang-pane[data-lang-group="' + group + '"][data-lang="' + lang + '"]').addClass('active');
                });

                // Rental period toggle
                function toggleRentalPeriod() {
                    const purpose = $('#purpose').val();
                    $('#rental-period-row').toggle(
                        purpose === 'rent' || purpose === 'sale_rent'
                    );
                }

                $('#purpose').on('change', toggleRentalPeriod);
                toggleRentalPeriod();

                // Price display -> hidden price + price_label
                function syncPrice() {
                    const raw = $('#price_display').val().trim();
                    const cleaned = raw.replace(/,/g, '');
                    const numeric = parseFloat(cleaned);

                    if (raw !== '' && !isNaN(numeric) && /^[\d,.]+$/.test(raw)) {
                        $('#price_numeric').val(numeric);
                        $('#price_label').val('');
                    } else {
                        $('#price_numeric').val('');
                        $('#price_label').val(raw);
                    }
                }

                $('#price_display').on('input', syncPrice);
                syncPrice();

                // Negotiable checkbox
                $('#price_negotiable').on('change', function () {
                    const $priceDisplay = $('#price_display');

                    if ($(this).is(':checked')) {
                        $priceDisplay
                            .val('Contact us')
                            .prop('disabled', true);
                    } else {
                        $priceDisplay
                            .prop('disabled', false)
                            .val('')
                            .focus();
                    }

                    syncPrice();
                });

                // Featured / Verified toggle labels
                $('.toggle-switch-group .form-check-input').on('change', function () {
                    $(this)
                        .closest('.toggle-switch-group')
                        .find('.toggle-status-text')
                        .text($(this).is(':checked') ? 'Yes' : 'No');
                });

                // Prevent submit while uploading
                $('#propertyForm').on('submit', function (e) {
                    if ($('.uploader-item.uploading').length) {
                        e.preventDefault();
                        alert('Please wait — some images are still uploading.');
                        return false;
                    }
                });

            });
        </script>
    @endpush
</x-app-layout>