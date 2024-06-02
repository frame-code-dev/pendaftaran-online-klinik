<x-app-layout>
    @push('js')
        <script>
            var urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
            var urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
            var urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";

            let user_provinsi_id = `{{ Session::get('user')->provinsi_id }}`; // Ubah nama variabel menjadi user_provinsi_id
            let user_kabupaten_id = `{{ Session::get('user')->kabupaten_id }}`; // Ubah nama variabel menjadi user_provinsi_id
            let user_kecamatan_id = `{{ Session::get('user')->kecamatan_id }}`; // Ubah nama variabel menjadi user_provinsi_id

            $.getJSON(urlProvinsi, function (res) {
                let provinsi_name;
                let selected_provinsi_id; // Ubah nama variabel menjadi selected_provinsi_id
                res = $.map(res, function (obj) {
                    if (obj.id == user_provinsi_id) {
                        obj.text = obj.nama;
                        provinsi_name = obj.text;
                        selected_provinsi_id = obj.id; // Simpan id provinsi yang sesuai dengan id dari sesi pengguna
                        $('#provinsi').html(provinsi_name);
                    }
                    return obj; // Perlu mengembalikan objek dalam fungsi map
                });
                $.getJSON(urlKabupaten + selected_provinsi_id + ".json", function(res) {
                    let kabupaten_id;
                    res = $.map(res, function (obj) {
                        if (obj.id == user_kabupaten_id) {
                            obj.text = obj.nama;
                            kabupaten_id = obj.id;
                            $('#kabupaten').html(obj.text); // Tampilkan nama kabupaten yang sesuai
                        }
                        return obj;
                    });
                    $.getJSON(urlKecamatan + kabupaten_id + ".json", function(res) {
                        res = $.map(res, function (obj) {
                            if (obj.id == user_kecamatan_id) {
                                obj.text = obj.nama;
                                $('#kecamatan').html(obj.text);
                            }
                            return obj;
                        })
                    })
                });
            });
        </script>
        <script>
            // update status
            $('#btn-konfirmasi').on('click', function() {
                var id = $(this).data('id');
                console.log(id);
                $.ajax({
                    type: "GET",
                    url: "{{ route('pasien.konfirmasi-pendaftaran.store', ':id') }}".replace(':id', id),
                    success: function(data) {
                        console.log(data);
                        $('#nama_pasien').text(data.data_pasien.name);
                        $('#tanggal_kunjungan').text(data.tanggal_kunjungan);
                        $('#jenis_pembayaran').text(data.jenis_pembayaran);
                        $('#poliklinik').text(data.dokter.poliklinik.name);
                        $('#dokter').text(data.dokter.name);
                        let no_antrian = data.dokter.kuota != null ? data.noAntrian : '-';
                        $('#no_antrian').text(no_antrian);
                        $('#estimasi_dilayani').text(data.estimasi_waktu);
                        $('#jam_praktek').text(data.estimasi_dokter);
                        $('#kode_booking').text(data.kodeUnik);
                        $('#qrcode').text(data.kodeUnik);

                    }
                })
            })
        </script>
        <script>
            document.getElementById('cetakQrCodeButton').addEventListener('click', function(event) {
                // Jika download selesai, arahkan kembali ke halaman dashboard
                setTimeout(function() {
                    window.location.href = "{{ route('dashboard.pasien') }}";
                }, 5000); // 5000 milliseconds = 5 seconds

            });
        </script>
    @endpush
    <div class="p-5 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto ">
            <div class="bg-blue-800 p-4 rounded-t-lg flex content-center gap-2">
                <a href="{{ route('pasien.list-dokter',Session::get('poliklinik')->id) }}">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                      </svg>
                </a>
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">Konfirmasi Pendaftaran</h4>
            </div>
            <div class="p-5 space-y-4">
                <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                    <div class="heading flex-auto">
                        <h4 class="font-bold tracking-tighter text-base text-theme-text mb-3">
                            Data Pasien
                        </h4>
                        <hr>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-5">
                        <tbody class="border p-4 w-full">
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">No. RM</td>
                                <td width="1%">:</td>
                                <td class="font-bold">{{ $data_pasien->no_rm }}</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">NIK</td>
                                <td width="1%">:</td>
                                <td class="font-bold">{{ $data_pasien->nik }}</td>
                            </tr>

                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Nama Pasien</td>
                                <td width="1%">:</td>
                                <td class="font-bold">{{ ucwords($data_pasien->name) }}</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Jenis Kelamin</td>
                                <td width="1%">:</td>
                                <td class="font-bold">{{ $data_pasien->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Tanggal Lahir</td>
                                <td width="1%">:</td>
                                <td class="font-bold"> {{ \Carbon\Carbon::parse($data_pasien->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">Alamat</td>
                                <td width="1%">:</td>
                                <td class="font-bold">{{ $data_pasien->alamat }} <br><span id="provinsi">-</span>,<span id="kabupaten"></span> <span id="kecamatan"></span></td>
                            </tr>

                            <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <td width="20%" class="p-4">No. HP : </td>
                                <td width="1%">:</td>
                                <td class="font-bold">
                                    {{ $data_pasien->no_hp }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="heading flex-auto mt-5">
                        <h4 class="font-bold tracking-tighter text-base text-theme-text mb-3">
                            Detail Layanan
                        </h4>
                        <hr>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-5">
                            <tbody class="border p-4 w-full">
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Dokter</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $dokter->name }}</td>
                                </tr>
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Klinik</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $dokter->poliklinik->name }}</td>
                                </tr>
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Jenis Pembayaran</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $jenis_pembayaran }}</td>
                                </tr>
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Tanggal Kunjungan</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $tanggal_kunjungan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="flex justify-end content-center items-center">
                    <div>
                        <x-primary-button type="button" id="btn-konfirmasi" data-id="{{ $dokter->id }}" data-modal-target="static-modal" data-modal-toggle="static-modal">Konfirmasi Pendaftaran</x-primary-button>
                    </div>
                    <div>
                        <a href="{{ route('dashboard.pasien') }}" class="bg-white text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-3 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Batal Konfirmasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
    <div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-center p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Selamat Datang
                    </h3>
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
                        {!! $simple !!}
                    </div>
                    <div>
                        <h4 id="qrcode" class="text-xl font-bold text-center text-gray-900 dark:text-white">-</h4>
                    </div>
                    <center>
                        <small> Catatan : Harap datang 1 Jam sebelum pemeriksaan</small>
                    </center>
                </div>
                <!-- Modal footer -->
                <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <a href="{{route('pasien.qrcode',Session::has('kodeUnik') ? Session::get('kodeUnik') : 1)}}" download id="cetakQrCodeButton" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cetak QRCode</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
