<x-app-layout>
    <style>
        .card-body label {
            color: #797979 ;
        }
    </style>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <h4 class="mb-0">Website Settings</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.general.update', ['id'=>$settings->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <h5 class="section-title"><i class="fa-regular fa-gear"></i> General Settings</h5>
                                        
                                        <div class="mb-4">
                                            <label for="web_name" class="form-label">Website Name</label>
                                            <input type="text"
                                                class="form-control @error('web_name') is-invalid @enderror"
                                                id="web_name"
                                                name="web_name"
                                                value="{{ old('web_name', $setting->web_name) }}"
                                                required>
                                            @error('web_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row mb-4">
                                            {{-- Logo --}}
                                            <div class="col-6">
                                                <label class="form-label">Website Logo</label>
                                                <input type="file" class="form-control" id="web_logo" name="web_logo">

                                                <div class="preview-container mt-2">
                                                    @if($setting->web_logo && file_exists(public_path('storage/'.$setting->web_logo)))
                                                        <img src="{{ asset('storage/'.$setting->web_logo) }}"
                                                            class="img-thumbnail-custom"
                                                            id="web_logo_preview">
                                                    @else
                                                        <img id="web_logo_preview" style="display:none;" class="img-thumbnail-custom">
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Favicon --}}
                                            <div class="col-6">
                                                <label class="form-label">Favicon</label>
                                                <input type="file" class="form-control" id="favicon" name="favicon">

                                                <div class="preview-container mt-2">
                                                    @if($setting->favicon && file_exists(public_path('storage/'.$setting->favicon)))
                                                        <img src="{{ asset('storage/'.$setting->favicon) }}"
                                                            class="img-thumbnail-custom"
                                                            id="favicon_preview">
                                                    @else
                                                        <img id="favicon_preview" style="display:none;" class="img-thumbnail-custom">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="web_email" class="form-label">System Email</label>
                                            <input type="email"
                                                class="form-control"
                                                id="web_email"
                                                name="web_email"
                                                value="{{ old('web_email', $setting->web_email) }}">
                                        </div>

                                        <div class="mb-0">
                                            <label for="description" class="form-label">Meta Description</label>
                                            <textarea class="form-control"
                                                    id="description"
                                                    name="description"
                                                    rows="4">{{ old('description', $setting->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="section-title"><i class="fa-regular fa-address-book"></i> Contact Details</h5>

                                        <div class="mb-4">
                                            <label class="form-label">Public Contact Email</label>
                                            <input type="email"
                                                class="form-control"
                                                name="contact_email"
                                                value="{{ old('contact_email', $setting->contact_email) }}">
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text"
                                                class="form-control"
                                                id="contact_phone"
                                                name="contact_phone"
                                                value="{{ old('contact_phone', $setting->contact_phone) }}">
                                        </div>

                                        <div class="mb-5">
                                            <label class="form-label">Business Location</label>
                                            <input type="text"
                                                class="form-control"
                                                name="location"
                                                value="{{ old('location', $setting->location) }}">
                                        </div>

                                        <h5 class="section-title"><i class="fa-regular fa-share-nodes"></i> Social Profiles</h5>

                                        <div class="row g-3">
                                            <div class="col-6">
                                                <input type="url" name="facebook" class="form-control"
                                                    value="{{ old('facebook', $setting->facebook) }}" placeholder="Facebook">
                                            </div>
                                            <div class="col-6">
                                                <input type="url" name="instagram" class="form-control"
                                                    value="{{ old('instagram', $setting->instagram) }}" placeholder="Instagram">
                                            </div>
                                            <div class="col-6">
                                                <input type="url" name="twitter" class="form-control"
                                                    value="{{ old('twitter', $setting->twitter) }}" placeholder="Twitter">
                                            </div>
                                            <div class="col-6">
                                                <input type="url" name="youtube" class="form-control"
                                                    value="{{ old('youtube', $setting->youtube) }}" placeholder="YouTube">
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
            $(document).ready(function() {
                function setupImageUpload(inputId, previewId) {
                    $(inputId).on('change', function(e) {
                        const file = e.target.files[0];
                        const preview = $(previewId);

                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                preview.attr('src', event.target.result);
                                preview.show();
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.attr('src', '#');
                            preview.hide();
                        }
                    });
                }

                    // Initialize both uploads
                setupImageUpload( '#web_logo', '#web_logo_preview');
                setupImageUpload( '#favicon', '#favicon_preview');
                setupImageUpload( '#image', '#image_preview');
                

                $('#contact_phone').on('input', function() {
                    let value = $(this).val();
                    let numbers = value.replace(/\D/g, '');
                    numbers = numbers.substring(0, 12);

                    let formatted = '';
                    if (numbers.length >= 1) formatted += '+' + numbers.substring(0, 3);
                    if (numbers.length >= 4) formatted += ' ' + numbers.substring(3, 6);
                    if (numbers.length >= 7) formatted += ' ' + numbers.substring(6, 9);
                    if (numbers.length >= 10) formatted += ' ' + numbers.substring(9, 12);

                    $(this).val(formatted);
                });

            });
        </script>
    @endpush
</x-app-layout>