<div class="py-1 px-4">
    <div class="text-center mb-2">
        <div class="bg-light d-inline-block p-1 rounded-circle mb-1">
            <i class="fa-solid {{ isset($form->id) ? 'fa-pencil-square' : 'fa-plus-circle' }} fs-3 text-primary"></i>
        </div>
        <p class="text-muted small">
            {{ isset($form->id) ? 'Modify the information for ' . $form->name : 'Please fill in the details to register a new city.' }}
        </p>
    </div>

    <form action="{{ $action }}" method="POST" id="city-form" class="ajax-form">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold text-uppercase small">Country</label>
            <select name="country_id" id="country" class="form-select @error('country_id') is-invalid @enderror" required>
                <option value="">-- Select Country --</option>
                @if(isset($form->country_id))
                    <option value="{{ $form->country_id }}" selected>{{ $form?->country?->name }}</option>
                @endif
            </select>
            @error('country_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="name" class="form-label fw-bold text-dark small text-uppercase">City Name</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-globe"></i>
                </span>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                    placeholder="e.g. United Kingdom"
                    value="{{ old('name', $form->name ?? '') }}"
                    required
                >
            </div>
            @error('name')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div><div class="mb-3">
            <label for="image" class="form-label fw-bold text-dark small text-uppercase">City Thumbnail</label>
            <div class="d-flex align-items-center gap-3">
                <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; overflow: hidden;">
                    @if(isset($form->image))
                        <img id="preview-img" src="{{ asset('storage/' . $form->image) }}" class="img-fluid object-fit-cover" style="height: 100%;">
                    @else
                        <img id="preview-img" src="https://via.placeholder.com/80?text=No+Image" class="img-fluid object-fit-cover">
                    @endif
                </div>
                <div class="flex-grow-1">
                    <input type="file" name="image" id="image-input" class="form-control form-control-sm @error('image') is-invalid @enderror" accept="image/*">
                    <div class="form-text mt-1 small">Recommended: Square image, max 2MB.</div>
                </div>
            </div>
            @error('image')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-2">
            <label for="status" class="form-label fw-bold text-dark small text-uppercase">STATUS</label>
            <select name="status" id="status" class="form-select custom-select">
                <option value="1" {{ isset($form->status) && $form->status == 1 ? 'selected' : ''}}>🟢 Active</option>
                <option value="0" {{ isset($form->status) && $form->status == 0 ? 'selected' : ''}}>⚪ Inactive</option>
            </select>
        </div>

        <div class="row g-2 mt-4 justify-content-end">
            <div class="col-sm-2">
                <button type="button" class="btn btn-light w-100 py-2 text-muted fw-semibold" data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                    <i class="bi bi-save2 me-2"></i> 
                    {{ isset($form->id) ? 'Save Changes' : 'Create Country' }}
                </button>
            </div>
        </div>
    </form>
</div>

@include('location.script')
<script>
    document.getElementById('image-input').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    }
</script>