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
    $(document).on('submit', '.ajax-form', function (e) {
        e.preventDefault();
        
        let form = $(this);
        console.log("Submitting form:", form.attr('id') || form.attr('class'));
        return;     

        if (form.data('processing')) return;

        form.data('processing', true);

        let url = form.attr('action');
        let method = form.attr('method') || 'POST';
        let data = new FormData(this);

        if (!url) {
            console.error("Form action is missing!");
            form.data('processing', false);
            return;
        }

        $.ajax({
            url: url,
            type: method,
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function (response) {

                if (response.status === 'success') {
                    successAlert(response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                }

                if (response.status === 'error') {
                    errorAlert(response.message || 'An error occurred');
                }
            },

            error: function (xhr) {

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    console.log(errors);
                    swalInit.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: errorMessage
                    });
                } else {
                    let errors = xhr.responseJSON.message;
                    errorAlert(errors || 'An unexpected error occurred');
                }
            }
        });
    });

});
</script>
@endpush
@endonce