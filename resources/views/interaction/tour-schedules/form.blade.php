<div class="py-1 px-4 position-relative">

    <form action="{{ $action }}" method="POST" id="inquiry-reply-form" class="ajax-form">
        @csrf
        @method('PUT')

        {{-- Inquiry Summary (read-only) --}}
        <div class="mb-3 p-3 rounded" style="background:#f8f9fa; border:1px solid #e9ecef;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <strong>{{ $inquiry->name }}</strong>
                <span class="badge
                    @switch($inquiry->status)
                        @case('new') bg-primary @break
                        @case('read') bg-warning text-dark @break
                        @case('replied') bg-success @break
                        @case('closed') bg-secondary @break
                    @endswitch
                ">
                    {{ ucfirst($inquiry->status) }}
                </span>
            </div>
            <div class="small text-muted mb-1">
                <i class="fa-solid fa-envelope me-1"></i> {{ $inquiry->email }}
                @if($inquiry->phone)
                    &nbsp;|&nbsp; <i class="fa-solid fa-phone me-1"></i> {{ $inquiry->phone }}
                @endif
            </div>
            @if($inquiry->property)
                <div class="small text-muted mb-2">
                    <i class="fa-solid fa-house me-1"></i> {{ $inquiry->property->title }}
                </div>
            @endif
            <div class="small" style="white-space: pre-wrap;">
                {{ $inquiry->message }}
            </div>
        </div>

        {{-- Reply Message --}}
        <div class="mb-3">
            <label for="reply_message" class="form-label">
                {{ __('global.reply_message') ?? 'Reply Message' }} <span class="text-danger">*</span>
            </label>
            <textarea name="reply_message" id="reply_message" rows="5"
                class="form-control form-control-modern"
                placeholder="Type your reply to {{ $inquiry->name }}...">{{ old('reply_message') }}</textarea>
        </div>

        {{-- Status --}}
        <div class="mb-2">
            <label for="status" class="form-label fw-bold text-dark small text-uppercase">
                STATUS
            </label>
            <select name="status" id="status" class="form-select custom-select">
                <option value="new" {{ old('status', $inquiry->status) == 'new' ? 'selected' : '' }}>
                    🔵 New
                </option>
                <option value="read" {{ old('status', $inquiry->status) == 'read' ? 'selected' : '' }}>
                    🟡 Read
                </option>
                <option value="replied" {{ old('status', $inquiry->status) == 'replied' ? 'selected' : '' }}>
                    🟢 Replied
                </option>
                <option value="closed" {{ old('status', $inquiry->status) == 'closed' ? 'selected' : '' }}>
                    ⚪ Closed
                </option>
            </select>
        </div>

        {{-- Footer --}}
        <div class="modal-footer px-0 pb-0 pt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>
            <button type="submit" class="btn btn-primary btn-save text-white shadow-sm">
                <i class="fa-solid fa-paper-plane me-2"></i>
                <span>Send Reply</span>
            </button>
        </div>

    </form>
</div>

<script>
$(document).ready(function () {
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#inquiry-reply-form');
    }
});
</script>