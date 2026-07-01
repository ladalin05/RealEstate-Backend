<div class="py-1 px-4">

    <form action="{{ $action }}" method="POST" id="form-category" class="ajax-form">
        @csrf

        {{-- Name (English) --}}
        <div class="mb-3">
            <label for="name_en" class="form-label">
                Name (English) <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="name_en"
                   id="name_en"
                   class="form-control form-control-modern"
                   value="{{ old('name_en', $form->name_en ?? '') }}"
                   placeholder="e.g. Apartment"
                   required>
        </div>

        {{-- Name (Khmer) --}}
        <div class="mb-3">
            <label for="name_km" class="form-label">
                Name (Khmer)
            </label>
            <input type="text"
                   name="name_km"
                   id="name_km"
                   class="form-control form-control-modern"
                   value="{{ old('name_km', $form->name_km ?? '') }}"
                   placeholder="e.g. អាផាតមិន">
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
                   class="form-control form-control-modern"
                   value="{{ old('slug', $form->slug ?? '') }}"
                   placeholder="e.g. apartment"
                   required>
        </div>

        {{-- Image (MinIO async upload) --}}
        <div class="mb-3">
            <label class="form-label">Category Image</label>

            @php
                $hasImage  = !empty($form->image ?? null);
                $minioBase = rtrim(env('MINIO_ENDPOINT', ''), '/') . '/' . env('MINIO_BUCKET', '') . '/';
                $imageUrl  = $hasImage ? $minioBase . $form->image : null;
            @endphp

            <div class="image-upload-wrapper {{ $hasImage ? 'upload-done' : '' }}" id="category-image-wrapper">

                {{-- Upload spinner overlay --}}
                <div class="upload-spinner" id="category-image-spinner" style="display:none; align-items:center; padding: 6px 0;">
                    <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                    <span class="ms-2" style="font-size:.8rem;font-weight:600;color:#858796;">Uploading…</span>
                </div>

                {{-- Hidden inputs --}}
                <input type="file"   id="category_image_file" class="d-none" accept="image/*">
                <input type="hidden" name="image" id="category_image_path"
                       value="{{ old('image', $form->image ?? '') }}">

                {{-- Browse bar --}}
                <div class="input-group mb-2">
                    <input type="text"
                           id="category-image-name"
                           readonly
                           class="form-control form-control-modern bg-white"
                           placeholder="No file chosen"
                           value="{{ old('image', $form->image ?? '') }}">
                    <button class="btn btn-dark px-4" type="button" id="category-image-btn">Browse</button>
                </div>

                {{-- Hint + badge --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        <i class="fa-solid fa-circle-info me-1"></i> Recommended: 600×400px
                    </small>
                    <span id="category-upload-badge"
                          class="badge {{ $hasImage ? 'bg-success' : 'bg-secondary d-none' }}">
                        {{ $hasImage ? 'Uploaded' : 'Pending' }}
                    </span>
                </div>

                {{-- Preview --}}
                <div class="image-preview-container p-1">
                    <img src="{{ $imageUrl ?? '#' }}"
                         id="category-image-preview"
                         alt="Preview"
                         class="img-fluid rounded {{ $hasImage ? '' : 'd-none' }}"
                         style="max-height: 120px;">

                    <div id="no-image-placeholder-category"
                         class="{{ $hasImage ? 'd-none' : '' }} text-muted text-center py-2">
                        <i class="fa-regular fa-image fa-3x mb-2 d-block"></i>
                        <span>Image Preview</span>
                    </div>
                </div>

                {{-- Remove button --}}
                <div class="mt-2">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            id="category-image-remove"
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
            <button type="submit" class="btn btn-primary btn-save text-white shadow-sm" id="category-submit-btn">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                <span id="saveBtnText">Save Changes</span>
            </button>
        </div>

    </form>
</div>

<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────────
       Config – injected by Laravel at render time
    ───────────────────────────────────────────── */
    const CSRF         = $('meta[name="csrf-token"]').attr('content');
    const UPLOAD_URL   = '{{ route("uploads.store") }}';
    const DESTROY_URL  = '{{ route("uploads.destroy") }}';
    const UPLOAD_FOLDER = 'properties/categories';

    /* ─────────────────────────────────────────────
       Auto-slug from Name (English)
    ───────────────────────────────────────────── */
    let slugManuallyEdited = {{ !empty($form->slug ?? null) ? 'true' : 'false' }};

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

    $('#name_en').on('input', function () {
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
    const $file    = $('#category_image_file');
    const $path    = $('#category_image_path');
    const $preview = $('#category-image-preview');
    const $ph      = $('#no-image-placeholder-category');
    const $wrapper = $('#category-image-wrapper');
    const $spinner = $('#category-image-spinner');
    const $nameBox = $('#category-image-name');
    const $remove  = $('#category-image-remove');
    const $btn     = $('#category-image-btn');
    const $badge   = $('#category-upload-badge');

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
            console.error('Category image upload failed:', err);
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
    $('#form-category').on('submit', function (e) {
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
        handleFormSubmit('#form-category');
    }

});
</script>