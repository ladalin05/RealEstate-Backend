<div class="py-1 px-4 position-relative">

    <form action="{{ $action }}" method="POST" id="amenity-form" class="ajax-form">
        @csrf

        {{-- English Name --}}
        <div class="mb-3">
            <label for="name_en" class="form-label">
                {{ __('global.name_en') }} <span class="text-danger">*</span>
            </label>
            <input type="text" name="name_en" id="name_en"
                class="form-control form-control-modern"
                value="{{ old('name_en', $form->name_en ?? '') }}"
                placeholder="e.g. WiFi, Parking" required>
        </div>

        {{-- Khmer Name --}}
        <div class="mb-3">
            <label for="name_kh" class="form-label">
                {{ __('global.name_kh') }}
            </label>
            <input type="text" name="name_kh" id="name_kh"
                class="form-control form-control-modern"
                value="{{ old('name_kh', $form->name_kh ?? '') }}"
                placeholder="ឧ. វ៉ាយហ្វាយ">
        </div>

        {{-- Icon Field --}}
        <div class="mb-3">
            <label class="form-label">Icon (FontAwesome)</label>

            <div class="form-control mb-2 p-0 d-flex">
                <span class="input-group-text mx-2">
                    <i id="icon-preview" class="{{ old('icon', $form->icon ?? 'fas fa-icons') }}"></i>
                </span>
                <div class="flex-grow-1">
                    <button type="button"
                            class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center py-2 px-3"
                            role="iconpicker"
                            data-iconset="fontawesome6"
                            data-icon="{{ old('icon', $form->icon ?? 'fas fa-icons') }}"
                            data-search="true"
                            data-search-text="Search icons...">
                        <span class="text-muted">Click to browse...</span>
                    </button>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i> Popular: <code>fa-wifi</code>, <code>fa-heart</code>, <code>fa-star</code>
                        </small>
                    </div>
                </div>
            </div>

            {{-- Hidden input carries the actual icon class value on submit --}}
            <input type="hidden" name="icon" id="icon"
                   value="{{ old('icon', $form->icon ?? '') }}">
        </div>

        {{-- Status --}}
        <div class="mb-2">
            <label for="status" class="form-label fw-bold text-dark small text-uppercase">
                STATUS
            </label>
            <select name="status" id="status" class="form-select custom-select">
                <option value="1" {{ (old('status', $form->status ?? 1) == 1) ? 'selected' : '' }}>
                    🟢 Active
                </option>
                <option value="0" {{ (old('status', $form->status ?? 1) == 0) ? 'selected' : '' }}>
                    ⚪ Inactive
                </option>
            </select>
        </div>

        {{-- Footer --}}
        <div class="modal-footer px-0 pb-0 pt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>
            <button type="submit" class="btn btn-primary btn-save text-white shadow-sm">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                <span>Save Changes</span>
            </button>
        </div>

    </form>
</div>

<script src="{{ asset('assets/extend/bootstrap-iconpicker/dist/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
<script>
$(document).ready(function () {

    function initIconPicker() {
        const $btn = $('[role="iconpicker"]');

        // Destroy previous instance to avoid duplicate bindings on re-init
        if ($btn.data('iconpicker')) {
            $btn.iconpicker('destroy');
        }

        $btn.iconpicker({
            iconset:   'fontawesome6',
            search:    true,
            placement: 'bottom',
            align:     'left'
        });

        $btn.off('change.amenity').on('change.amenity', function (e) {
            $('#icon').val(e.icon);
            $('#icon-preview').attr('class', e.icon);
        });
    }

    // Run on first load
    initIconPicker();

    // Re-init each time the modal is shown (AJAX reload)
    $(document).on('shown.bs.modal', function () {
        initIconPicker();
    });

    // Wire up global ajax-form handler if available
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#amenity-form');
    }

});
</script>