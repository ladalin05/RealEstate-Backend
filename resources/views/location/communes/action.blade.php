
<div class="d-flex gap-2">
    <a
        href="{{route('location.communes.edit', ['id' => $row->id])}}"
        class="btn btn-success btn-sm text-white"
        onclick="editCommunes(event)">
        <i class="ph ph-pencil-simple me-1"></i>
        Edit
    </a>

    <button type="button"
        class="btn btn-danger btn-sm data_remove"
        data-url="{{route('location.communes.deleted', $row->id)}}"
        onclick="deleteLocation(event)">

        <i class="fa fa-trash"></i>

    </button>

</div>
<script>


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