<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href=" {{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/style.css') }}">
    </head>
    <body class="account-page">

        <div class="main-wrapper">
            {{ $slot }}
        </div>
            <script src="assets/js/jquery-3.6.0.min.js"></script>

            <script src="assets/js/feather.min.js"></script>

            <script src="assets/js/bootstrap.bundle.min.js"></script>

            <script src="assets/js/script.js"></script>
    </body>
</html>
