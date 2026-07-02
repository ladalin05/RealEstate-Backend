<div class="py-1 px-4">

    <form action="{{ $action }}" method="POST" id="form-blog-category" class="ajax-form">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label for="name_en" class="form-label">
                Category Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="name_en"
                   id="name_en"
                   class="form-control form-control-modern"
                   value="{{ old('name_en', $form->name_en ?? '') }}"
                   placeholder="e.g. Travel Tips"
                   required>
        </div>
        <div class="mb-3">
            <label for="name_km" class="form-label">
                Category Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="name_km"
                   id="name_km"
                   class="form-control form-control-modern"
                   value="{{ old('name_km', $form->name_km ?? '') }}"
                   placeholder="e.g. Travel Tips"
                   required>
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
                   placeholder="e.g. travel-tips"
                   required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description"
                      id="description"
                      class="form-control form-control-modern"
                      rows="4"
                      placeholder="Short description of this category">{{ old('description', $form->description ?? '') }}</textarea>
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
            <button type="submit" class="btn btn-primary btn-save text-white shadow-sm" id="blog-category-submit-btn">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                <span id="saveBtnText">Save Changes</span>
            </button>
        </div>

    </form>
</div>

<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────────
       Auto-slug from Name (create mode only)
    ───────────────────────────────────────────── */
    let slugManuallyEdited = {{ !empty($form->slug ?? null) ? 'true' : 'false' }};

    $('#name_en').on('input', function () {
        if (slugManuallyEdited) return;
        const slug = $(this).val()
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });

    $('#slug').on('input', function () {
        slugManuallyEdited = true;
    });

    /* ─────────────────────────────────────────────
       External ajax-form handler (if defined globally)
    ───────────────────────────────────────────── */
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#form-blog-category');
    }

});
</script>