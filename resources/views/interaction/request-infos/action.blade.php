
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
            <a href="{{route('interaction.request-infos.show', ['id' => $row->id])}}" class="dropdown-item">
                <i class="fa fa-eye"></i>
                View
            </a>
        </li>
        <li>
            @if(in_array($row->status, ['new', 'read']))
                <a href="{{ route('interaction.request-infos.reply', $row->id) }}" class="dropdown-item" title="Reply">
                    <i class="fa fa-reply"></i> Reply
                </a>
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
            <button type="button" class="dropdown-item text-danger data_remove" data-url="{{route('interaction.request-infos.destroy', ['id' => $row->id])}}" onclick="deleteData(event)">
                <i class="fa fa-trash me-2"></i>
                Delete
            </button>
        </li>
    </ul>
</div>

<script>
    $(document).on('click', '.btn-delete-inquiry', function () {
        const url = $(this).data('url');

        if (!confirm('Are you sure you want to delete this inquiry?')) {
            return;
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                $('#request-infos-table').DataTable().ajax.reload();
            },
            error: function () {
                alert('Failed to delete inquiry.');
            },
        });
    });

    $(document).on('click', '.btn-close-inquiry', function () {
        const id = $(this).data('id');

        $.ajax({
            url: `/interaction/request-infos/${id}/close`,
            type: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function () {
                $('#request-infos-table').DataTable().ajax.reload();
            },
            error: function () {
                alert('Failed to close inquiry.');
            },
        });
    });
</script>