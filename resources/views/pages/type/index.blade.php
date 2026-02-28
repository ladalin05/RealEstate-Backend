<x-app-layout>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.type_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage your types
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('type.add') }}"
               class="btn btn-add-user text-white">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_type') }}
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

    // STATUS TOGGLE
    $(document).on("change",".enable_disable", function(){

        let id = $(this).data("id");
        let status = $(this).prop("checked");

        $.post("{{ URL::to('admin/ajax_status') }}", {
            _token: "{{ csrf_token() }}",
            id: id,
            value: status,
            action_for: "type_status"
        });
    });

    // DELETE
    $(document).on("click",".data_remove", function(){

        let id = $(this).data("id");

        Swal.fire({
            title: '{{ __("global.dlt_warning") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("global.dlt_confirm") }}'
        }).then((result) => {

            if(result.isConfirmed){

                $.post("{{ URL::to('admin/ajax_delete') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    action_for: "type_delete"
                }, function(res){

                    if(res.status == '1'){
                        $('#type-table').DataTable().ajax.reload();
                        Swal.fire('Deleted!', '', 'success');
                    }
                });

            }
        });
    });

    </script>
    @endpush

</x-app-layout>