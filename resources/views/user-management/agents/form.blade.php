<style>
    /* Soften the input look */
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        border: 1px solid #0d6efd !important;
    }

    /* Small labels for a modern look */
    .ls-1 {
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    /* Transition for buttons */
    .btn-primary {
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
</style>
<div class="modal-body p-4 py-1 bg-light-subtle">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="ajax-form">
        @csrf

        <div class="mb-4 border-bottom pb-3">
            <h5 class="fw-bold text-dark mb-1">Agent Information</h5>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">User Account *</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-primary"></i></span>
                    <select name="user_id" class="form-select select2 border-start-0 ps-0" required>
                        <option value="">-- Select User --</option>
                        @foreach(getUser() as $user)
                            <option value="{{ $user->id }}"
                                {{ (isset($form->user_id) && $form->user_id == $user->id) ? 'selected' : '' }}>
                                {{ $user->{'name_'.app()->getLocale()} }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Affiliated Agency</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-building text-primary"></i></span>
                    <select name="agency_id" class="form-select select2 border-start-0 ps-0">
                        <option value="">-- Select Agency --</option>
                        @foreach(getAgency() as $agency)
                            <option value="{{ $agency->id }}"
                                {{ (isset($form->agency_id) && $form->agency_id == $agency->id) ? 'selected' : '' }}>
                                {{ $agency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">License Number</label>
                <input type="text" name="license_number" class="form-control bg-white shadow-sm"
                       placeholder="LC-000000" value="{{ $form->license_number ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Years of Experience</label>
                <div class="input-group">
                    <input type="number" name="experience_years" class="form-control bg-white shadow-sm"
                           value="{{ $form->experience_years ?? '' }}">
                    <span class="input-group-text bg-light small">Years</span>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-primary">Performance Rating (0-5)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-warning border-0"><i class="bi bi-star-fill"></i></span>
                                <input type="number" step="0.1" min="0" max="5"
                                       name="rating" class="form-control border-0 shadow-sm"
                                       value="{{ $form->rating ?? 0 }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-primary">Total Sales Volume</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-success border-0"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" name="total_sales" class="form-control border-0 shadow-sm"
                                       value="{{ $form->total_sales ?? 0 }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <label class="form-label small fw-bold text-uppercase text-muted">Professional Bio</label>
                <textarea name="bio" rows="4" class="form-control bg-white shadow-sm" 
                          placeholder="Write a brief professional summary...">{{ $form->bio ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save Profile
            </button>
        </div>
    </form>
</div>