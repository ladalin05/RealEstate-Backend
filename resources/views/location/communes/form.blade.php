<div class="py-1 px-4">
    <div class="text-center mb-2">
        <div class="bg-light d-inline-block p-1 rounded-circle mb-1">
            <i class="fa-solid {{ isset($form->id) ? 'fa-pencil-square' : 'fa-plus-circle' }} fs-3 text-primary"></i>
        </div>
        <p class="text-muted small">
            {{ isset($form->id) ? 'Modify the information for ' . $form->name : 'Please fill in the details to register a new commune.' }}
        </p>
    </div>

    <form action="{{ $action }}" method="POST" id="commune-form" class="ajax-form">
        @csrf

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

        <div class="mb-4">
            <label for="name" class="form-label fw-bold text-dark small text-uppercase">Commune Name</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-geo-alt"></i>
                </span>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                    placeholder="e.g. Sangkat Toul Kork"
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

        <div class="mb-4">
            <label for="geom" class="form-label fw-bold text-dark small text-uppercase">Geometry (GeoJSON)</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-bounding-box"></i>
                </span>
                <textarea
                    name="geom"
                    id="geom"
                    rows="4"
                    class="form-control border-start-0 ps-0 font-monospace @error('geom') is-invalid @enderror"
                    placeholder='{ "type": "MultiPolygon", "coordinates": [...] }'
                    required
                >{{ old('geom', $form->geom ?? '') }}</textarea>
            </div>
            @error('geom')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
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
                    {{ isset($form->id) ? 'Save Changes' : 'Create Commune' }}
                </button>
            </div>
        </div>
    </form>
</div>

@include('location.script')