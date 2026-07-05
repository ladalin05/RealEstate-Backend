<div class="d-flex gap-1">
    {{-- View --}}
    <a href="{{ route('interaction.tour-schedules.show', $row->id) }}"
       class="btn btn-sm btn-info text-white"
       title="View">
        <i class="fa fa-eye"></i>
    </a>

    {{-- Confirm (only if pending) --}}
    @if($row->status === 'pending')
        <button type="button"
                class="btn btn-sm btn-success text-white btn-confirm-tour"
                data-id="{{ $row->id }}"
                title="Confirm">
            <i class="fa fa-check"></i>
        </button>

        {{-- Reject (only if pending) --}}
        <button type="button"
                class="btn btn-sm btn-danger text-white btn-reject-tour"
                data-id="{{ $row->id }}"
                title="Reject">
            <i class="fa fa-times"></i>
        </button>
    @endif

    {{-- Delete --}}
    <button type="button"
            class="btn btn-sm btn-dark text-white btn-delete-tour"
            data-id="{{ $row->id }}"
            data-url="{{ route('interaction.tour-schedules.destroy', $row->id) }}"
            title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>

<script>
    $(document).on('click', '.btn-confirm-tour', function () {
        const id = $(this).data('id');

        if (!confirm('Confirm this tour schedule?')) return;

        $.ajax({
            url: `/interaction/tour-schedules/${id}/confirm`,
            type: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                $('#tour-schedules-table').DataTable().ajax.reload();
            },
            error: function () {
                alert('Failed to confirm tour.');
            },
        });
    });

    $(document).on('click', '.btn-reject-tour', function () {
        const id = $(this).data('id');

        if (!confirm('Reject this tour schedule?')) return;

        $.ajax({
            url: `/interaction/tour-schedules/${id}/reject`,
            type: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                $('#tour-schedules-table').DataTable().ajax.reload();
            },
            error: function () {
                alert('Failed to reject tour.');
            },
        });
    });

    $(document).on('click', '.btn-delete-tour', function () {
        const url = $(this).data('url');

        if (!confirm('Are you sure you want to delete this tour schedule?')) return;

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                $('#tour-schedules-table').DataTable().ajax.reload();
            },
            error: function () {
                alert('Failed to delete tour schedule.');
            },
        });
    });
</script>