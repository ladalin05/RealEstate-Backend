<div class="py-1 px-4 position-relative">

    <form action="{{ $action }}" method="POST" id="feature-form" class="ajax-form">
        @csrf

        {{-- English Name --}}
        <div class="mb-3">
            <label for="name_en" class="form-label">
                {{ __('global.name_en') }} <span class="text-danger">*</span>
            </label>
            <input type="text" name="name_en" id="name_en"
                class="form-control form-control-modern"
                value="{{ $form->name_en ?? '' }}"
                placeholder="e.g. Swimming Pool, Gym" required>
        </div>

        {{-- Khmer Name --}}
        <div class="mb-3">
            <label for="name_kh" class="form-label">
                {{ __('global.name_kh') }}
            </label>
            <input type="text" name="name_kh" id="name_kh"
                class="form-control form-control-modern"
                value="{{ $form->name_kh ?? '' }}"
                placeholder="ឧ. អាងហែលទឹក">
        </div>

        {{-- Status --}}
        <div class="mb-2">
            <label for="status" class="form-label fw-bold text-dark small text-uppercase">
                STATUS
            </label>
            <select name="status" id="status" class="form-select custom-select">
                <option value="1" {{ isset($form->status) && $form->status == 1 ? 'selected' : '' }}>🟢 Active</option>
                <option value="0" {{ isset($form->status) && $form->status == 0 ? 'selected' : '' }}>⚪ Inactive</option>
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