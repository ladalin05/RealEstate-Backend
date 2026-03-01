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
    </script>
    @endpush

</x-app-layout>