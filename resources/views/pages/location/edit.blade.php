
<div class="modal-content">
    <form id="editForm">
        @csrf
        <input type="hidden" name="id" id="edit_id">
        <div class="modal-header bg-emerald-50">
            <h5 class="text-xl font-bold text-emerald-800">Edit Location</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Location Name</label>
                <input type="text" name="name" id="edit_name" value="{{ $location->name }}" class="form-control w-full" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="edit_status" class="form-select w-full">
                    <option value="1" {{$location->status == 1 ? 'selected' : ''}}>Active</option>
                    <option value="0" {{$location->status == 0 ? 'selected' : ''}}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="px-4 py-2 text-gray-600 font-medium" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 shadow-md transition">Update Changes</button>
        </div>
    </form>
</div>