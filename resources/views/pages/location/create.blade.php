<form id="addLocation" class="modern-modal-form" action="{{ route('location.add') }}" method="POST" >
    @csrf
    <div class="modal-body p-4">
        <div class="mb-4">
            <label for="locationName" class="form-label fw-bold text-secondary small">LOCATION NAME</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-geo-alt-fill text-indigo"></i>
                </span>
                <input type="text" id="locationName" name="name" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="e.g. Downtown Branch" required>
            </div>
        </div>

        <div class="mb-2">
            <label for="locationStatus" class="form-label fw-bold text-secondary small">STATUS</label>
            <select name="status" id="locationStatus" class="form-select custom-select">
                <option value="1">🟢 Active</option>
                <option value="0">⚪ Inactive</option>
            </select>
        </div>
    </div>

    <div class="modal-footer border-0 bg-light-subtle p-1">
        <button type="button" class="btn btn-warning text-decoration-none text-white fw-semibold me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" onclick="handleLocationSubmit(event)" class="btn btn-primary text-white shadow-sm">
            Save Location
        </button>
    </div>
</form>
<script>
    function handleLocationSubmit(e) {
        e.preventDefault();
        ajaxSubmit('#addLocation');
    }
</script>