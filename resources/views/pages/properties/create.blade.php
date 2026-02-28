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

        .card-header i {
            margin-right: 10px;
        }

        .form-label {
            font-weight: 600;
            color: #4e5154;
            margin-bottom: 0.5rem;
        }

        .form-control, .select2-container .select2-selection--single {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            height: auto;
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
        }

        .image-upload-wrapper:hover {
            border-color: var(--primary-color);
            background: #f8faff;
        }

        .img-preview-custom {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            margin-top: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: none; /* Hidden until file is selected */
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
    </style>

    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('property.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h3 class="h4 mb-0 text-gray-800">Add New Property</h3>
                    </div>

                    {{-- Updated Route for Store --}}
                    <form action="{{ route('property.add') }}" method="POST" id="addProperty" enctype="multipart/form-data">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-info-circle"></i> Basic Information
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('global.property_title') }}*</label>
                                        <input type="text" name="title" class="form-control" placeholder="e.g. Luxury Villa in Downtown" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.type') }}*</label>
                                        <select class="form-control select2" name="type" id="type" required>
                                            <option value="">{{ __('global.select_type') }}</option>
                                            @foreach (getTypes() as $type_data)
                                                <option value="{{ $type_data->id }}"> {{ $type_data->type_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.purpose') }}</label>
                                        <select class="form-control" name="purpose" id="purpose">
                                            <option value="Sale">Sale</option>
                                            <option value="Rent">Rent</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('global.description') }}</label>
                                        <textarea id="elm1" name="description" class="form-control tinymce"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-map-marker-alt"></i> Location & Contact
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('global.phone') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="phone" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('global.location_text') }} *</label>
                                        <select class="form-control select2" name="location" id="location" required>
                                            <option value="">{{ __('global.select_location') }}</option>
                                            @foreach (getLocations() as $location_data)
                                                <option value="{{ $location_data->id }}"> {{ $location_data->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('global.address') }}</label>
                                        <input type="text" name="address" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Latitude</label>
                                        <input type="text" name="latitude" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Longitude</label>
                                        <input type="text" name="longitude" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-list"></i> Property Features
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.bedrooms') }}</label>
                                        <input type="number" name="bedrooms" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.bathrooms') }}</label>
                                        <input type="number" name="bathrooms" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.area') }} (sq ft)</label>
                                        <input type="text" name="area" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.furnishing') }}</label>
                                        <select class="form-control" name="furnishing">
                                            <option value="Unfurnished">Unfurnished</option>
                                            <option value="Semi-Furnished">Semi-Furnished</option>
                                            <option value="Furnished">Furnished</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('global.price') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ __('global.verified') }}</label>
                                        <select class="form-control" name="verified">
                                            <option value="NO">No</option>
                                            <option value="YES">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('global.amenities') }}</label>
                                        <input type="text" name="amenities" class="form-control" data-role="tagsinput" placeholder="Add and press Enter">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-images"></i> Media Assets
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Featured Image</label>
                                        <div class="image-upload-wrapper">
                                            <input type="file" name="feature_image" id="feature_image" class="d-none" accept="image/*">
                                            <button type="button" class="btn btn-dark mb-2" id="feature-image-btn">Select Image</button>
                                            <p class="small text-muted mb-0">Recommended: 800x480px</p>
                                            <img id="feature-image-preview" src="#" class="img-preview-custom">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Floor Plan</label>
                                        <div class="image-upload-wrapper">
                                            <input type="file" name="floor_plan_image" id="floor_plan_image" class="d-none" accept="image/*">
                                            <button type="button" class="btn btn-dark mb-2" id="floor-plan-image-btn">Select Plan</button>
                                            <p class="small text-muted mb-0">Upload architecture layout</p>
                                            <img id="floor-plan-image-preview" src="#" class="img-preview-custom">
                                        </div>
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
                                    {{-- Start with one empty slot or leave empty --}}
                                </div>
                            </div>
                            {{-- Actions --}}
                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> {{__('global.save')}} Changes
                                </button>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).on('submit', '#addProperty', function (event) {
            event.preventDefault();
            ajaxSubmit('#addProperty');
        });
        $(document).ready(function () {
            // Trigger file inputs
            $('#feature-image-btn').click(() => $('#feature_image').click());
            $('#floor-plan-image-btn').click(() => $('#floor_plan_image').click());

            // Simple Preview Logic
            function readURL(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#feature_image").change(function() { readURL(this, '#feature-image-preview'); });
            $("#floor_plan_image").change(function() { readURL(this, '#floor-plan-image-preview'); });

            // Gallery logic
            $('#add-gallery-image').on('click', function () {
                $('#gallery-images-wrapper').append(`
                    <div class="gallery-image-item">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <input type="file" name="gallery_image[]" accept="image/*" class="gallery-file d-none">
                                <input type="text" class="form-control gallery-image-name bg-white" readonly placeholder="No file selected">
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-dark select-gallery" style="height: 36px">Select</button>
                                <button type="button" class="btn btn-outline-danger remove-gallery" style="height: 36px"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="preview mt-2"></div>
                    </div>
                `);
            });

            $(document).on('click', '.select-gallery', function() {
                $(this).closest('.gallery-image-item').find('.gallery-file').click();
            });

            $(document).on('change', '.gallery-file', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).siblings('.gallery-image-name').val(fileName);
            });

            $(document).on('click', '.remove-gallery', function() {
                $(this).closest('.gallery-image-item').remove();
            });
        });
    </script>
    @endpush
</x-app-layout>