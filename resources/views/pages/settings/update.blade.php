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
                                <div class="row">
                                    <div class="col-md-6">
                                        <legend class="float-none w-auto px-1 fs-5">General Settings</legend>
                                        <fieldset class="border p-3 mb-4">
                                            <div class="mb-3">
                                                <label for="web_name" class="form-label">Website Name</label>
                                                <input type="text" class="form-control @error('web_name') is-invalid @enderror" id="web_name" name="web_name" value="{{ old('web_name', $settings->web_name ?? '') }}" required>
                                                @error('web_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="web_logo" class="form-label">Website Logo</label>
                                                <input type="file" class="form-control @error('web_logo') is-invalid @enderror" id="web_logo" name="web_logo"   accept="image/*">
                                                
                                                <div class="form-group row my-3">
                                                    <div class="col-sm-8">
                                                        <img id="web_logo_preview" src="{{ $settings->web_logo != null ? asset($settings->web_logo) : '#' }}" style="max-width:150px; {{ $settings->web_logo != null ? '' : 'display:none;' }}" class="img-thumbnail">
                                                    </div>
                                                </div>
                                                @error('web_logo') <div class="invalid-feedback">{{ $message }} </div>@enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="favicon" class="form-label">Favicon</label>
                                                <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon" name="favicon" value="{{$settings->favicon}}" accept="image/*">
                                                <div class="form-group row my-3">
                                                    <div class="col-sm-8">
                                                        <img id="favicon_preview" src="{{ $settings->favicon != null ? asset($settings->favicon) : '#' }}" style="max-width:150px; {{ $settings->favicon != null ? '' : 'display:none;' }}" class="img-thumbnail">
                                                    </div>
                                                </div>
                                                @error('favicon') <div class="invalid-feedback">{{ $message }} </div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="web_email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('web_email') is-invalid @enderror" id="web_email" name="web_email" value="{{ old('web_email', $settings->web_email ?? '') }}">
                                                @error('web_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" style="height: 120px !important; width: 100%;" id="description" name="description" rows="3">{{ old('description', $settings->description ?? '') }}</textarea>
                                                <div class="form-text">Recommended length: 150-160 characters.</div>
                                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <legend class="float-none w-auto px-1 fs-5">Contact Us</legend>
                                        <fieldset class="border p-3 mb-4">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"   accept="image/*">
                                                
                                                <div class="form-group row my-3">
                                                    <div class="col-sm-8">
                                                        <img id="image_preview" src="{{ $user_info->image != null ? asset($user_info->image) : '#' }}" style="max-width:150px; {{ $user_info->image != null ? '' : 'display:none;' }}" class="img-thumbnail">
                                                    </div>
                                                </div>
                                                @error('image') <div class="invalid-feedback">{{ $message }} </div>@enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="contact_email" class="form-label">Contact Email</label>
                                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ $user_info->contact_email }}">
                                                @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                                <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" placeholder="+855 123 456 789" value="{{ $user_info->contact_phone }}">
                                                @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="contact_address" class="form-label">Contact Address</label>
                                                <input type="text" class="form-control @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" value="{{ $user_info->contact_address }}">
                                                @error('contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="location" class="form-label">Location</label>
                                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ $user_info->contact_location }}">
                                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </fieldset>
                                        <legend class="float-none w-auto px-1 fs-5">Social Links</legend>
                                        <fieldset class="border p-3 mb-4">
                                            <div class="mb-3">
                                                <label for="facebook" class="form-label">Facebook URL</label>
                                                <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ $user_info->facebook ?? '' }}">
                                                @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="instagram" class="form-label">Instagram URL</label>
                                                <input type="url" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ $user_info->instagram ?? '' }}">
                                                @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="twitter" class="form-label">X (Twitter) URL</label>
                                                <input type="url" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ $user_info->twitter ?? '' }}">
                                                @error('twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="youtube" class="form-label">Youtube URL</label>
                                                <input type="url" class="form-control @error('youtube') is-invalid @enderror" id="youtube" name="youtube" value="{{ $user_info->youtube ?? '' }}">
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