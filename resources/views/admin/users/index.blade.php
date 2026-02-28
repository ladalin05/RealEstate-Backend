<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.users') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage your users
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('users-management.users.add') }}"
               class="btn btn-add-user text-white">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_user') }}
            </a>
        </div>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>
    <!-- /content area -->
    <x-basic.modal id="action-modal">
        <x-basic.form id="action-form" novalidate>
        </x-basic.form>
    </x-basic.modal>
    <!-- /content area -->
    @push('scripts')
        <script>
            function changePassword(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#action-modal #action-form').html('').removeClass('was-validated');
                        if (res.status == 'success') {
                            $('#action-modal .modal-title').text(res.title);
                            $('#action-modal #action-form').html(res.html);
                            $('#action-modal form').attr('action', url);
                            $('#action-modal').modal('show');
                        }
                    }
                });
            }

            function permission(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#action-modal #action-form').removeClass('was-validated');
                        if (res.status == 'success') {
                            $('#action-modal .modal-title').text(res.title);
                            $('#action-modal #action-form').html(res.html);
                            $('#action-modal form').attr('action', url);
                            $('#action-modal').modal('show');
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
