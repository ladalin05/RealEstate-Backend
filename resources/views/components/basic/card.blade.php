@props([
    'data' => null,
    'title' => null,
])
<div class="card border border-primary shadow-sm">
    <div class="card-header bg-primary text-white border-bottom-0 py-2">
        <h6 class="mb-0">{{ $title }}</h6>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>