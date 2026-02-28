<x-app-layout>
    <style>
        /* Custom UI Enhancements */
        :root {
            --primary-accent: #e63946; /* Professional Red */
            --dark-surface: #2b2d42;
            --soft-bg: #f8f9fa;
        }

        .bg-custom-light { background-color: var(--soft-bg); }
        
        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header-custom {
            background-color: #fff;
            border-bottom: 1px solid #edf2f7;
            padding: 1rem 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-surface);
            margin-bottom: 0.5rem;
        }

        .form-control-modern {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control-modern:focus {
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 0.25 red radial-gradient(rgba(230, 57, 70, 0.1));
        }

        .image-preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            background: #fff;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center; 
        }

        .btn-save {
            background-color: var(--primary-accent);
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: #d62828;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }

        .input-group-text-btn {
            background-color: #6c757d;
            color: white;
            cursor: pointer;
            border: none;
        }
    </style>

    <div class="content mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('type.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h3 class="h4 mb-0 text-gray-800">Edit Type Details</h3>
                        
                    </div>
                    
                    <div class="card card-modern">
                        {{-- Body --}}
                        <div class="card-body p-3 p-md-3">
                            <form method="POST" action="{{ route('type.edit', $data_obj->id) }}" id="form-type" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data_obj->id ?? '' }}">

                                {{-- Name Field --}}
                                <div class="mb-2">
                                    <label for="type_name" class="form-label">{{__('global.type_name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="type_name" id="type_name" 
                                           value="{{ isset($data_obj->type_name) ? stripslashes($data_obj->type_name) : '' }}" 
                                           class="form-control form-control-modern" 
                                           placeholder="e.g. Electronic Devices">
                                </div>

                                {{-- Image Field --}}
                                <div class="mb-2">
                                    <label class="form-label">{{__('global.type_image')}}</label>
                                    <div class="input-group mb-2">
                                        <input type="file" name="type_image" id="type-image" class="d-none" accept="image/*">
                                        <input type="text" id="type-image-name" readonly 
                                               class="form-control form-control-modern bg-white" 
                                               placeholder="No file chosen" 
                                               value="{{ $data_obj->type_image ?? '' }}">
                                        <button class="btn btn-dark px-4" type="button" id="type-image-btn">Browse</button>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Recommended: 600x400px</small>
                                    </div>

                                    <div class="image-preview-container p-1">
                                        @php $hasImage = isset($data_obj->type_image) && $data_obj->type_image; @endphp
                                        <img src="{{ $hasImage ? asset('storage/'.$data_obj->type_image) : '#' }}" 
                                             id="type-image-preview" 
                                             alt="Preview" 
                                             class="img-fluid rounded {{ $hasImage ? '' : 'd-none' }}" 
                                             style="max-height: 100px;">
                                        
                                        <div id="no-image-placeholder" class="{{ $hasImage ? 'd-none' : '' }} text-muted text-center">
                                            <i class="fa-regular fa-image fa-3x mb-2 d-block"></i>
                                            <span>Image Preview</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Field --}}
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{__('global.status')}}</label>
                                    <select class="form-select form-control-modern" name="status" id="status">
                                        <option value="1" {{ (isset($data_obj->status) && $data_obj->status == 1) ? 'selected' : '' }}>{{__('global.active')}}</option>
                                        <option value="0" {{ (isset($data_obj->status) && $data_obj->status == 0) ? 'selected' : '' }}>{{__('global.inactive')}}</option>
                                    </select>
                                </div>

                                {{-- Actions --}}
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary btn-save text-white shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> {{__('global.save')}} Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Function to handle image click and preview
            $('#type-image-btn').on('click', function() {
                $('#type-image').click();
            });

            $('#type-image').on('change', function(e) {
                const file = e.target.files[0];
                const $preview = $('#type-image-preview');
                const $placeholder = $('#no-image-placeholder');
                const $nameInput = $('#type-image-name');

                if (file) {
                    $nameInput.val(file.name);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $preview.attr('src', e.target.result).removeClass('d-none');
                        $placeholder.addClass('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    $nameInput.val('');
                    $preview.addClass('d-none');
                    $placeholder.removeClass('d-none');
                }
            });

            // Assuming handleFormSubmit is a global function you have defined elsewhere
            if (typeof handleFormSubmit === "function") {
                handleFormSubmit('#form-type');
            }
        });
    </script>
    @endpush
</x-app-layout>