<div class="py-1 px-4">

    <form action="{{ $action }}" method="POST" id="city-form" class="ajax-form">
        @csrf

        {{-- Name Field --}}
        <div class="mb-3">
            <label for="type_name" class="form-label">{{__('global.type_name')}} <span class="text-danger">*</span></label>
            <input type="text" name="type_name" id="type_name" 
                class="form-control form-control-modern" value="{{ $form->type_name }}"
                placeholder="e.g. Electronic Devices" required>
        </div>

        {{-- Image Field --}}
        <div class="mb-2">
            <label class="form-label">{{__('global.type_image')}}</label>
            <div class="input-group mb-2">
                <input type="file" name="type_image" id="type-image" class="d-none" accept="image/*">
                <input type="text" id="type-image-name" readonly 
                        class="form-control form-control-modern bg-white" 
                        placeholder="No file chosen" 
                        value="{{ $form->type_image ?? '' }}">
                <button class="btn btn-dark px-4" type="button" id="type-image-btn">Browse</button>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Recommended: 600x400px</small>
            </div>

            <div class="image-preview-container p-1">
                @php $hasImage = isset($form->type_image) && $form->type_image; @endphp
                <img src="{{ $hasImage ? asset('storage/'.$form->type_image) : '#' }}" 
                        id="type-image-preview" 
                        alt="Preview" 
                        class="img-fluid rounded {{ $hasImage ? '' : 'd-none' }}" 
                        style="max-height: 100px;">
                
                <div id="no-image-placeholder" class="{{ $hasImage ? 'd-none' : '' }} text-muted text-center">
                    <i class="fa-regular fa-image fa-3x mb-2 d-block"></i>
                    <span>Image Preview</span>
                </div>
            </div>
        </div>

        {{-- Status Field --}}
        <div class="mb-2">
            <label for="status" class="form-label fw-bold text-dark small text-uppercase">STATUS</label>
            <select name="status" id="status" class="form-select custom-select">
                <option value="1" {{ isset($form->status) && $form->status == 1 ? 'selected' : ''}}>🟢 Active</option>
                <option value="0" {{ isset($form->status) && $form->status == 0 ? 'selected' : ''}}>⚪ Inactive</option>
            </select>
        </div>

        {{-- Modal Footer Actions --}}
        <div class="modal-footer px-0 pb-0 pt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-save text-white shadow-sm">
                <i class="fa-solid fa-floppy-disk me-2"></i> <span id="saveBtnText">Save Changes</span>
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Function to handle image click and preview
        $('#type-image-btn').on('click', function() {
            $('#type-image').click();
        });

        $('#type-image').on('change', function(e) {
            const file = e.target.files[0];
            const $preview = $('#type-image-preview');
            const $placeholder = $('#no-image-placeholder');
            const $nameInput = $('#type-image-name');

            if (file) {
                $nameInput.val(file.name);
                const reader = new FileReader();
                reader.onload = function(e) {
                    $preview.attr('src', e.target.result).removeClass('d-none');
                    $placeholder.addClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                $nameInput.val('');
                $preview.addClass('d-none');
                $placeholder.removeClass('d-none');
            }
        });

        // Assuming handleFormSubmit is a global function you have defined elsewhere
        if (typeof handleFormSubmit === "function") {
            handleFormSubmit('#form-type');
        }
    });
</script>