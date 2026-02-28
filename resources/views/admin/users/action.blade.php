<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end" style="">
            <a href="{{ route('users-management.users.edit', $a->id) }}" class="dropdown-item">
                <i class="ph ph-pencil me-2"></i>
                {{ __('global.edit') }}
            </a>
            {{-- <a href="{{ route('users-management.users.change-password', $a->id) }}" class="dropdown-item" onclick="changePassword(event)">
                <i class="ph ph-password me-2"></i>
                {{ __('global.change_password') }}
            </a>
            <a href="{{ route('users-management.users.permission', $a->id) }}" class="dropdown-item" onclick="permission(event)">
                <i class="ph ph-person-simple-circle me-2"></i>
                {{ __('global.permission') }}
            </a> --}}
            <div class="dropdown-divider"></div>
            <a href="{{ route('users-management.users.delete', $a->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                <i class="ph ph-trash text-danger me-2"></i>
                {{ __('global.delete') }}
            </a>
    </div>
</div>
