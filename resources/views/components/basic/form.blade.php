<form {{ $attributes->merge([
        'class' => 'ajax-form needs-validation',
        'enctype' => 'multipart/form-data',
        'method' => 'POST'
    ]) }}>
    @csrf

    {{ $slot }}

</form>

@once
@push('scripts')
<!-- ✅ jQuery AJAX Form Handler -->
<script>
$(document).ready(function () {
});
</script>
@endpush
@endonce