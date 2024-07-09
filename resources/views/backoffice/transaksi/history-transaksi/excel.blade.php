<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>Versi Cetak</title>
        <style>
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
            /* background-color: #424745; */
            }
            @media print {
                @page {
                    size: A3 landscape;
                    padding: 10px
                        /* margin-bottom: 2cm; */
                }

                table {
                    width: 100%;
                }
                * {
                    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
                    color-adjust: exact !important;                 /*Firefox*/     /*Firefox*/
                }
                html,
                body {
                    width: 100%;
                    height: max-content;
                }

                .card{
                    padding: 0;
                }
                .card-body{
                    padding: 0 10px 0 10px;
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
        <section id="pembayaran" class="pembayaran">
            <div class="container-fluid mt-5" data-aos="fade-up">
                <div class="mb-3 text-center">
                    <h5 class="fw-bold"> Laporan History Reservasi</h5>
                    <hr>
                </div>
                <div class="table-responsive">
                    <div class="mt-5">
                        <h4 class="fw-bold">Periode : {{ $start.' - '.$end }}</h4>
                    </div>
                    <table id="customers" border="1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kunjungan</th>
                                <th>No.RM</th>
                                <th>Nama</th>
                                <th>Pembayaran</th>
                                <th>Jenis Pendaftaran</th>
                                <th>Poliklinik</th>
                                <th>Dokter</th>
                                <th>Antrian</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td >{{ $item->tanggal_kunjungan }}</td>
                                    <td > {{ $item->pasien->no_rm }}</td>
                                    <td >{{ ucwords($item->pasien->name) }}</td>
                                    <td >{{ ucwords($item->jenis_pembayaran) }}</td>
                                    <td >{{ ucwords($item->jenis_pendaftaran) }}</td>
                                    <td >{{ ucwords($item->poliklinik->name) }}</td>
                                    <td >{{ $item->dokter->name }}</td>
                                    <td >{{ $item->no_antrian != null ? $item->no_antrian : '-' }}</td>
                                    <td >{{ ucwords($item->status_pendaftaran) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @php
                $name = 'HistoryTransaksi' . '.xls';
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=$name");
        @endphp
    </body>
</html>


