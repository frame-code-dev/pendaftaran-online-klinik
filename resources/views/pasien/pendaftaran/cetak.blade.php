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
    <div class="p-4 md:p-5 space-y-4">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white" id="nama_pasien">{{ $data->pasien->name }}</h4>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-5">
            <tbody class="border p-4 w-full">
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Tanggal Kunjungan</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="tanggal_kunjungan">{{ $data->tanggal_kunjungan }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Pembayaran</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="jenis_pembayaran">{{ $data->jenis_pembayaran }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Poliklinik</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="poliklinik">{{ $data->poliklinik->name }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Dokter</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="dokter">{{ $data->dokter->name }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">No.Antrian Klinik</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="no_antrian">{{ $data->no_antrian }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Estimasi Dilayanin</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="estimasi_dilayani">{{ $data->estimasi_dilayani }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Jam Praktek</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="estimasi_dilayani">{{ $data->estimasi_dokter }}</td>
                </tr>
                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <td width="20%" class="p-4">Kode Booking</td>
                    <td width="1%">:</td>
                    <td class="font-bold" id="kode_booking">{{ $data->kode_pendaftaran }}</td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-center mx-auto mt-4">
            {{-- {!! $simple !!} --}}
            <center>
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(256)->generate($data->kode_pendaftaran)) !!} ">
            </center>
            {{-- <img src="{{ asset('') }}qrcodes/{{ $data->kode_pendaftaran }}.png" id="foto_bukti" class="w-96" alt=""> --}}
        </div>
        <div>
            <h4 id="qrcode" class="text-xl font-bold text-center text-gray-900 dark:text-white">{{ $data->kode_pendaftaran }}</h4>
        </div>
        <center>
            <small> Catatan : Harap datang 1 Jam sebelum pemeriksaan</small>
        </center>

    </div>
</body>
</html>
