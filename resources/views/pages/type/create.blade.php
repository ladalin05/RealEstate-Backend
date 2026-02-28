<x-app-layout>
    <style>
        :root {
            --primary-accent: #e63946;
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

        .btn-back {
            color: var(--primary-accent);
            font-weight: 600;
            transition: transform 0.2s ease;
        }
        
        .btn-back:hover {
            color: #d62828;
            transform: translateX(-5px);
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
            box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.1);
        }

        .image-preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            background: #fff;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 150px;
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
    </style>

    <div class="content mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('type.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h3 class="h4 mb-0 text-gray-800">Create Type</h3>
                        
                    </div>
                    
                    <div class="card card-modern">
                        {{-- Body --}}
                        <div class="card-body p-3">
                            <form method="POST" action="{{ route('type.add') }}" id="form-type" enctype="multipart/form-data">
                                @csrf

                                {{-- Name Field --}}
                                <div class="mb-3">
                                    <label for="type_name" class="form-label">
                                        {{__('global.type_name')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="type_name" 
                                           id="type_name" 
                                           value="{{ old('type_name') }}" 
                                           class="form-control form-control-modern" 
                                           placeholder="e.g. Electronic Devices">
                                </div>

                                {{-- Image Field --}}
                                <div class="mb-3">
                                    <label class="form-label">{{__('global.type_image')}}</label>

                                    <div class="input-group mb-2">
                                        <input type="file" name="type_image" id="type-image" class="d-none" accept="image/*">
                                        <input type="text" id="type-image-name" readonly 
                                               class="form-control form-control-modern bg-white" 
                                               placeholder="No file chosen">
                                        <button class="btn btn-dark px-4" type="button" id="type-image-btn">Browse</button>
                                    </div>

                                    <small class="text-muted">
                                        <i class="fa-solid fa-circle-info me-1"></i> Recommended: 600x400px
                                    </small>

                                    <div class="image-preview-container p-3 mt-2">
                                        <img src="#" 
                                             id="type-image-preview" 
                                             class="img-fluid rounded d-none" 
                                             style="max-height: 120px;">
                                        
                                        <div id="no-image-placeholder" class="text-muted text-center">
                                            <i class="fa-regular fa-image fa-3x mb-2 d-block"></i>
                                            <span>Image Preview</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Field --}}
                                <div class="mb-4">
                                    <label for="status" class="form-label">{{__('global.status')}}</label>
                                    <select class="form-select form-control-modern" name="status" id="status">
                                        <option value="1" selected>{{__('global.active')}}</option>
                                        <option value="0">{{__('global.inactive')}}</option>
                                    </select>
                                </div>

                                {{-- Actions --}}
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-save text-white shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> {{__('global.save')}}
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

            if (typeof handleFormSubmit === "function") {
                handleFormSubmit('#form-type');
            }

        });
    </script>
    @endpush

</x-app-layout>