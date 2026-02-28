<x-app-layout>

    {{-- ================= HEADER ================= --}}
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.location_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage your locations
            </span>
        </x-slot>

        <div class="header-actions">
            <button type="button"
                    class="btn btn-add-user text-white"
                    data-url="{{ route('location.add') }}"
                    onclick="addLocation(event)">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_location') }}
            </button>
        </div>
    </x-basic.breadcrumb>
    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable"
        />
    </div>
    <x-basic.modal id="action-modal" size="modal-xl">
        <div id="action-form" novalidate>
        </div>
    </x-basic.modal>


    {{-- ================= SCRIPT ================= --}}
    @push('scripts')
    <script>

        /* ================= ADD LOCATION ================= */
        function addLocation(e) {
            e.preventDefault();
            let url = e.currentTarget.getAttribute('data-url');
            if (!url) {
                console.error("Add URL not found");
                return;
            }
            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {
                    $('#action-form').html('').removeClass('was-validated');
                    if (res.status === 'success') {
                        $('#action-modal .modal-title').text(res.title);
                        $('#action-form').html(res.html);
                        $('#action-form').attr('action', url);
                        $('#action-modal').modal('show');
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    errorAlert('Failed to load add form');
                }
            });
        }


        /* ================= EDIT LOCATION ================= */
        function editLocation(e) {
            e.preventDefault();
            let url = e.currentTarget.getAttribute('data-url');
            if (!url) {
                console.error("Edit URL not found");
                return;
            }
            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {
                    $('#action-form').html('').removeClass('was-validated');
                    if (res.status === 'success') {
                        $('#action-modal .modal-title').text(res.title);
                        $('#action-form').html(res.html);
                        $('#action-form').attr('action', url);
                        $('#action-modal').modal('show');
                    }
                },
                error: function () {
                    errorAlert('Failed to load edit form');
                }
            });
        }


        /* ================= DELETE LOCATION ================= */
        function deleteLocation(e) {
            e.preventDefault();
            let url = e.currentTarget.getAttribute('data-url');
            if (!url) {
                console.error("Delete URL not found");
                return;
            }
            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {
                    if (res.status === 'success') {
                        successAlert(res.message);
                        setTimeout(function () {
                            if (res.redirect) {
                                window.location.href = res.redirect;
                            } else {
                                location.reload();
                            }
                        }, 2000);
                    } else {
                        errorAlert(res.message || 'Failed to delete location');
                    }
                },
                error: function () {
                    errorAlert('Failed to delete location');
                }
            });
        }

    </script>
    @endpush

</x-app-layout>