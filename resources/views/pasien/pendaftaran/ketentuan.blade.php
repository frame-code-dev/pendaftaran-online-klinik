<x-app-layout>
    <div class="p-5 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto ">
            <div class="bg-blue-800 p-4 rounded-t-lg">
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">Ketentuan Umum Pendaftaran Online</h4>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <ol class="max-w-full space-y-1 text-gray-500 list-decimal list-inside dark:text-gray-400">
                        <li class="text-lg">
                            Pendaftaran online hanya bisa dilakukan oleh pasien yang telah memiliki nomor rekam medis
                        </li>
                        <hr>
                        <li class="text-lg">
                            Pelayanan pendaftaran online dilakukan pukul <b>07. 00 – 21.00</b> WIB
                        </li>
                        <hr>
                        <li class="text-lg">
                            Pendaftaran online di lakukan <b>satu hari (H-6)</b> sebelum periksa
                        </li>
                        <hr>
                        <li class="text-lg">
                            Pilihan klinik yang dituju tidak bisa dipilih jika kuota sudah penuh
                        </li>
                        <hr>
                        <li class="text-lg">
                            Khusus pasien BPJS harus menyiapkan SKDP/Surat Rujukan untuk diupload ke dalam sistem pendaftaran online dalam bentuk pdf/png/jpg dan diharapkan dibawa juga ke rumah sakit saat verifikasi kedatangan
                        </li>
                        <hr>
                        <li class="text-lg">
                            Setelah melakukan pendaftaran, anda akan mendapatkan bukti pendaftaran dan diharapkan untuk mengunduh atau ss bukti pendaftaran tersebut
                        </li>
                        <hr>
                        <li class="text-lg">
                            Tunjukkan bukti pendaftaran tersebut kepada petugas pendaftaran di loket khusus pendaftaran online
                        </li>
                        <hr>
                        <li class="text-lg">
                            Jika pasien tidak datang ke RSGM UNEJ diharapkan langsung membatalkan kunjungan
                        </li>
                        <hr>
                        <li class="text-lg">
                            Diharapkan untuk memasukkan nomor handphone aktif dan terdaftar whatsapp untuk mengabari informasi, apabila sewaktu-waktu terdapat perubahan jadwal dokter
                        </li>
                        <hr>
                        <li class="text-lg">
                            Poliklinik libur di hari sabtu, minggu, dan tanggal merah
                        </li>

                    </ol>
                </div>

                <div class="flex justify-end">
                    <x-a-primary-button href="{{ route('pasien.jenis-pembayaran') }}">Lanjutkan</x-a-primary-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
