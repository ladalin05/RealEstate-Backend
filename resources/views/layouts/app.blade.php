<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon shortcut" href="{{ asset('assets/images/default/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Styles -->
    <link href="{{ asset('assets/icons/phosphor/regular/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/login.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script> 
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/init.js') }}"></script>

    @include('layouts.script')

    <!-- Scripts by page -->
    @stack('scripts')

</head>

<body>
    <div class="card-overlay" id="body-overlay"><span class="ph ph-spinner-gap spinner"></span></div>
    @include('layouts.navigation')
    <!-- Page content -->
    <div class="page-content">
        @include('layouts.sidebar')
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">
                {{ $slot }}
            </div>
            <!-- /inner content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <script>

        function successAlert(message, redirect = null) {
            swalInit.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        }

        function errorAlert(message) {
            swalInit.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
        }

        function ajaxSubmit(formSelector) {
            let form = $(formSelector);
            let formData = new FormData(form[0]); 
            
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method') || 'POST',
                data: formData,
                processData: false,
                contentType: false,
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
        }
    </script>
</body>

</html>
