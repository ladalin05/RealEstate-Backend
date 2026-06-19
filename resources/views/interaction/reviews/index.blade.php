<x-app-layout>
    
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.review_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage Review
            </span>
        </x-slot>

    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable">
        </x-basic.datatables>
    </div>
</x-app-layout>
