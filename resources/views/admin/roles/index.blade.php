<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="path_name">
                {{ __('global.roles') }}
        </x-slot>
        <x-slot name="action">
            <x-basic.option>
                <a href="{{ route('users-management.roles.add') }}" class="dropdown-item">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            </x-basic.option>
        </x-slot>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>
    <!-- /content area -->
</x-app-layout>
