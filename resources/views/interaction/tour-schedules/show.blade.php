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
</style>

<div class="row mb-3 g-3">
    <div class="col-md-6">
        <div class="info-card h-100">
            <div class="info-label">Requester</div>
            <div class="info-value fw-semibold">{{ $tourSchedule->user->name ?? $tourSchedule->name }}</div>

            <div class="info-label">Email</div>
            <div class="info-value">{{ $tourSchedule->email }}</div>

            <div class="info-label">Phone</div>
            <div class="info-value">{{ $tourSchedule->phone ?? '-' }}</div>

            <div class="info-label">Message</div>
            <div class="info-value mb-0">{{ $tourSchedule->message ?? '-' }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-card h-100">
            <div class="info-label">Property</div>
            <div class="info-value fw-semibold">{{ $tourSchedule->property->title_en ?? '-' }}</div>

            <div class="info-label">Assigned Agent</div>
            <div class="info-value">
                {{ $tourSchedule->agent ? $tourSchedule->agent->first_name . ' ' . $tourSchedule->agent->last_name : 'Unassigned' }}
            </div>

            <div class="info-label">Status</div>
            <div class="info-value mb-0">
                <span class="badge bg-{{ ['pending' => 'info', 'confirmed' => 'success', 'rejected' => 'danger'][$tourSchedule->status] ?? 'secondary' }}">
                    {{ ucfirst($tourSchedule->status) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3 g-3">
    <div class="col-md-4">
        <div class="info-label">Tour Type</div>
        <div class="info-value mb-0">
            <span class="badge bg-light text-dark border">
                {{ $tourSchedule->tour_type === 'video-chat' ? 'Video Chat' : 'In Person' }}
            </span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-label">Requested Date</div>
        <div class="info-value mb-0">{{ $tourSchedule->schedule_date?->format('Y-m-d') }}</div>
    </div>
    <div class="col-md-4">
        <div class="info-label">Requested Time</div>
        <div class="info-value mb-0">{{ \Carbon\Carbon::parse($tourSchedule->schedule_time)->format('H:i') }}</div>
    </div>
</div>

@if($tourSchedule->handled_by)
    <hr>
    <p class="text-muted small mb-0">
        Handled by {{ $tourSchedule->handledBy->name ?? '#' . $tourSchedule->handled_by }}
        @if($tourSchedule->handled_at)
            on {{ $tourSchedule->handled_at?->format('Y-m-d H:i') }}
        @endif
    </p>
@endif

<p class="text-muted small mb-0">
    Submitted {{ $tourSchedule->created_at?->format('Y-m-d H:i') }}
</p>