
<div class="d-flex gap-2">
    <button type="button"
        class="btn btn-success btn-sm text-white"
        data-url="{{route('location.edit', $row->id)}}"
        onclick="editLocation(event)">
        <i class="ph ph-pencil-simple me-1"></i>
        Edit
    </button>

    <button type="button"
        class="btn btn-danger btn-sm data_remove"
        data-url="{{route('location.deleted', $row->id)}}"
        onclick="deleteLocation(event)">

        <i class="fa fa-trash"></i>

    </button>

</div>
<script>
    /* ================= EDIT LOCATION ================= */
    function editLocation(e) {
        e.preventDefault();
        let url = e.currentTarget.getAttribute('data-url');
        if (!url) {
            console.error("Edit URL not found");
            return;
        }
        $.ajax({
            url: url,
            type: 'GET',
            success: function (res) {
                $('#action-form').html('').removeClass('was-validated');
                if (res.status === 'success') {
                    $('#action-modal .modal-title').text(res.title);
                    $('#action-form').html(res.html);
                    $('#action-form').attr('action', url);
                    $('#action-modal').modal('show');
                }
            },
            error: function () {
                errorAlert('Failed to load edit form');
            }
        });
    }


    /* ================= DELETE LOCATION ================= */
    function deleteLocation(e) {
        e.preventDefault();
        let url = e.currentTarget.getAttribute('data-url');
        if (!url) {
            console.error("Delete URL not found");
            return;
        }
        $.ajax({
            url: url,
            type: 'GET',
            success: function (res) {
                if (res.status === 'success') {
                    successAlert(res.message);
                    setTimeout(function () {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        } else {
                            location.reload();
                        }
                    }, 2000);
                } else {
                    errorAlert(res.message || 'Failed to delete location');
                }
            },
            error: function () {
                errorAlert('Failed to delete location');
            }
        });
    }
</script>