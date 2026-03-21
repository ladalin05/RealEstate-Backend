<x-app-layout>
    <style>
        /* Modernize the table header */
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

        /* Improved search bar styling for integration */
        .search-form-inline .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            width: 200px;
        }
        .search-form-inline .btn-search {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background-color: #007bff; /* Primary color */
            color: white;
            border: 1px solid #007bff;
        }
        .search-form-inline {
            display: flex;
        }
        
        /* Action button style refinement */
        .action-column {
            min-width: 80px; /* Ensure actions column doesn't collapse */
            text-align: center;
        }
    </style>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark"> {{ __('global.report_management') }}</h4>
                        </div>

                        <div class="card-body">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-12 d-flex justify-content-end px-5">
                                    <form method="GET" action="{{ route('reports.filter') }}" class="search-form-inline">
                                        <input type="text" name="search_text" id="search_text" placeholder="{{ __('global.search_by_name') }}" 
                                            class="form-control" value="{{ request('search_text') }}">
                                        <button type="button" class="btn btn-search" id="search_button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="col-md-7">
                                    @if(Session::has('flash_message'))
                                        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                                            <i class="fa fa-check-circle me-1"></i>
                                            Success! {{ Session::get('flash_message') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            

                            <div id="report_list_container">
                                {!! $report_list !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('admin_assets/js/jquery.min.js') }}"></script> 
    <script src="{{ URL::asset('admin_assets/js/sweetalert2@11.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#search_button').click(function() {
                var searchText = $('#search_text').val().trim();
                $.ajax({
                    url: "{{ route('reports.filter') }}",
                    type: "GET",
                    data: { search_text: searchText },
                    success: function(response) {
                        $('#report_list_container').html(response);
                    },
                    error: function(xhr) {
                        console.error("An error occurred while searching:", xhr);
                    }
                });
            });
        })
    </script>
</x-app-layout>