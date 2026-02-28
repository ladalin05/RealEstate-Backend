<form id="editLoccation"
      class="modern-modal-form"
      action="{{ route('location.edit', $location->id) }}"
      method="POST">

    @csrf

    <input type="hidden" name="id" id="edit_id" value="{{ $location->id }}">

    <div class="modal-body p-4">

        <!-- LOCATION NAME -->
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary small">
                EDIT LOCATION NAME
            </label>

            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-pencil-square text-primary"></i>
                </span>

                <input type="text"
                       name="name"
                       id="edit_locationName"
                       class="form-control border-start-0 ps-0"
                       value="{{ $location->name }}"
                       required>
            </div>
        </div>

        <!-- STATUS -->
        <div class="mb-2">
            <label class="form-label fw-bold text-secondary small">
                STATUS
            </label>

            <select name="status"
                    id="edit_locationStatus"
                    class="form-select">

                <option value="1"
                    {{ $location->status == 1 ? 'selected' : '' }}>
                    🟢 Active
                </option>

                <option value="0"
                    {{ $location->status == 0 ? 'selected' : '' }}>
                    ⚪ Inactive
                </option>

            </select>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="modal-footer border-0 bg-light-subtle p-1">

        <button type="button"
                class="btn btn-warning text-white fw-semibold me-3"
                data-bs-dismiss="modal">
            Cancel
        </button>

        <!-- ✅ NO onclick -->
        <button type="button" onclick="handleLocationSubmit(event)"
                class="btn btn-primary text-white shadow-sm px-4 update-location-btn">
            <i class="bi bi-check-lg me-1"></i>
            Update Changes
        </button>

    </div>

</form>
<script>
    function handleLocationSubmit(e) {
        e.preventDefault();
        ajaxSubmit('#editLoccation');
    }
</script>