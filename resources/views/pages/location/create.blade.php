
<div class="modal-content">
    <form id="addForm">
        @csrf
        <div class="modal-header">
            <h5 class="text-xl font-bold text-gray-800">New Location</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Location Name</label>
                <input type="text" name="name" class="form-control w-full" placeholder="e.g. Downtown Branch" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="form-select w-full">
                    <option value="1">🟢 Active</option>
                    <option value="0">⚪ Inactive</option>
                </select>
            </div>
        </div>
        <div class="modal-footer bg-gray-50/50">
            <button type="button" class="px-4 py-2 text-gray-600 font-medium" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 shadow-md transition">Save Location</button>
        </div>
    </form>
</div>