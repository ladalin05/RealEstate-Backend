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

            success: function (res) {

                form.data('processing', false);

                if (res.status === 'success') {

                    swalInit.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    });

                    // ✅ Close modal if form inside modal
                    let modal = form.closest('.modal');
                    if (modal.length) {
                        modal.modal('hide');
                    }

                } else {

                    swalInit.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message || 'Something went wrong!'
                    });

                }
            },

            error: function (xhr) {

                form.data('processing', false);

                let message = 'Something went wrong!';

                if (xhr.responseJSON && xhr.responseJSON.errors) {

                    message = '';

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        message += value + '<br>';
                    });
                }

                swalInit.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: message
                });
            }
        });
    });

});
</script>
@endpush
@endonce