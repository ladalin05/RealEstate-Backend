
    <style>
        /* Modernize the table header */
        .admin-table thead th {
            color: #ffffff; /* White text for contrast */
            background-color: #4a5568; /* Darker, modern background color */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>

<div class="table-responsive">
    <table class="table table-striped table-hover admin-table">
        <thead>
            <tr>
                <th>{{ __('global.name') }}</th>
                <th>{{ __('global.email') }}</th>
                <th>{{ __('global.title') }}</th>
                <th>{{ __('global.message') }}</th>
                <th>{{ __('global.date') }}</th>
                <th class="action-column">{{ __('global.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $data)
                <tr id="post_id_{{ $data->id }}">
                    <td>{{ getUserById($data->user_id)->name }}</td>
                    <td>{{ getUserById($data->user_id)->email }}</td>
                    <td>
                        <a href="{{ url('admin/property/edit/' . $data->post_id) }}" 
                            class="text-info fw-bold text-decoration-none"> 
                            {{ StrLimit(stripslashes(getPropertyInfo($data->post_id)->title), 40) }}
                        </a> 
                    </td>
                    <td>
                        <span data-toggle="tooltip" title="{{ stripslashes($data->message) }}">
                            {{ StrLimit(stripslashes($data->message), 30) }}
                        </span>
                    </td>
                    <td class="text-muted">{{ date('M d, Y h:i A', $data->date) }}</td>
                    <td class="action-column">
                        <a href="#" class="btn btn-sm btn-outline-danger data_remove" 
                            data-toggle="tooltip" title="{{ __('global.remove') }}" data-id="{{ $data->id }}"> 
                            <i class="fa fa-trash"></i> 
                        </a> 
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fa fa-info-circle me-1"></i> No reports found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>