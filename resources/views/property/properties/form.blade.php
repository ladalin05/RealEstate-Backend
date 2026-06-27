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

        #rental-period-row { display: none; }

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
                                    <div class="col-md-12">
                                        <x-basic.form.input
                                            label="{{ __('global.property_title') }}"
                                            name="title"
                                            :value="$property->title"
                                            placeholder="e.g. Luxury Villa in Downtown"
                                            required
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
                                </div>

                                {{-- Rental period (shown only when purpose = rent / sale_rent) --}}
                                <div class="row mb-3" id="rental-period-row">
                                    <div class="col-md-4">
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

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <x-basic.form.textarea
                                            label="{{ __('global.description') }}"
                                            name="description"
                                            id="elm1"
                                            class="tinymce"
                                            :value="$property ? $property?->description : ''"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <x-basic.form.textarea
                                            name="notes"
                                            :rows="2"
                                            placeholder="Staff-only notes about this listing…"
                                            :value="$property ? $property?->notes : ''"
                                        >
                                            <x-slot:label>
                                                Internal Notes <small class="text-muted fw-normal">(not shown publicly)</small>
                                            </x-slot:label>
                                        </x-basic.form.textarea>
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
                                        <label class="form-label">{{ __('global.phone') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="phone" value="{{ $property ? $property?->phone : '' }}" class="form-control" placeholder="+855 xx xxx xxx">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="{{ __('global.area') }}"
                                            name="area_id"
                                            class="select2"
                                            id="area"
                                            placeholder="{{ __('global.select_area') }}"
                                            :options="getAreas()->pluck('name', 'id')->toArray()"
                                            :value="$property?->area_id"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-basic.form.input
                                            label="{{ __('global.address') }}"
                                            name="address"
                                            :value="old('address', stripslashes($property?->address ?? ''))"
                                            placeholder="Street / landmark"
                                        />
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
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.price') }}</label>

                                        {{-- Hidden fields submitted to the server --}}
                                        <input type="hidden" name="price"       id="price_numeric" value="{{ $property->price }}">
                                        <input type="hidden" name="price_label" id="price_label"   value="{{ $property->price_label }}">

                                        <div class="input-group">
                                            <input
                                                type="text"
                                                id="price_display"
                                                class="form-control"
                                                placeholder='e.g. 300 or "Contact us"'
                                                value="{{ $property->price_label ?: ($property->price ? number_format($property->price, 0) : '') }}"
                                            >
                                        </div>
                                        <div class="d-flex justify-content-end mt-1">
                                            <div class="form-check">
                                                <input type="hidden" name="price_negotiable" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="price_negotiable" value="1"
                                                    id="price_negotiable"
                                                    {{ $property->price_negotiable == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="price_negotiable">
                                                    Negotiable
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
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

                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="Featured"
                                            name="featured"
                                            class="select2"
                                            :placeholder="null"
                                            :options="['0' => 'No', '1' => 'Yes']"
                                            :value="(string) $property->featured"
                                        />
                                    </div>

                                    <div class="col-md-4">
                                        <x-basic.form.select
                                            label="{{ __('global.verified') }}"
                                            name="verified"
                                            class="select2"
                                            :placeholder="null"
                                            :options="['0' => 'No', '1' => 'Yes']"
                                            :value="(string) $property->verified"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <x-basic.form.multiple-select
                                            name="amenities"
                                            class="select2-multiple"
                                            :options="getAmenity()->pluck('name_'.app()->getLocale(), 'id')->toArray()"
                                            :value="[$property->amenity_id]"
                                        >
                                            <x-slot:label><strong>{{ __('global.amenities') }}</strong></x-slot:label>
                                        </x-basic.form.multiple-select>
                                    </div>

                                    <div class="col-6">
                                        <x-basic.form.multiple-select
                                            name="features"
                                            class="select2-multiple"
                                            :options="getFeature()->pluck('name_'.app()->getLocale(), 'id')->toArray()"
                                            :value="[$property->feature_id]"
                                        >
                                            <x-slot:label><strong>{{ __('global.features') }}</strong></x-slot:label>
                                        </x-basic.form.multiple-select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 4 – Pricing
                        ════════════════════════════════════════════ --}} 

                        {{-- ═══════════════════════════════════════════
                             CARD 5 – Media & Links
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-images"></i> Media & Links
                            </div>
                            <div class="card-body">

                                <div class="row mb-4">
                                    @php
                                        $mainImgRaw = $property->main_image ?? '';
                                        $mainImgUrl = $mainImgRaw
                                            ? (str_starts_with($mainImgRaw, 'http://') || str_starts_with($mainImgRaw, 'https://')
                                                ? $mainImgRaw
                                                : Storage::url($mainImgRaw))
                                            : '';
                                    @endphp
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Main Image</label>
                                        <div class="image-upload-wrapper {{ $mainImgUrl ? 'upload-done' : '' }}" id="main-image-wrapper" data-storage-path="{{ $mainImgRaw }}">
                                            <div class="upload-spinner" id="main-image-spinner">
                                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                                Uploading…
                                            </div>
                                            <input type="file" id="main_image_file" class="d-none" accept="image/*">
                                            <input type="hidden" name="main_image" id="main_image_path" value="{{ $mainImgUrl }}">
                                            <button type="button" class="btn btn-dark mb-2" id="main-image-btn">
                                                {{ $mainImgUrl ? 'Change Image' : 'Select Image' }}
                                            </button>
                                            <p class="small text-muted mb-0">Recommended: 800×480px</p>
                                            <img id="main-image-preview"
                                                 src="{{ $mainImgUrl ?: '#' }}"
                                                 class="img-preview-custom {{ $mainImgUrl ? '' : 'hidden' }}">
                                            <div>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-remove-image mt-2"
                                                        id="main-image-remove"
                                                        style="{{ $mainImgUrl ? 'display:inline-block' : '' }}">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $floorPlanRaw = $property->floor_plan_image ?? '';
                                        $floorPlanUrl = $floorPlanRaw
                                            ? (str_starts_with($floorPlanRaw, 'http://') || str_starts_with($floorPlanRaw, 'https://')
                                                ? $floorPlanRaw
                                                : Storage::url($floorPlanRaw))
                                            : '';
                                    @endphp
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Floor Plan</label>
                                        <div class="image-upload-wrapper {{ $floorPlanUrl ? 'upload-done' : '' }}" id="floor-plan-wrapper" data-storage-path="{{ $floorPlanRaw }}">
                                            <div class="upload-spinner" id="floor-plan-spinner">
                                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                                Uploading…
                                            </div>
                                            <input type="file" id="floor_plan_file" class="d-none" accept="image/*">
                                            <input type="hidden" name="floor_plan_image" id="floor_plan_path" value="{{ $floorPlanUrl }}">
                                            <button type="button" class="btn btn-dark mb-2" id="floor-plan-btn">
                                                {{ $floorPlanUrl ? 'Change Plan' : 'Select Plan' }}
                                            </button>
                                            <p class="small text-muted mb-0">Upload architecture layout</p>
                                            <img id="floor-plan-preview"
                                                 src="{{ $floorPlanUrl ?: '#' }}"
                                                 class="img-preview-custom {{ $floorPlanUrl ? '' : 'hidden' }}">
                                            <div>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-remove-image mt-2"
                                                        id="floor-plan-remove"
                                                        style="{{ $floorPlanUrl ? 'display:inline-block' : '' }}">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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

                                <div class="section-divider"></div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">{{ __('global.gallery_images') }}</label>
                                    <button type="button" id="add-gallery-image" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add Image
                                    </button>
                                </div>

                                <div id="gallery-images-wrapper">
                                    @if ($property->id)
                                        @foreach ($property->property_image as $image)
                                            @php
                                                $gStoragePath = $image->image ?? '';
                                                $gFullUrl = $gStoragePath
                                                    ? (str_starts_with($gStoragePath, 'http://') || str_starts_with($gStoragePath, 'https://')
                                                        ? $gStoragePath
                                                        : Storage::url($gStoragePath))
                                                    : asset('/upload/placeholder_img.jpg');
                                            @endphp
                                            <div class="gallery-image-item upload-done"
                                                 data-storage-path="{{ $gStoragePath }}">
                                                <input type="hidden" name="gallery_images[]" value="{{ $gFullUrl }}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-7">
                                                        <input type="file" accept="image/*" class="gallery-file d-none">
                                                        <input type="text"
                                                               class="form-control gallery-image-name bg-white"
                                                               readonly
                                                               value="{{ $gFullUrl }}">
                                                    </div>
                                                    <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-2">
                                                        <span class="badge bg-success upload-status-badge">Uploaded</span>
                                                        <button type="button" class="btn btn-dark select-gallery" style="height:36px">Change</button>
                                                        <button type="button" class="btn btn-outline-danger remove-gallery" style="height:36px"
                                                                data-storage-path="{{ $gStoragePath }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="preview mt-2">
                                                    <img src="{{ $gFullUrl }}" width="120" height="90"
                                                         style="object-fit:cover;border-radius:6px;">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

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
        $(document).ready(function () {

            $('.select2').select2({ width: '100%' });
            $('.select2-multiple').select2({
                placeholder: 'Select options',
                allowClear: true,
                width: '100%'
            });

            // ── Rental period toggle ──────────────────────────────
            function toggleRentalPeriod() {
                const p = $('#purpose').val();
                $('#rental-period-row').toggle(p === 'rent' || p === 'sale_rent');
            }
            $('#purpose').on('change', toggleRentalPeriod);
            toggleRentalPeriod();

            // ── Price display → hidden price + price_label ────────
            function syncPrice() {
                const raw     = $('#price_display').val().trim();
                const cleaned = raw.replace(/,/g, '');
                const numeric = parseFloat(cleaned);

                if (raw !== '' && !isNaN(numeric) && /^[\d,\.]+$/.test(raw)) {
                    // Pure number → store in price, clear price_label
                    $('#price_numeric').val(numeric);
                    $('#price_label').val('');
                } else {
                    // Text label → clear price, store in price_label
                    $('#price_numeric').val('');
                    $('#price_label').val(raw);
                }
            }
            $('#price_display').on('input', syncPrice);
            syncPrice(); // run once on load to sync existing value

            // ── MinIO upload helpers ──────────────────────────────
            const CSRF = $('meta[name="csrf-token"]').attr('content');

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

            // ── Single image uploader ─────────────────────────────
            function initSingleUploader(cfg) {
                const $file    = $(cfg.fileInputId);
                const $path    = $(cfg.pathInputId);
                const $preview = $(cfg.previewId);
                const $wrapper = $(cfg.wrapperId);
                const $spinner = $(cfg.spinnerId);
                const $remove  = $(cfg.removeId);
                const $btn     = $(cfg.triggerBtnId);

                let storagePath = $wrapper.attr('data-storage-path') || '';

                $btn.on('click', () => $file.trigger('click'));

                $file.on('change', async function () {
                    const file = this.files[0];
                    if (!file) return;

                    $spinner.addClass('active');
                    $wrapper.addClass('uploading').removeClass('upload-done');
                    $btn.prop('disabled', true);

                    try {
                        if (storagePath) deleteFromMinio(storagePath);

                        const result = await uploadToMinio(file, cfg.folder);

                        $path.val(result.public_url);
                        storagePath = result.path;
                        $wrapper.attr('data-storage-path', storagePath);
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
                    if (storagePath && confirm('Remove this image?')) {
                        deleteFromMinio(storagePath);
                        storagePath = '';
                        $wrapper.removeAttr('data-storage-path');
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

            // ── Gallery images ────────────────────────────────────
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
                                <button type="button" class="btn btn-outline-danger remove-gallery" style="height:36px" data-storage-path="">
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

                const prevStoragePath = $item.attr('data-storage-path') || '';
                if (prevStoragePath) deleteFromMinio(prevStoragePath);

                $badge.text('Uploading…').removeClass('bg-success bg-danger bg-secondary').addClass('bg-warning text-dark');
                $item.addClass('uploading').removeClass('upload-done upload-error');
                $select.prop('disabled', true);

                try {
                    const result = await uploadToMinio(file, 'properties/gallery');

                    $path.val(result.public_url);
                    $nameBox.val(result.public_url);
                    $item.attr('data-storage-path', result.path);
                    $item.find('.remove-gallery').attr('data-storage-path', result.path);
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
                const storagePath = $(this).attr('data-storage-path') || '';
                const doRemove = () => {
                    if (storagePath) deleteFromMinio(storagePath);
                    $(this).closest('.gallery-image-item').remove();
                };
                storagePath ? (confirm('Delete this image?') && doRemove()) : doRemove();
            });

            // ── Guard: block submit while uploads are in progress ─
            $('#propertyForm').on('submit', function (e) {
                if ($('.gallery-image-item.uploading').length > 0 ||
                    $('#main-image-wrapper.uploading').length > 0 ||
                    $('#floor-plan-wrapper.uploading').length > 0) {
                    e.preventDefault();
                    alert('Please wait — some images are still uploading.');
                    return false;
                }
            });

        });
        </script>
    @endpush
</x-app-layout>