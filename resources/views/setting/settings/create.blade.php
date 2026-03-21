<x-app-layout>
    <style>
        :root {
            --primary-bg: #f8f9fa;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --accent-color: #4e73df;
        }

        body { background-color: var(--primary-bg); }

        .settings-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .card-header-custom {
            background: #fff;
            padding: 1.5rem;
            border-bottom: 1px solid #edf2f9;
        }

        .section-title {
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title i {
            width: 35px;
            height: 35px;
            background: rgba(78, 115, 223, 0.1);
            color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 12px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1px solid #d1d9e6;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
        }

        .preview-container {
            background: #f1f5f9;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 10px;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-thumbnail-custom {
            max-width: 120px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-save {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            letter-spacing: 0.5px;
            transition: transform 0.2s;
        }

        .btn-save:hover { transform: translateY(-2px); }
    </style>

    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    
                    <div class="card settings-card">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold">Website Configuration</h4>
                            <span class="badge bg-soft-primary text-primary">System Settings</span>
                        </div>

                        <div class="card-body p-4 p-lg-5">
                            <form action="{{ route('settings.settings.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <h5 class="section-title"><i class="fa-regular fa-gear"></i> General Settings</h5>
                                        
                                        <div class="mb-4">
                                            <label for="web_name" class="form-label">Website Name</label>
                                            <input type="text" class="form-control @error('web_name') is-invalid @enderror" id="web_name" name="web_name" placeholder="e.g. My Awesome Brand" required>
                                            @error('web_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <label class="form-label">Website Logo</label>
                                                <input type="file" class="form-control" id="web_logo" name="web_logo" accept="image/*">
                                                <div class="preview-container mt-2">
                                                    <img id="web_logo_preview" src="#" style="display:none;" class="img-thumbnail-custom">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Favicon</label>
                                                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                                                <div class="preview-container mt-2">
                                                    <img id="favicon_preview" src="#" style="display:none;" class="img-thumbnail-custom">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="web_email" class="form-label">System Email</label>
                                            <input type="email" class="form-control @error('web_email') is-invalid @enderror" id="web_email" name="web_email">
                                        </div>

                                        <div class="mb-0">
                                            <label for="description" class="form-label">Meta Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Brief site summary..."></textarea>
                                            <div class="form-text mt-2 text-muted small">SEO Best Practice: 150-160 characters.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="section-title"><i class="fa-regular fa-address-book"></i> Contact Details</h5>
                                        
                                        <div class="mb-4">
                                            <label for="contact_email" class="form-label">Public Contact Email</label>
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="support@brand.com">
                                        </div>

                                        <div class="mb-4">
                                            <label for="contact_phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone" placeholder="+855 123 456 789">
                                        </div>

                                        <div class="mb-5">
                                            <label for="location" class="form-label">Business Location</label>
                                            <input type="text" class="form-control" id="location" name="location" placeholder="City, Country">
                                        </div>

                                        <h5 class="section-title"><i class="fa-regular fa-share-nodes"></i> Social Profiles</h5>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-brands fa-facebook-f text-primary"></i></span>
                                                    <input type="url" name="facebook" class="form-control" placeholder="Facebook">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-brands fa-instagram text-danger"></i></span>
                                                    <input type="url" name="instagram" class="form-control" placeholder="Instagram">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-brands fa-twitter"></i></span>
                                                    <input type="url" name="twitter" class="form-control" placeholder="X / Twitter">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-brands fa-youtube text-danger"></i></span>
                                                    <input type="url" name="youtube" class="form-control" placeholder="YouTube">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5 opacity-10">

                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light me-3 px-4">Discard Changes</button>
                                    <button type="submit" class="btn btn-success btn-save shadow-sm">
                                        Update Settings
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
            // Your existing JavaScript logic remains compatible with this UI
            $(document).ready(function() {
                function setupImageUpload(inputId, previewId) {
                    $(inputId).on('change', function(e) {
                        const file = e.target.files[0];
                        const preview = $(previewId);
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                preview.attr('src', event.target.result).fadeIn();
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
                setupImageUpload('#web_logo', '#web_logo_preview');
                setupImageUpload('#favicon', '#favicon_preview');

                // Auto-formatting for phone remains same
                $('#contact_phone').on('input', function() {
                    let numbers = $(this).val().replace(/\D/g, '').substring(0, 12);
                    let formatted = numbers.length >= 1 ? '+' + numbers.substring(0, 3) : '';
                    if (numbers.length >= 4) formatted += ' ' + numbers.substring(3, 6);
                    if (numbers.length >= 7) formatted += ' ' + numbers.substring(6, 9);
                    if (numbers.length >= 10) formatted += ' ' + numbers.substring(9, 12);
                    $(this).val(formatted);
                });
            });
        </script>
    @endpush
</x-app-layout>