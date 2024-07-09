<x-app-layout>
    @push('js')
        <script>
            $('.cek-data').on('click',function() {
                let img = $(this).data('id');
                console.log(img);
                $('#skdp').attr('src',img);

            })
        </script>
    @endpush
    <div class="p-4 sm:ml-64 pt-20 h-screen">
        <section class="p-5 overflow-y-auto mt-5">
            <div class="head lg:flex grid grid-cols-1 justify-between w-full">
                <div class="heading flex-auto">
                    <p class="text-blue-950 font-sm text-xs">
                        Transaksi
                    </p>
                    <h2 class="font-bold tracking-tighter text-2xl text-theme-text">
                        {{ $title }}
                    </h2>
                </div>
                <div class="layout lg:flex grid grid-cols-1 lg:mt-0 mt-5 justify-end gap-5">
                    <div class="button-wrapper gap-2 flex lg:justify-end">
                        <x-a-primary-button href="{{ route('verifikasi.detail') }}">
                            <svg class="w-3.5 h-3.5 me-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                            Verifikasi Data Pasien Online</x-a-primary-button>
                    </div>
                </div>
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Kunjungan</th>
                            <th scope="col" class="px-4 py-3">No.RM</th>
                            <th scope="col" class="px-4 py-3">NIK</th>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="px-4 py-3">No. HP</th>
                            <th scope="col" class="px-4 py-3">Pembayaran</th>
                            <th scope="col" class="px-4 py-3">SKDP/Rujukan</th>
                            <th scope="col" class="px-4 py-3">Nomor BPJS</th>
                            <th scope="col" class="px-4 py-3">Poliklinik</th>
                            <th scope="col" class="px-4 py-3">Dokter</th>
                            <th scope="col" class="px-4 py-3">Jam Praktek</th>
                            <th scope="col" class="px-4 py-3">Antrian</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->tanggal_kunjungan }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->pasien->no_rm }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->pasien->nik }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->pasien->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="https://wa.me/{{ $item->pasien->no_hp }}" class="text-blue-700 hover:underline">{{ $item->pasien->no_hp }}</a>
                                </td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->jenis_pembayaran) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if ($item->jenis_pembayaran == 'bpjs')
                                        <img data-modal-target="default-modal" data-modal-toggle="default-modal" data-id="{{ asset('storage/files-bpjs/'.$item->gambar) }}" class="cek-data cursor-pointer h-auto max-w-12 rounded-lg" src="{{ asset('storage/files-bpjs/'.$item->gambar) }}">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->jenis_pembayaran == 'bpjs')
                                        {{ $item->no_kartu }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->poliklinik->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->dokter->name) }}</td>
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
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  $item->no_antrian != null ? $item->no_antrian : '-' }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                    <a href="{{ $item->status_verifikasi == 'belum-verifikasi' ? route('verifikasi.update-manual',$item->id) : '#' }}" type="button" class="{{ $item->status_verifikasi == 'sudah-verifikasi' ? "cursor-not-allowed " : "cursor-pointer" }} text-white bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-{{ $item->status_verifikasi == 'sudah-verifikasi' ? '700' : '500' }}
                                        hover:bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-800 focus:ring-4
                                        focus:outline-none focus:ring-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-300
                                        font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                        me-2 dark:bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-600
                                        dark:hover:bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-700
                                        dark:focus:ring-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-800">
                                        {{ $item->status_verifikasi == 'sudah-verifikasi' ? 'Sudah Verifikasi' : 'Belum Verifikasi' }}
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        SKDP/Rujukan
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
                   <img src="" alt="" id="skdp">
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
