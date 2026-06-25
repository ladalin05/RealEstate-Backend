<style>
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        border: 1px solid #0d6efd !important;
    }

    .ls-1 {
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    .btn-primary {
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }

    .spec-check {
        cursor: pointer;
    }

    /* Widen the modal so the multi-column layout has room to breathe */
    #agentModal .modal-dialog {
        max-width: 900px;
        width: 100%;
    }

    @media (min-width: 1200px) {
        #agentModal .modal-dialog {
            max-width: 1100px;
        }
    }
</style>

@php
    $specializationOptions = ['Residential', 'Commercial', 'Land', 'Industrial', 'Luxury', 'Rental'];
    $selectedSpecializations = isset($form->specializations)
        ? (is_array($form->specializations) ? $form->specializations : json_decode($form->specializations, true) ?? [])
        : [];

    $socialLinks = isset($form->social_links)
        ? (is_array($form->social_links) ? $form->social_links : json_decode($form->social_links, true) ?? [])
        : [];

    $hasImage  = !empty($form->profile_image ?? null);
    $imageUrl  = $hasImage ? $form->profile_image : null;
@endphp

<div class="modal-body p-4 py-1 bg-white">
    <form action="{{ $action }}" method="POST" id="form-agent" class="ajax-form">
        @csrf

        <div class="mb-4 border-bottom pb-3">
            <h5 class="fw-bold text-dark mb-1">Agent Information</h5>
        </div>

        {{-- Account Credentials --}}
        <div class="row g-3">
            <div class="col-12">
                <span class="small fw-bold text-uppercase text-muted ls-1">Account Credentials</span>
            </div>

            {{-- Profile Image (async MinIO upload, same pattern as Type form) --}}
            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase text-muted d-block">Profile Image</label>

                <div class="image-upload-wrapper {{ $hasImage ? 'upload-done' : '' }}" id="agent-image-wrapper">
                    <div class="d-flex align-items-start gap-3 flex-wrap">

                        {{-- Circular avatar preview --}}
                        <div class="image-preview-container p-1 d-flex align-items-center justify-content-center"
                             style="width:90px;height:90px;border-radius:50%;overflow:hidden;flex-shrink:0;">
                            <img src="{{ $imageUrl ?? '#' }}"
                                 id="agent-image-preview"
                                 alt="Preview"
                                 class="img-fluid {{ $hasImage ? '' : 'd-none' }}"
                                 style="width:100%;height:100%;object-fit:cover;">

                            <div id="agent-no-image-placeholder"
                                 class="{{ $hasImage ? 'd-none' : '' }} text-muted text-center">
                                <i class="fa-regular fa-user fa-2x"></i>
                            </div>
                        </div>

                        {{-- Controls --}}
                        <div class="flex-grow-1" style="min-width:240px;">

                            {{-- Upload spinner overlay (hidden by default, shown only during upload) --}}
                            <div class="upload-spinner" id="agent-image-spinner" style="display:none;align-items:center;padding:6px 0;">
                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                <span class="ms-2" style="font-size:.8rem;font-weight:600;color:#858796;">Uploading…</span>
                            </div>

                            {{-- Hidden inputs --}}
                            <input type="file" id="agent_image_file" class="d-none" accept="image/*">
                            <input type="hidden" name="profile_image" id="agent_image_path"
                                   value="{{ old('profile_image', $form->profile_image ?? '') }}">

                            {{-- Browse bar --}}
                            <div class="input-group mb-2">
                                <input type="text"
                                       id="agent-image-name"
                                       readonly
                                       class="form-control bg-white shadow-sm"
                                       placeholder="No file chosen"
                                       value="{{ old('profile_image', $form->profile_image ?? '') }}">
                                <button class="btn btn-dark px-4" type="button" id="agent-image-btn">
                                    {{ $hasImage ? 'Change Image' : 'Browse' }}
                                </button>
                            </div>

                            {{-- Hint + badge --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info me-1"></i> Square image recommended
                                </small>
                                <span id="agent-upload-badge"
                                      class="badge {{ $hasImage ? 'bg-success' : 'bg-secondary d-none' }}">
                                    {{ $hasImage ? 'Uploaded' : 'Pending' }}
                                </span>
                            </div>

                            {{-- Remove button --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    id="agent-image-remove"
                                    style="{{ $hasImage ? '' : 'display:none' }}">
                                <i class="fa fa-times me-1"></i> Remove
                            </button>
                        </div>

                    </div>
                </div>{{-- /.image-upload-wrapper --}}
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Email *</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-regular fa-envelope text-primary"></i></span>
                    <input type="email" name="email" class="form-control bg-white shadow-sm border-start-0 ps-0"
                           placeholder="agent@example.com" value="{{ $form->email ?? '' }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">
                    Password {{ isset($form) ? '' : '*' }}
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock text-primary"></i></span>
                    <input type="password" name="password" class="form-control bg-white shadow-sm border-start-0 ps-0"
                           placeholder="{{ isset($form) ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}"
                           {{ isset($form) ? '' : 'required' }}>
                </div>
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="row g-3 mt-1">
            <div class="col-12">
                <span class="small fw-bold text-uppercase text-muted ls-1">Personal Details</span>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">First Name *</label>
                <input type="text" name="first_name" class="form-control bg-white shadow-sm"
                       value="{{ $form->first_name ?? '' }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Last Name *</label>
                <input type="text" name="last_name" class="form-control bg-white shadow-sm"
                       value="{{ $form->last_name ?? '' }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase text-muted">Phone</label>
                <input type="text" name="phone" class="form-control bg-white shadow-sm"
                       placeholder="+85512345678" value="{{ $form->phone ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase text-muted">Gender</label>
                <select name="gender" class="form-select bg-white shadow-sm">
                    <option value="">-- Select --</option>
                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" {{ (isset($form->gender) && $form->gender == $value) ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase text-muted">Date of Birth</label>
                <input type="date" name="dob" class="form-control bg-white shadow-sm"
                       value="{{ $form->dob ?? '' }}">
            </div>
        </div>

        {{-- Professional Details --}}
        <div class="row g-3 mt-1">
            <div class="col-12">
                <span class="small fw-bold text-uppercase text-muted ls-1">Professional Details</span>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">License Number</label>
                <input type="text" name="license_number" class="form-control bg-white shadow-sm"
                       placeholder="LC-000000" value="{{ $form->license_number ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">License Expires</label>
                <input type="date" name="license_expires_at" class="form-control bg-white shadow-sm"
                       value="{{ $form->license_expires_at ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Years of Experience</label>
                <div class="input-group">
                    <input type="number" min="0" name="experience_years" class="form-control bg-white shadow-sm"
                           value="{{ $form->experience_years ?? 0 }}">
                    <span class="input-group-text bg-light small">Years</span>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Status</label>
                <select name="status" class="form-select bg-white shadow-sm">
                    @foreach(['pending' => 'Pending', 'active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended'] as $value => $label)
                        <option value="{{ $value }}" {{ (isset($form->status) ? $form->status == $value : $value == 'pending') ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase text-muted d-block">Specializations</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach($specializationOptions as $option)
                        <div class="form-check spec-check">
                            <input class="form-check-input" type="checkbox" name="specializations[]"
                                   value="{{ $option }}" id="spec_{{ $option }}"
                                   {{ in_array($option, $selectedSpecializations) ? 'checked' : '' }}>
                            <label class="form-check-label" for="spec_{{ $option }}">{{ $option }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Performance Metrics --}}
        <div class="col-12 mt-4">
            <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                <span class="small fw-bold text-primary text-uppercase ls-1 d-block mb-2">Performance Metrics</span>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-primary">Rating (0-5)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-warning border-0"><i class="bi bi-star-fill"></i></span>
                            <input type="number" step="0.01" min="0" max="5"
                                   name="rating" class="form-control border-0 shadow-sm"
                                   value="{{ $form->rating ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-primary">Review Count</label>
                        <input type="number" min="0" name="review_count" class="form-control border-0 shadow-sm"
                               value="{{ $form->review_count ?? 0 }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-primary">Total Sales</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-success border-0"><i class="bi bi-house-check"></i></span>
                            <input type="number" min="0" name="total_sales" class="form-control border-0 shadow-sm"
                                   value="{{ $form->total_sales ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-primary">Total Rentals</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-info border-0"><i class="bi bi-key"></i></span>
                            <input type="number" min="0" name="total_rentals" class="form-control border-0 shadow-sm"
                                   value="{{ $form->total_rentals ?? 0 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Social & Contact Links --}}
        <div class="row g-3 mt-3">
            <div class="col-12">
                <span class="small fw-bold text-uppercase text-muted ls-1">Social & Contact Links</span>
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">Facebook</label>
                <input type="url" name="social_links[facebook]" class="form-control bg-white shadow-sm"
                       placeholder="https://facebook.com/..." value="{{ $socialLinks['facebook'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">Telegram</label>
                <input type="text" name="social_links[telegram]" class="form-control bg-white shadow-sm"
                       placeholder="@username" value="{{ $socialLinks['telegram'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">WhatsApp</label>
                <input type="text" name="social_links[whatsapp]" class="form-control bg-white shadow-sm"
                       placeholder="+85512345678" value="{{ $socialLinks['whatsapp'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">Instagram</label>
                <input type="url" name="social_links[instagram]" class="form-control bg-white shadow-sm"
                       placeholder="https://instagram.com/..." value="{{ $socialLinks['instagram'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">LinkedIn</label>
                <input type="url" name="social_links[linkedin]" class="form-control bg-white shadow-sm"
                       placeholder="https://linkedin.com/in/..." value="{{ $socialLinks['linkedin'] ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">Website</label>
                <input type="url" name="social_links[website]" class="form-control bg-white shadow-sm"
                       placeholder="https://..." value="{{ $socialLinks['website'] ?? '' }}">
            </div>
        </div>

        {{-- Professional Bio --}}
        <div class="row g-3 mt-1">
            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase text-muted">Professional Bio</label>
                <textarea name="bio" rows="4" class="form-control bg-white shadow-sm"
                          placeholder="Write a brief professional summary...">{{ $form->bio ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save Profile
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────────
       Config – injected by Laravel at render time
    ───────────────────────────────────────────── */
    const CSRF          = $('meta[name="csrf-token"]').attr('content');
    const UPLOAD_URL    = '{{ route("uploads.store") }}';
    const DESTROY_URL   = '{{ route("uploads.destroy") }}';
    const MINIO_BASE    = '{{ rtrim(env("MINIO_ENDPOINT", ""), "/") }}/{{ env("MINIO_BUCKET", "") }}/';
    const UPLOAD_FOLDER = 'agents/profiles';

    /* ─────────────────────────────────────────────
       MinIO helpers
    ───────────────────────────────────────────── */
    function uploadToMinio(file, folder) {
        const fd = new FormData();
        fd.append('file', file);
        fd.append('folder', folder || UPLOAD_FOLDER);
        return $.ajax({
            url:          UPLOAD_URL,
            method:       'POST',
            data:         fd,
            processData:  false,
            contentType:  false,
            headers:      { 'X-CSRF-TOKEN': CSRF }
        }).then(function (res) { return res.data; });
    }

    function deleteFromMinio(path) {
        if (!path) return;
        $.ajax({
            url:         DESTROY_URL,
            method:      'DELETE',
            data:        JSON.stringify({ path: path }),
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF }
        });
    }

    /* ─────────────────────────────────────────────
       DOM refs
    ───────────────────────────────────────────── */
    const $file    = $('#agent_image_file');
    const $path    = $('#agent_image_path');
    const $preview = $('#agent-image-preview');
    const $ph      = $('#agent-no-image-placeholder');
    const $wrapper = $('#agent-image-wrapper');
    const $spinner = $('#agent-image-spinner');
    const $nameBox = $('#agent-image-name');
    const $remove  = $('#agent-image-remove');
    const $btn     = $('#agent-image-btn');
    const $badge   = $('#agent-upload-badge');

    /* ─────────────────────────────────────────────
       Browse trigger
    ───────────────────────────────────────────── */
    $btn.on('click', function () {
        $file.trigger('click');
    });

    /* ─────────────────────────────────────────────
       File selected → upload to MinIO
    ───────────────────────────────────────────── */
    $file.on('change', async function () {
        const file = this.files[0];
        if (!file) return;

        /* UI: uploading state — show spinner */
        $spinner.css('display', 'flex');
        $wrapper.addClass('uploading').removeClass('upload-done');
        $btn.prop('disabled', true);
        $badge
            .text('Uploading…')
            .removeClass('bg-success bg-secondary bg-danger d-none')
            .addClass('bg-warning text-dark');

        try {
            /* Delete old image from MinIO if replacing */
            const prevPath = $path.val();
            if (prevPath) deleteFromMinio(prevPath);

            const result = await uploadToMinio(file, UPLOAD_FOLDER);

            /* UI: success state */
            $path.val(result.public_url); 
            $nameBox.val(result.public_url); 
            $preview.attr('src', result.public_url).removeClass('d-none');
            $ph.addClass('d-none');
            $remove.show();
            $wrapper.removeClass('uploading').addClass('upload-done');
            $badge
                .text('Uploaded')
                .removeClass('bg-warning text-dark bg-secondary')
                .addClass('bg-success');
            $btn.text('Change Image');

        } catch (err) {
            console.error('Agent image upload failed:', err);
            alert('Upload failed. Please try again.');
            $wrapper.removeClass('uploading');
            $badge
                .text('Failed')
                .removeClass('bg-warning text-dark bg-secondary')
                .addClass('bg-danger');
        } finally {
            /* Always hide spinner when done */
            $spinner.css('display', 'none');
            $btn.prop('disabled', false);
            $(this).val(''); /* reset file input */
        }
    });

    /* ─────────────────────────────────────────────
       Remove image
    ───────────────────────────────────────────── */
    $remove.on('click', function () {
        const path = $path.val();
        if (!path) return;
        if (!confirm('Remove this profile image?')) return;

        deleteFromMinio(path);

        $path.val('');
        $nameBox.val('');
        $preview.attr('src', '#').addClass('d-none');
        $ph.removeClass('d-none');
        $remove.hide();
        $wrapper.removeClass('upload-done uploading');
        $badge
            .text('Pending')
            .removeClass('bg-success bg-danger bg-warning text-dark')
            .addClass('bg-secondary')
            .removeClass('d-none');
        $btn.text('Browse');
    });

    /* ─────────────────────────────────────────────
       Submit guard – block if upload still in flight
    ───────────────────────────────────────────── */
    $('#form-agent').on('submit', function (e) {
        if ($wrapper.hasClass('uploading')) {
            e.preventDefault();
            alert('Please wait — the image is still uploading.');
            return false;
        }
    });

    /* ─────────────────────────────────────────────
       External ajax-form handler (if defined globally)
    ───────────────────────────────────────────── */
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#form-agent');
    }

});
</script>