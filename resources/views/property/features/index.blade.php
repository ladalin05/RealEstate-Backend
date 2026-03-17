<x-app-layout>
    
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.feature_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage Feature
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('property.features.add') }}"
               class="btn btn-add-user text-white">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_feature') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable">
        </x-basic.datatables>
    </div>
</x-app-layout>
