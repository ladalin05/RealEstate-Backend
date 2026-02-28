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
                            <form action="{{ route('settings.general.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <legend class="float-none w-auto px-1 fs-5">General Settings</legend>
                                        <fieldset class="border p-3 mb-4">
                                            <div class="mb-3">
                                                <label for="web_name" class="form-label">Website Name</label>
                                                <input type="text" class="form-control @error('web_name') is-invalid @enderror" id="web_name" name="web_name" value="" required>
                                                @error('web_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="web_logo" class="form-label">Website Logo</label>
                                                <input type="file" class="form-control @error('web_logo') is-invalid @enderror" id="web_logo" name="web_logo" accept="image/*">
                                                
                                                <div class="form-group row my-3">
                                                    <div class="col-sm-8">
                                                        <img id="web_logo_preview" src="#" style="max-width:150px; display:none;" class="img-thumbnail">
                                                    </div>
                                                </div
                                                @error('web_logo') <div class="invalid-feedback">{{ $message }} </div>@enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="favicon" class="form-label">Favicon</label>
                                                <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon" name="favicon" accept="image/*">
                                                <div class="form-group row my-3">
                                                    <div class="col-sm-8">
                                                        <img id="favicon_preview" src="#" style="max-width:150px; display:none;" class="img-thumbnail">
                                                    </div>
                                                </div
                                                @error('favicon') <div class="invalid-feedback">{{ $message }} </div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="web_email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('web_email') is-invalid @enderror" id="web_email" name="web_email" value="">
                                                @error('web_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3"></textarea>
                                                <div class="form-text">Recommended length: 150-160 characters.</div>
                                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <legend class="float-none w-auto px-1 fs-5">Contact Us</legend>
                                        <fieldset class="border p-3 mb-4">
                                            <div class="mb-3">
                                                <label for="contact_email" class="form-label">Contact Email</label>
                                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="">
                                                @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                                <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" placeholder="+855 123 456 789" value="">
                                                @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="contact_address" class="form-label">Contact Address</label>
                                                <input type="text" class="form-control @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" value="">
                                                @error('contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="location" class="form-label">Location</label>
                                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="">
                                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </fieldset>
                                        <legend class="float-none w-auto px-1 fs-5">Social Links</legend>
                                        <fieldset class="border p-3 mb-4">
                                            <div class="mb-3">
                                                <label for="facebook" class="form-label">Facebook URL</label>
                                                <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="">
                                                @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="instagram" class="form-label">Instagram URL</label>
                                                <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="">
                                                @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="twitter" class="form-label">X (Twitter) URL</label>
                                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="">
                                                @error('twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="youtube" class="form-label">Youtube URL</label>
                                                <input type="url" class="form-control @error('youtube') is-invalid @enderror" id="youtube" name="youtube" value="">
                                                @error('youtube')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="d-grid justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-save me-2"></i> Save Settings
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