<div class="modal-content border-0 shadow-lg">
    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="countryModalLabel">
            {{ isset($country) ? 'Update Country' : 'Add New Country' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body p-4">
        <div class="text-center mb-4">
            <div class="bg-light d-inline-block p-3 rounded-circle mb-2">
                <i class="bi {{ isset($country) ? 'bi-pencil-square' : 'bi-plus-circle' }} fs-3 text-primary"></i>
            </div>
            <p class="text-muted small">
                {{ isset($country) ? 'Modify the information for ' . $country->name : 'Please fill in the details to register a new country.' }}
            </p>
        </div>

        <form action="{{ isset($country) ? route('countries.update', $country->id) : route('countries.store') }}" method="POST">
            @csrf
            @if(isset($country))
                @method('PUT')
            @endif

            <div class="mb-4">
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
                        value="{{ old('name', $country->name ?? '') }}"
                        required
                    >
                </div>
                @error('name')
                    <div class="text-danger small mt-1">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="row g-2 mt-4">
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save2 me-2"></i> 
                        {{ isset($country) ? 'Save Changes' : 'Create Country' }}
                    </button>
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-light w-100 py-2 text-muted fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>