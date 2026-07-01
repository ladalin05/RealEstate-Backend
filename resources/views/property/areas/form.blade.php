<div class="py-1 px-4">

    <form action="{{ $action }}" method="POST" id="area-form" class="ajax-form">
        @csrf

        {{-- Province --}}
        <div class="mb-3">
            <label class="form-label fw-bold text-uppercase small">Province</label>
            <select name="province_id" id="province" class="form-select @error('province_id') is-invalid @enderror" required>
                <option value="">-- Select Province --</option>
                @if(isset($form->province_id))
                    <option value="{{ $form->province_id }}" selected>{{ $form?->province?->name }}</option>
                @endif
            </select>
            @error('province_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- District --}}
        <div class="mb-3">
            <label class="form-label fw-bold text-uppercase small">District</label>
            <select name="district_id" id="district" class="form-select @error('district_id') is-invalid @enderror" required>
                <option value="">-- Select District --</option>
                @if(isset($form->district_id))
                    <option value="{{ $form->district_id }}" selected>{{ $form?->district?->name }}</option>
                @endif
            </select>
            @error('district_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Commune --}}
        <div class="mb-3">
            <label class="form-label fw-bold text-uppercase small">Commune</label>
            <select name="commune_id" id="commune" class="form-select">
                <option value="">-- Select Commune --</option>
                @if(isset($form->commune_id))
                    <option value="{{ $form->commune_id }}" selected>{{ $form?->commune?->name }}</option>
                @endif
            </select>
            @error('commune_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">
                Area Name <span class="text-danger">*</span>
                <small class="text-muted fw-normal">(auto-generated, editable)</small>
            </label>
            <input type="text" name="name_en" id="name_en" class="form-control form-control-modern @error('name_en') is-invalid @enderror"
                   value="{{ old('name_en', $form->name_en ?? '') }}" placeholder="e.g. Chamkarmon, Phnom Penh" required>
            @error('name_en')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Name Khmer --}}
        <div class="mb-3">
            <label for="name_km" class="form-label">
                Area Name Khmer <span class="text-danger">*</span>
                <small class="text-muted fw-normal">(auto-generated, editable)</small>
            </label>
            <input type="text" name="name_km" id="name_km" class="form-control form-control-modern @error('name_km') is-invalid @enderror"
                   value="{{ old('name_km', $form->name_km ?? '') }}" placeholder="e.g. Chamkarmon, Phnom Penh" required>
            @error('name_km')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>


        {{-- Slug --}}
        <div class="mb-3">
            <label for="slug" class="form-label">
                Slug <span class="text-danger">*</span>
                <small class="text-muted fw-normal">(auto-generated, editable)</small>
            </label>
            <input type="text"
                   name="slug"
                   id="slug"
                   class="form-control form-control-modern @error('slug') is-invalid @enderror"
                   value="{{ old('slug', $form->slug ?? '') }}"
                   placeholder="e.g. chamkarmon-phnom-penh"
                   required>
            @error('slug')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Image (MinIO async upload) --}}
        <div class="mb-3">
            <label class="form-label">Area Image</label>

            @php
                $hasImage  = !empty($form->image ?? null);
            @endphp

            <div class="image-upload-wrapper {{ $hasImage ? 'upload-done' : '' }}" id="area-image-wrapper">

                <div class="upload-spinner" id="area-image-spinner" style="display:none; align-items:center; padding: 6px 0;">
                    <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                    <span class="ms-2" style="font-size:.8rem;font-weight:600;color:#858796;">Uploading…</span>
                </div>

                <input type="file"   id="area_image_file" class="d-none" accept="image/*">
                <input type="hidden" name="image" id="area_image_path"
                       value="{{ old('image', $form->image ?? '') }}">

                <div class="input-group mb-2">
                    <input type="text"
                           id="area-image-name"
                           readonly
                           class="form-control form-control-modern bg-white"
                           placeholder="No file chosen"
                           value="{{ old('image', $form->image ?? '') }}">
                    <button class="btn btn-dark px-4" type="button" id="area-image-btn">Browse</button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        <i class="fa-solid fa-circle-info me-1"></i> Recommended: 600×400px
                    </small>
                    <span id="area-upload-badge"
                          class="badge {{ $hasImage ? 'bg-success' : 'bg-secondary d-none' }}">
                        {{ $hasImage ? 'Uploaded' : 'Pending' }}
                    </span>
                </div>

                <div class="image-preview-container p-1">
                    <img src="{{ $form->image ?? '#' }}"
                         id="area-image-preview"
                         alt="Preview"
                         class="img-fluid rounded {{ $hasImage ? '' : 'd-none' }}"
                         style="max-height: 120px;">

                    <div id="no-image-placeholder-area"
                         class="{{ $hasImage ? 'd-none' : '' }} text-muted text-center py-2">
                        <i class="fa-regular fa-image fa-3x mb-2 d-block"></i>
                        <span>Image Preview</span>
                    </div>
                </div>

                <div class="mt-2">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            id="area-image-remove"
                            style="{{ $hasImage ? '' : 'display:none' }}">
                        <i class="fa fa-times me-1"></i> Remove
                    </button>
                </div>

            </div>{{-- /.image-upload-wrapper --}}
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label fw-bold text-dark small text-uppercase">Status</label>
            <select name="status" id="status" class="form-select custom-select">
                <option value="1" {{ (old('status', $form->status ?? 1) == 1) ? 'selected' : '' }}>🟢 Active</option>
                <option value="0" {{ (old('status', $form->status ?? 1) == 0) ? 'selected' : '' }}>⚪ Inactive</option>
            </select>
        </div>

        {{-- Modal Footer --}}
        <div class="modal-footer px-0 pb-0 pt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-save text-white shadow-sm" id="area-submit-btn">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                <span id="saveBtnText">{{ isset($form->id) ? 'Save Changes' : 'Create Area' }}</span>
            </button>
        </div>

    </form>
