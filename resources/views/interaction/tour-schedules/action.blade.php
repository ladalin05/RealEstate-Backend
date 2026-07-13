<style>
    .action-toggle {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .action-toggle:hover {
        background-color: #f1f1f1;
    }

    .dropdown-menu .dropdown-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        padding: 8px 16px;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="dropdown">
    <button type="button" class="btn btn-sm btn-light border action-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li>
            <a href="{{route('interaction.tour-schedules.show', ['id' => $row->id])}}" onclick="showView(event)" class="dropdown-item">
                <i class="fa fa-eye"></i>
                View
            </a>
        </li>
        <li>
            @if($row->status === 'pending')
                <button type="button" class="dropdown-item btn-confirm-tour" data-id="{{ $row->id }}">
                    <i class="fa fa-check"></i>
                    Confirm
                </button>
            @endif
        </li>
        <li>
            @if($row->status === 'pending')
                {{-- Reject (only if pending) --}}
                <button type="button" class="dropdown-item btn-reject-tour" data-id="{{ $row->id }}">
                    <i class="fa fa-times"></i>
                    Reject
                </button>
            @endif
        </li>
        <li>
            @if($row->status !== 'closed')
                <button type="button" class="dropdown-item btn-close-inquiry" data-id="{{ $row->id }}" >
                    <i class="fa fa-lock"></i> Lock
                </button>
            @endif
        </li>
        <li>
            <button type="button" class="dropdown-item text-danger data_remove" data-url="{{route('interaction.tour-schedules.destroy', ['id' => $row->id])}}" onclick="deleteData(event)">
                <i class="fa fa-trash me-2"></i>
                Delete
            </button>
        </li>
    </ul>
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