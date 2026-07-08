<style>
    .action-toggle {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .action-toggle:hover {
        background-color: #f1f1f1;
    }

    .dropdown-menu .dropdown-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        padding: 8px 16px;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="dropdown">
    <button type="button" class="btn btn-sm btn-light border action-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li>
            <a href="{{ route('users-management.agents.view', $row->id) }}" class="dropdown-item">
                <i class="ph ph-eye me-2 text-info"></i>
                View Profile
            </a>
        </li>
        <li>
            <a href="{{ route('users-management.agents.edit', $row->id) }}" class="dropdown-item" onclick="editData(event)">
                <i class="ph ph-pencil-simple me-2 text-success"></i>
                Edit
            </a>
        </li>
        <li>
            <a href="{{ route('users-management.agents.properties', $row->id) }}" class="dropdown-item">
                <i class="ph ph-house-line me-2 text-primary"></i>
                View Properties
            </a>
        </li>
        <li>
            <button type="button" class="dropdown-item text-warning toggle_status"
                data-url="{{ route('users-management.agents.toggleStatus', $row->id) }}"
                onclick="toggleStatus(event)">
                <i class="ph ph-{{ $row->status == 1 ? 'lock' : 'lock-open' }} me-2"></i>
                {{ $row->status == 1 ? 'Suspend' : 'Activate' }}
            </button>
        </li>

        @if(!$row->is_verified)
        <li>
            <button type="button" class="dropdown-item text-success verify_license"
                data-url="{{ route('users-management.agents.verifyLicense', $row->id) }}"
                onclick="verifyLicense(event)">
                <i class="ph ph-seal-check me-2"></i>
                Verify License
            </button>
        </li>
        @endif

        <li>
            <button type="button" class="dropdown-item {{ $row->is_featured ? 'text-secondary' : 'text-primary' }} toggle_featured"
                data-url="{{ route('users-management.agents.toggleFeatured', $row->id) }}"
                onclick="toggleFeatured(event)">
                <i class="ph ph-star me-2"></i>
                {{ $row->is_featured ? 'Unfeature Agent' : 'Feature Agent' }}
            </button>
        </li>

        <li>
            <button type="button" class="dropdown-item text-primary send_reset_link"
                data-url="{{ route('users-management.agents.sendResetLink', $row->id) }}"
                onclick="sendResetLink(event)">
                <i class="ph ph-envelope-simple me-2"></i>
                Send Password Reset Link
            </button>
        </li>

        <li><hr class="dropdown-divider"></li>
        <li>
            <button type="button" class="dropdown-item text-danger data_remove"
                data-url="{{ route('users-management.agents.delete', $row->id) }}"
                onclick="deleteData(event)">
                <i class="fa fa-trash me-2"></i>
                Delete
            </button>
        </li>
    </ul>
</div>