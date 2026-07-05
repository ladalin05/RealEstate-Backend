<div class="d-flex gap-1">
    {{-- View --}}
    <a href="{{ route('interaction.request-infos.show', $row->id) }}"
       class="btn btn-sm btn-info text-white"
       title="View">
        <i class="fa fa-eye"></i>
    </a>

    {{-- Reply (only if not already replied/closed) --}}
    @if(in_array($row->status, ['new', 'read']))
        <a href="{{ route('interaction.request-infos.reply', $row->id) }}"
           class="btn btn-sm btn-success text-white"
           title="Reply">
            <i class="fa fa-reply"></i>
        </a>
    @endif

    {{-- Close --}}
    @if($row->status !== 'closed')
        <button type="button"
                class="btn btn-sm btn-dark text-white btn-close-inquiry"
                data-id="{{ $row->id }}"
                title="Close">
            <i class="fa fa-lock"></i>
        </button>
    @endif

    {{-- Delete --}}
    <button type="button"
            class="btn btn-sm btn-danger text-white btn-delete-inquiry"
            data-id="{{ $row->id }}"
            data-url="{{ route('interaction.request-infos.destroy', $row->id) }}"
            title="Delete">
        <i class="fa fa-trash"></i>
    </button>
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