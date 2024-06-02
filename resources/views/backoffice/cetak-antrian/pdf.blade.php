<!DOCTYPE html>
<html lang="">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <style>
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            html, body {
                width: 210mm;
                height: 297mm;
                display: flex;
                justify-content: center; /* Mengatur konten berada di tengah secara horizontal */
                align-items: center; /* Mengatur konten berada di tengah secara vertikal */
            }
            .no-print, .no-print * {
                display: none !important;
            }
            .container {
                text-align: center; /* Mengatur teks berada di tengah */
            }
        }
		@page {
                size: A4 portrait; /* Mengatur orientasi halaman menjadi potret */
                margin: 0; /* Menghapus margin bawaan halaman */
        }
    </style>
</head>
<body class="text-gray-900">
<div class="">
    <div class="flex justify-end p-10">
        <button onclick="history.back()" class=" mt-5 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 no-print"></i> Kembali</button>
    </div>
    <section class="">
        <div class="container card bg-white p-5 mt-4 border rounded-md w-full mx-auto text-center relative overflow-x-auto">
            <div>
                <img src="{{ asset('img/image 1.png') }}" class="h-20 mx-auto" alt="FlowBite Logo" />
                <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap dark:text-white">RUMAH SAKIT GIGI DAN MULUT</span> <br>
                <span class="text-sm">Jl. KH. Ashari 123 <i></i>( 0332 )  429584</span>
            </div>
            <hr>
            <h1 class="font-bold sm:text-2xl text-xl mt-5">NOMOR ANTREAN</h1>
            <h1 class="mt-5 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">{{ $data->no_antrian != null ? $data->no_antrian : "-" }}</h1>
            <h4 class="font-bold sm:text-2xl text-xl mt-5">{{ ucwords($data->poliklinik->name) }}</h4>
            <h4 class="font-bold sm:text-2xl text-xl mt-5">{{ $data->dokter->name }}</h4>
        </div>
    </section>
</div>
</body>
<script>
      function printAndRedirect() {
            // Trigger the print dialog
            window.print();

            // Listen for the 'afterprint' event to redirect after printing
            window.addEventListener('afterprint', function() {
                window.location.href = `{{ route('laporan.kunjungan-jenis') }}`; // Replace with your desired URL
            });
        }

        // Call the function when the page loads
        window.onload = printAndRedirect;
</script>
</html>
