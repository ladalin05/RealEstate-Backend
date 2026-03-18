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
<div class="modal-body p-4 py-1">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="ajax-form">
        @csrf

        <div class="d-flex align-items-center mb-2">
            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                <i class="bi bi-person-badge text-primary fs-5"></i>
            </div>
            <h6 class="mb-0 fw-bold">Company Profile</h6>
        </div>

        <div class="row g-2">
            <div class="col-md-7">
                <label class="form-label small fw-semibold text-muted">Full Name *</label>
                <input type="text" name="name" class="form-control border-0 bg-light py-2"
                    placeholder="e.g. Acme Corp" value="{{ $form->name ?? '' }}" required>
            </div>

            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted">Brand Logo</label>
                <div class="d-flex align-items-center gap-3">
                    @if(!empty($form->logo))
                        <div class="position-relative">
                            <img src="{{ asset('storage/'.$form->logo) }}" 
                                 class="rounded shadow-sm border" width="50" height="50" style="object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control form-control-sm border-0 bg-light">
                </div>
            </div>

            <hr class="my-4 opacity-50">

            <div class="col-12 mt-0 mb-1">
                <p class="small text-uppercase fw-bold text-primary ">Contact Information</p>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-0 bg-light py-2" 
                        value="{{ $form->email ?? '' }}">
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-telephone text-muted"></i></span>
                    <input type="text" name="phone" class="form-control border-0 bg-light py-2" 
                        value="{{ $form->phone ?? '' }}">
                </div>
            </div>

            <div class="col-md-12">
                <label class="form-label small fw-semibold text-muted">Website URL</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light"><i class="bi bi-globe text-muted"></i></span>
                    <input type="text" name="website" class="form-control border-0 bg-light py-2" 
                        placeholder="https://example.com" value="{{ $form->website ?? '' }}">
                </div>
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold text-muted">Physical Address</label>
                <input type="text" name="address" class="form-control border-0 bg-light py-2" 
                    value="{{ $form->address ?? '' }}">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold text-muted">Company Bio</label>
                <textarea name="description" rows="3" class="form-control border-0 bg-light" 
                    placeholder="Tell us a bit about the company...">{{ $form->description ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
            </button>
        </div>
    </form>
</div>