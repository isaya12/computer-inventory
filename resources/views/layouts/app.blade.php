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
    </div>
    <!-- Scripts -->
    @stack('scripts') <!-- For page-specific JS -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

    <script src="assets/js/script.js"></script>

    <script>
        $(document).ready(function() {
            // Barcode generation functionality
            $('#generate_barcode').click(function() {
                let serialNumber = $('#serial_number').val();
                if (!serialNumber) {
                    alert('Please enter a serial number first');
                    return;
                }

                // Generate a unique barcode/RFID value
                let barcodeValue = 'DEV-' + serialNumber + '-' + Math.floor(1000 + Math.random() * 9000);
                $('#barcode').val(barcodeValue);

                // Generate visual barcode
                JsBarcode("#barcode-preview", barcodeValue, {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 50,
                    displayValue: true
                });
                $('#barcode-preview').show();
            });

            // Auto-generate barcode when serial number changes (optional)
            $('#serial_number').on('change', function() {
                if ($('#barcode').val() === '') {
                    $('#generate_barcode').click();
                }
            });

            // Bulk upload file validation
            $('#bulk_upload').change(function() {
                const file = this.files[0];
                if (file) {
                    const fileType = file.name.split('.').pop().toLowerCase();
                    if (fileType !== 'csv' && fileType !== 'xlsx') {
                        alert('Please upload a CSV or Excel file only');
                        $(this).val('');
                    }
                }
            });
        });
    </script>
    </body>
</html>
