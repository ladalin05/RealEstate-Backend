<x-app-layout>

    {{-- ================= HEADER ================= --}}
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.district_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage District
            </span>
        </x-slot>

        <div class="header-actions">
            <a  href="{{ route('location.districts.add') }}"
                class="btn btn-add-user text-white" 
                onclick="addDistrict(event)" >
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_new') }}
            </a>
        </div>
    </x-basic.breadcrumb>
    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable"
        />
    </div>
    <x-basic.modal id="action-modal" size="modal-xl">
        <x-basic.form id="action-form" novalidate>
        </x-basic.form>
    </x-basic.modal>


    {{-- ================= SCRIPT ================= --}}
    @push('scripts')
    <script> 
        function showLoading() {
            // You could trigger a spinner here
        }
        function addDistrict(e) {
            e.preventDefault();
            console.log('Hello');
            var url = $(e.currentTarget).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: showLoading,
                success: function (res) {
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

        
        function editDistrict(e) {
            e.preventDefault();
            var url = $(e.currentTarget).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: showLoading,
                success: function (res) {
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
    </script>
    @endpush


</x-app-layout>