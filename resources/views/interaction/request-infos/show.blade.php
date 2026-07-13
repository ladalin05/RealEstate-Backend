<style>
    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
    }
    .info-label {
        font-size: 12px;
        color: #868e96;
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 14px;
        margin-bottom: 12px;
    }
    .thread-msg {
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 10px;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .thread-msg.from-user {
        background: #f1f3f5;
        margin-right: auto;
    }
    .thread-msg.from-agent {
        background: #d1e7ff;
        margin-left: auto;
        text-align: right;
    }
    .thread-msg .meta {
        font-size: 11px;
        color: #868e96;
        margin-top: 2px;
    }
    .view-only-notice {
        font-size: 12px;
        color: #868e96;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="row mb-3 g-3">
    <div class="col-md-6">
        <div class="info-card h-100">
            <div class="info-label">Requester</div>
            <div class="info-value fw-semibold">{{ $requestInfo->user->name ?? $requestInfo->name }}</div>

            <div class="info-label">Email</div>
            <div class="info-value">{{ $requestInfo->email }}</div>

            <div class="info-label">Phone</div>
            <div class="info-value">{{ $requestInfo->phone ?? '-' }}</div>

            <div class="info-label">Role</div>
            <div class="info-value mb-0">{{ $requestInfo->role ? ucfirst($requestInfo->role) : '-' }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-card h-100">
            <div class="info-label">Property</div>
            <div class="info-value fw-semibold">{{ $requestInfo->property->title_en ?? '-' }}</div>

            <div class="info-label">Assigned Agent</div>
            <div class="info-value">
                {{ $requestInfo->agent ? $requestInfo->agent->first_name . ' ' . $requestInfo->agent->last_name : 'Unassigned' }}
            </div>

            <div class="info-label">Status</div>
            <div class="info-value mb-0">
                <span class="badge bg-{{ ['pending' => 'info', 'active' => 'success', 'closed' => 'dark'][$requestInfo->status] ?? 'secondary' }}">
                    {{ ucfirst($requestInfo->status) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="mb-0">Conversation ({{ $requestInfo->messages->count() }})</h6>
    <span class="view-only-notice">
        <i class="fa fa-eye"></i> View only
    </span>
</div>

<div class="thread-box mb-2" style="max-height: 400px; overflow-y: auto;">
    @forelse($requestInfo->messages as $msg)
        <div class="thread-msg {{ $msg->sender === 'agent' ? 'from-agent' : 'from-user' }}">
            <div>{{ $msg->message }}</div>
            <div class="meta">
                {{ ucfirst($msg->sender) }} &middot; {{ $msg->created_at?->format('Y-m-d H:i') }}
            </div>
        </div>
    @empty
        <p class="text-muted text-center">No messages yet.</p>
    @endforelse
</div>

<p class="text-muted small mb-0">
    Submitted {{ $requestInfo->created_at?->format('Y-m-d H:i') }}
    @if($requestInfo->status === 'closed')
        &middot; This inquiry is closed.
    @endif
</p>