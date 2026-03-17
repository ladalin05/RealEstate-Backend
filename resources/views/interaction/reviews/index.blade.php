<x-app-layout>
    
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.review_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage Review
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('interaction.reviews.add') }}"
               class="btn btn-add-user text-white">
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_review') }}
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
