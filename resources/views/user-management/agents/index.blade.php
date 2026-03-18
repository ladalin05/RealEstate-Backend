<x-app-layout>
    
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">{{ __('global.agent_management') }}</h2>
            <span style="color:#646B72;font-size:14px;">
                Manage your Agents
            </span>
        </x-slot>

        <div class="header-actions">
            <a href="{{ route('users-management.agents.add') }}"
               class="btn btn-add-user text-white"
               onclick="addData(event)" >
                <i class="ph ph-plus-circle me-1"></i>
                {{ __('global.add_agent') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.datatables
            title="{{ __('global.list') }}"
            :data="$dataTable">
        </x-basic.datatables>
    </div>

    <x-basic.modal id="action-modal" size="modal-xl">
        <div id="action-form" novalidate>
        </div>
    </x-basic.modal>

</x-app-layout>
