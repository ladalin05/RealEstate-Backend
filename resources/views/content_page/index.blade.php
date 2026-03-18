<x-app-layout>
    <style>
        .admin-table thead th {
            color: #ffffff; /* White text for contrast */
            background-color: #4a5568; /* Darker, modern background color */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .admin-table tbody td {
            color: #4a5568 !important;
        }
    </style>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0"> {{-- Added shadow and border-0 for a modern card look --}}
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark"> {{ __('global.content_pages') }}</h4>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <a href="{{ route('settings.content_page.create') }}" class="btn btn-success btn-md waves-effect waves-light w-100"
                                    data-toggle="tooltip" title="{{ __('global.add_content_page') }}">
                                    {{ __('global.add_content_page') }}
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(Session::has('flash_message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('flash_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Transactions Table --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-hover admin-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($content_page as $content)
                                            <tr>
                                                <td class="fw-bold"> {{ $content->title }} </td>

                                                <td>{{ $content->slug }}</td>

                                                <td>
                                                    @if($content->status == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>

                                                <td>{{ $content->created_at->format('M d, Y h:i A') }}</td>
                                                <td>{{ $content->updated_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fa fa-info-circle me-1"></i> No content pages found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- End Transactions Table --}}

                        </div> {{-- End card-body --}}
                    </div> {{-- End card --}}
                </div>
            </div>
        </div>
    </div>

    {{-- 🚀 Export Modal (Cleaned up and functionally corrected) --}}
    <div id="export_model" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="exportModalLabel"><i class="fas fa-cloud-download-alt me-2"></i> Export Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                @if(Auth::User()->isAdmin()->slug != "administrator")
                    <div class="modal-body">
                        <p class="text-center fw-bold text-danger py-4">Access denied! Only Admins can export data.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                @else
                    <form action="{{ route('transactions.export') }}" method="POST" class="form-horizontal">
                        @csrf
                        <div class="modal-body">
                            {{-- Start Date Field --}}
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">{{ __('global.start_date') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" name="start_date" placeholder="Select Start Date" class="form-control datepicker_trans" id="datepicker_trans1" autocomplete="off" required>
                                </div>
                            </div>

                            {{-- End Date Field --}}
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">{{ __('global.end_date') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" name="end_date" placeholder="Select End Date" class="form-control datepicker_trans" id="datepicker_trans2" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Export Data</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#datepicker-autoclose, #datepicker_trans1, #datepicker_trans2').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'mm/dd/yyyy'
                });

                // 1. Gateway Filter Logic
                $('#gateway_select').on('change', function() {
                    var gateway = $(this).val();
                    var currentUrl = new URL(window.location.href);

                    if (gateway) {
                        currentUrl.searchParams.set('gateway', gateway);
                    } else {
                        currentUrl.searchParams.delete('gateway');
                    }
                    // Also clear other search parameters when filtering by gateway
                    currentUrl.searchParams.delete('s');
                    currentUrl.searchParams.delete('date');

                    window.location.href = currentUrl.toString();
                });
            });
        </script>
    @endpush

</x-app-layout>