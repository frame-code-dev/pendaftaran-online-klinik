<x-app-layout>
    @push('js')
        <script>
            $('.detail-button').on('click',function() {
                let url = `{{ route('pasien.history-transaksi.detail') }}`
                let id = $(this).data('id');
                console.log(id);
                $.ajax({
                    url: url,
                    data:{
                        id:id
                    },
                    success:(data) => {
                        console.log(data);
                        $('#nama_pasien').text(data.pasien.name);
                        $('#tanggal_kunjungan').text(data.tanggal_kunjungan);
                        $('#jenis_pembayaran').text(data.jenis_pembayaran);
                        $('#poliklinik').text(data.poliklinik.name);
                        $('#dokter').text(data.dokter.name);
                        $('#no_antrian').text(data.no_antrian);
                        $('#estimasi_dilayani').text(data.estimasi_dilayani);
                        $('#jam_praktek').text(data.estimasi_dokter);
                        $('#kode_booking').text(data.kode_pendaftaran);
                        var img = `{{ asset('') }}qrcodes/${data.kode_pendaftaran}.png`
                        $('#foto_bukti').attr("src", `${img}`);
                        let url_download = `{{ url('dashboard-pasien/history-transaksi/download') }}/${data.id}`;
                        let url_dibatalkan = `{{ route('pasien.history-transaksi.update') }}/?id=${data.id}&status=batal`;
                        $('#dibatalkan').attr('href',url_dibatalkan);
                        $('.cetakQrCodeButton').attr('href', url_download);
                        $('#qrcode').text(data.kode_pendaftaran);
                    }
                })
            })
        </script>
        <script>
            document.getElementById('cetakQrCodeButton').addEventListener('click', function(event) {
                // Jika download selesai, arahkan kembali ke halaman dashboard
                // window.location.href = "{{ route('dashboard.pasien') }}";

            });
        </script>
    @endpush
    <div class="p-4 px-10 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto max-h-fit pb-96">
            <div class="bg-blue-800 p-4 rounded-t-lg flex gap-2">
                <a href="{{ route('dashboard.pasien') }}">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                      </svg>
                </a>
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">History Transaksi</h4>
            </div>
            <div class="p-5">
                <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto mb-5">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatable">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th scope="col" class="px-4 py-3">Kunjungan</th>
                                <th scope="col" class="px-4 py-3">Pembayaran</th>
                                <th scope="col" class="px-4 py-3">Poliklinik</th>
                                <th scope="col" class="px-4 py-3">Dokter</th>
                                <th scope="col" class="px-4 py-3">Jam Praktek</th>
                                <th scope="col" class="px-4 py-3">Antrian</th>
                                <th scope="col" class="px-4 py-3">Keterangan</th>
                                <th scope="col" class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->tanggal_kunjungan }}</td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->jenis_pembayaran) }}</td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->poliklinik->name) }}</td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->dokter->name) }}</td>
                                   
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @foreach ($item->jadwal_dokter as $item_jadwal)
                                            @php
                                                $jadwalArray = $item_jadwal->toArray();
                                                $hari_kunjungan = strtolower(\Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('l'));
                                                $jadwalHari = $jadwalArray[$hari_kunjungan == 'jumat' ? 'jumaat' : $hari_kunjungan] ?? null;
                                            @endphp
                                            {{ $jadwalHari != null ? $jadwalHari : '-' }}
                                        @endforeach

                                    </td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->no_antrian != null ? $item->no_antrian : '-' }}</td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($item->status_pendaftaran == 'proses' || $item->status_pendaftaran == 'pending')
                                            <span class="font-bold p-3 text-yellow-500 bg-yellow-50">{{ ucwords($item->status_pendaftaran) }}</span>
                                        @elseif($item->status_pendaftaran == 'selesai')
                                            <span class="font-bold p-3 text-green-500 bg-green-50">{{ ucwords($item->status_pendaftaran) }}</span>
                                        @else
                                            <span class="font-bold p-3 text-red-500 bg-red-50">{{ ucwords($item->status_pendaftaran) }}</span>

                                        @endif
                                    </td>
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <x-primary-button class="detail-button" type="button" data-modal-target="default-modal" data-modal-toggle="default-modal" data-id="{{ $item->id }}" >Detail</x-primary-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     {{-- modal --}}
     <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Selamat Datang
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white" id="nama_pasien">-</h4>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-5">
                        <tbody class="border p-4 w-full">
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Tanggal Kunjungan</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="tanggal_kunjungan">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Pembayaran</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="jenis_pembayaran">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Poliklinik</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="poliklinik">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Dokter</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="dokter">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">No.Antrian Klinik</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="no_antrian">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Estimasi Dilayanin</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="estimasi_dilayani">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Jam Praktek</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="jam_praktek">-</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Kode Booking</td>
                                <td width="1%">:</td>
                                <td class="font-bold" id="kode_booking">-</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center mx-auto">
                        {{-- {!! $simple !!} --}}
                        <img src="" id="foto_bukti" class="w-96" alt="">
                    </div>
                    <div>
                        <h4 id="qrcode" class="text-xl font-bold text-center text-gray-900 dark:text-white">-</h4>
                    </div>

                </div>
                <!-- Modal footer -->
                <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <a href="#" id="cetakQrCodeButton" class="cetakQrCodeButton text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cetak QRCode</a>
                    <a href="#" id="dibatalkan" class=" py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-red-200 hover:bg-red-100 hover:text-red-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Dibatalkan</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
