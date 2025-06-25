<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.jpg') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/style.css') }}">
    </head>
    <body>



    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Header Component -->
        @include('components.header')

        <!-- sidebar Component -->
        @include('components.sidebar')

        <div class="page-wrapper">
            {{ $slot }}
        </div>
        <x-toaster-hub />
    </div>
    <!-- Scripts -->
    @stack('scripts') <!-- For page-specific JS -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    </body>
</html>
