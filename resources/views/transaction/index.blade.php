<x-app-layout>
    
    {{-- ================= BREADCRUMB / HEADER ================= --}}
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.transactions') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                {{ __('global.view_and_manage_payment_history') }}
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="javascript:void(0)" 
               data-bs-toggle="modal" 
               data-bs-target="#exportModal"
               class="btn btn-success text-white px-4">
                <i class="ph ph-file-xls me-1"></i>
                {{ __('global.export_transactions') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="content">
        <x-basic.datatables
            title="{{ __('global.transaction_list') }}"
            :data="$dataTable"> {{-- Assuming you've converted the controller to use DataTables --}}
        </x-basic.datatables>
    </div>

    {{-- ================= MODERN STYLES ================= --}}
    <style>
        .modal-content { border: none; border-radius: 1rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .modal-header { border-bottom: 1px solid #f3f4f6; padding: 1.5rem; }
        .modal-body { padding: 1.5rem; }
        .modal-footer { border-top: 1px solid #f3f4f6; padding: 1.25rem; }
        .form-control, .form-select { 
            border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.6rem 0.8rem; 
        }
        .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    </style>

    {{-- ================= EXPORT MODAL ================= --}}
    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                @if(Auth::User()->isAdmin()->slug != "administrator")
                    <div class="modal-body text-center py-5">
                        <i class="ph ph-warning-circle text-danger display-4"></i>
                        <p class="mt-3 fw-bold text-gray-800">Access denied! Only Admins can export data.</p>
                        <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">Close</button>
                    </div>
                @else
                    <form action="{{ route('transactions.export') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="text-xl font-bold text-gray-800">
                                <i class="ph ph-cloud-arrow-down me-2"></i>Export Transactions
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('global.start_date') }}</label>
                                <input type="text" name="start_date" class="form-control datepicker_trans" placeholder="Select Start Date" autocomplete="off" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('global.end_date') }}</label>
                                <input type="text" name="end_date" class="form-control datepicker_trans" placeholder="Select End Date" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50/50">
                            <button type="button" class="px-4 py-2 text-gray-600 font-medium" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="px-6 py-2 bg-success text-white rounded-lg font-semibold hover:bg-green-700 shadow-md transition">
                                Start Export
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= SCRIPTS ================= --}}
    @push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Datepicker for the Export Modal
            $('.datepicker_trans').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'mm/dd/yyyy'
            });

            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
            });

            // Handle successful exports if you redirect back with a message
            @if(Session::has('flash_message'))
                Toast.fire({ icon: 'success', title: "{{ Session::get('flash_message') }}" });
            @endif
        });
    </script>
    @endpush

</x-app-layout>