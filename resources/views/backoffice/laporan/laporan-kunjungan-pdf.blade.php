<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- bootstrap css-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- fontawesome  -->
        <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
        <title>Document</title>
        <style>
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FAFAFA;
                font-family: 'Tinos', serif;
                font: 12pt;
            }
            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
            p, table, ol{
                font-size: 13.5pt;
            }
            #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            #customers td, #customers th {
            border: 1px solid #424745;
            padding: 8px;
            }

            /* #customers tr:nth-child(even){background-color: #424745;} */

            #customers tr:hover {background-color: #ddd;}

            #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            }
            @page {
                size: A4;
                margin-top: 0;
                margin-left: 75px;
                margin-bottom: 0;
                margin-right: 75px;
            }
            @media print {
                * {
                    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
                    color-adjust: exact !important;                 /*Firefox*/     /*Firefox*/
                }
                html, body {
                    width: 210mm;
                    height: 297mm;
                }
                .no-print, .no-print *
                {
                    display: none !important;
                }
            /* ... the rest of the rules ... */
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="d-flex justify-content-center mt-4">
                    <div class="align-items-end p-0">
                        <img src="{{ asset('img/image 1.png') }}" class="img-fluid me-auto" width="120" alt="">
                    </div>
                    <div class="align-self-center p-0 ">
                        <div class="row">
                            <div class="col-md-12 me-auto ">
                                <h1 class="text-center p-0 m-0" style="font-size: 18px; letter-spacing: 0.4ch">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,<br>
                                </h1>
                                <h1 class="text-center p-0 m-0" style="font-size: 18px; letter-spacing: 0.4ch">
                                    RISET DAN TEKNOLOGI
                                </h1>
                                <h1 class="text-center p-0 m-0 fw-bold" style="font-size: 18px;">
                                    UNIVERSITAS JEMBER
                                </h1>
                                <h1 class="text-center p-0 m-0 fw-bold" style="font-size: 18px;">
                                    RUMAH SAKIT GIGI DAN MULUT
                                </h1>

                                <h5 class="text-center p-0 m-0" style="font-size: 16px">Jl. KH. Ashari 123 <i></i>( 0332 )  429584</h5>

                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr color="black">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="content">
                    <h1 class="text-center p-0 m-0 fw-bold" style="font-size: 18px;">
                        Laporan Kunjungan Pasien Pendaftaran Online
                    </h1>
                    <div class="d-flex justify-content-end">
                        <button onclick="history.back()" class="btn btn-primary no-print"></i> Kembali</button>
                    </div>
                    <div class="mt-5">
                        <h4 class="fw-bold">Periode : {{ $start.' - '.$end }}</h4>
                    </div>
                    <table id="customers">
                        <thead>
                            <tr>
                                <th>No.Antrian</th>
                                <th>Tanggal</th>
                                <th>No.RM</th>
                                <th>Nama</th>
                                <th>Dokter</th>
                                <th>Poliklinik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->no_antrian != null ? $item->no_antrian : '-' }}</td>
                                    <td >{{ $item->tanggal_kunjungan }}</td>
                                    <td > {{ $item->pasien->no_rm }}</td>
                                    <td >{{ ucwords($item->pasien->name) }}</td>
                                    <td >{{ ucwords($item->dokter->name) }}</td>
                                    <td >{{ ucwords($item->poliklinik->name) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5">
                        <h4 class="">Total Pendaftaran Online :  {{ $count_pendaftaran_online }}</h4>
                    </div>

                </div>
            </div>
        </div>
    </body>
    <script>
        print();
    </script>
</html>
