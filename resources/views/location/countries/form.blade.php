<div class="py-1 px-4">
    <div class="text-center mb-2">
        <div class="bg-light d-inline-block p-1 rounded-circle mb-1">
            <i class="fa-solid {{ isset($form->id) ? 'fa-pencil-square' : 'fa-plus-circle' }} fs-3 text-primary"></i>
        </div>
        <p class="text-muted small">
            {{ isset($form->id) ? 'Modify the information for ' . $form->name : 'Please fill in the details to register a new country.' }}
        </p>
    </div>

    <form action="{{ $action }}" method="POST" id="country-form" class="ajax-form">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-bold text-dark small text-uppercase">Country Name</label>
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