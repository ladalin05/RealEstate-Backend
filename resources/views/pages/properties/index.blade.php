<x-app-layout>
    
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.property_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage your properties
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('property.add') }}"
               class="btn btn-add-user text-white">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_property') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable">
        </x-basic.datatables>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                function filterProperty() {
                    var type_id = $('#type_id').val();
                    var location_id = $('#location_id').val();
                    var searchText = $('#search_text').val().trim();
                    $.ajax({
                        url: "{{ route('property.filter') }}",
                        type: 'GET',
                        data: {
                            type_id: type_id,
                            location_id: location_id,
                            search_text: searchText,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#property_card_list').html(response.property_view);
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }

                $('#btn_search').on('click', function(e) {
                    e.preventDefault();
                    filterProperty();
                });
                                  
                $('#type_id').on('change', function(e) {
                    e.preventDefault();
                    filterProperty();
                }); 
                  
                $('#location_id').on('change', function(e) {
                    e.preventDefault();
                    filterProperty();
                });
            });
        </script>
    @endpush

</x-app-layout>