</div>

@include('property.areas.script')

<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────────
       Config – injected by Laravel at render time
    ───────────────────────────────────────────── */
    const CSRF        = $('meta[name="csrf-token"]').attr('content');
    const UPLOAD_URL  = '{{ route("uploads.store") }}';
    const DESTROY_URL = '{{ route("uploads.destroy") }}';
    const UPLOAD_FOLDER = 'properties/areas';

    /* ─────────────────────────────────────────────
       Auto-name & auto-slug from selected locations
    ───────────────────────────────────────────── */
    let nameManuallyEdited = {{ !empty($form->name ?? null) ? 'true' : 'false' }};
    let slugManuallyEdited = {{ !empty($form->slug ?? null) ? 'true' : 'false' }};

    function buildNameFromSelects() {
        if (nameManuallyEdited) return;
        const communeText  = $('#commune').find(':selected').text();
        const provinceText = $('#province').find(':selected').text();
        const parts = [];
        if (communeText && $('#commune').val()) parts.push(communeText);
        if (provinceText && $('#province').val()) parts.push(provinceText);
        if (parts.length) {
            const built = parts.join(', ');
            $('#name_en').val(built);
            updateSlugFromName(built);
        }
    }

    function updateSlugFromName(value) {
        if (slugManuallyEdited) return;
        const slug = value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    }

    /* location.script triggers native 'change' on #province/#district/#commune
       when populating cascaded options — these listeners just react to that */
    $('#province, #district, #commune').on('change', buildNameFromSelects);

    $('#name_en').on('input', function () {
        nameManuallyEdited = true;
        updateSlugFromName($(this).val());
    });

    $('#slug').on('input', function () {
        slugManuallyEdited = true;
    });

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
    const $file    = $('#area_image_file');
    const $path    = $('#area_image_path');
    const $preview = $('#area-image-preview');
    const $ph      = $('#no-image-placeholder-area');
    const $wrapper = $('#area-image-wrapper');
    const $spinner = $('#area-image-spinner');
    const $nameBox = $('#area-image-name');
    const $remove  = $('#area-image-remove');
    const $btn     = $('#area-image-btn');
    const $badge   = $('#area-upload-badge');

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

        $spinner.css('display', 'flex');
        $wrapper.addClass('uploading').removeClass('upload-done');
        $btn.prop('disabled', true);
        $badge
            .text('Uploading…')
            .removeClass('bg-success bg-secondary bg-danger d-none')
            .addClass('bg-warning text-dark');

        try {
            const prevPath = $path.val();
            if (prevPath) deleteFromMinio(prevPath);

            const result = await uploadToMinio(file, UPLOAD_FOLDER);

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
            console.error('Area image upload failed:', err);
            alert('Upload failed. Please try again.');
            $wrapper.removeClass('uploading');
            $badge
                .text('Failed')
                .removeClass('bg-warning text-dark bg-secondary')
                .addClass('bg-danger');
        } finally {
            $spinner.css('display', 'none');
            $btn.prop('disabled', false);
            $(this).val('');
        }
    });

    /* ─────────────────────────────────────────────
       Remove image
    ───────────────────────────────────────────── */
    $remove.on('click', function () {
        const path = $path.val();
        if (!path) return;
        if (!confirm('Remove this image?')) return;

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
    $('#area-form').on('submit', function (e) {
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
        handleFormSubmit('#area-form');
    }

});
</script>