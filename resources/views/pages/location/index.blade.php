<x-app-layout>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.location_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage your locations
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('location.add') }}"
               class="btn btn-add-user text-white"
               onclick="addLocation(event)">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_location') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable"
        />
    </div>

    {{-- ================= MODAL ================= --}}
    <x-basic.modal id="action-modal" size="modal-xl">
        <x-basic.form id="action-form" novalidate>
        </x-basic.form>
    </x-basic.modal>

    {{-- ================= STYLES ================= --}}
    <style>
        .modal-content {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .modal-header {
            border-bottom: 1px solid #f3f4f6;
            padding: 1.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid #f3f4f6;
            padding: 1.25rem;
        }
        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            padding: 0.6rem 0.8rem;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
    </style>

    {{-- ================= SCRIPTS ================= --}}
    @push('script')
    <script>

        /* ================= ADD ================= */
        function addLocation(e) {
            e.preventDefault();
            let url = e.currentTarget.getAttribute('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {

                    $('#action-form')
                        .html('')
                        .removeClass('was-validated');

                    if (res.status === 'success') {

                        $('#action-modal .modal-title').text(res.title);

                        $('#action-form').html(res.html);

                        $('#action-form').attr('action', url);

                        $('#action-modal').modal('show');
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Something went wrong');
                }
            });
        }

        /* ================= EDIT ================= */
        function editLocation(e) {
            e.preventDefault();

            let url = e.currentTarget.getAttribute('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {

                    $('#action-form')
                        .html('')
                        .removeClass('was-validated');

                    if (res.status === 'success') {

                        $('#action-modal .modal-title').text(res.title);

                        $('#action-form').html(res.html);

                        $('#action-form').attr('action', url);

                        $('#action-modal').modal('show');
                    }
                },
                error: function () {
                    alert('Failed to load edit form');
                }
            });
        }

        /* ================= DELETE ================= */
        $(document).on('click', '.deleteBtn', function () {

            let id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.ajax({
                    url: "{{ url('location/delete') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function () {

                        // Reload DataTable safely
                        if ($.fn.DataTable.isDataTable('#location-table')) {
                            $('#location-table').DataTable().ajax.reload(null, false);
                        }

                        Swal.fire(
                            'Deleted!',
                            'The location has been removed.',
                            'success'
                        );
                    },
                    error: function () {
                        Swal.fire('Error', 'Delete failed', 'error');
                    }
                });

            });

        });

    </script>
    @endpush

</x-app-layout>