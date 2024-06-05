<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        {{-- Datatable  --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.tailwindcss.css">

        <style>
            .justify-self-end  {
                justify-content: end
            }
        </style>
        @stack('css')
    </head>
    <body class="bg-theme-body">
        <div class="h-screen overflow-scroll">
            @include('layouts.navigation')
            @include('components.modal-logout')
            @include('layouts.sidebar')
            @include('sweetalert::alert')
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    {{-- javascript plugins --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    {{-- Date picker  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    {{-- Datatable  --}}
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.tailwindcss.js"></script>

    <script>
        // Image Preview
        $(document).ready(function() {
            $('.file_input').change(function () {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $('.photosPreview')
                        .attr("src",event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            })
        })
        // Datatable
        $('#datatable').DataTable({
            responsive: true,
            ordering: false,
            "oLanguage": {
                "sEmptyTable": "Maaf data belum tersedia."
            },
            "columnDefs": [{
                // "defaultContent": "",
                // "targets": "_all"
            }]
        });

    </script>
    @stack('js')
</html>
