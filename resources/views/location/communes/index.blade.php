<x-app-layout>

    {{-- ================= HEADER ================= --}}
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.commune_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage Commune
            </span>
        </x-slot>

        <div class="header-actions">
            <a  href="{{ route('location.communes.add') }}"
                class="btn btn-add-user text-white" 
                onclick="addData(event)" >
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


</x-app-layout>