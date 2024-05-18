<x-app-layout>
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
                            <th scope="col" class="px-4 py-3">Estimasi Dilayani</th>
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
                                        <img class="h-auto max-w-12 rounded-lg" src="{{ asset('storage/files-bpjs/'.$item->gambar) }}">
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
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  $item->dokter->jam_praktek }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  $item->no_antrian != null ? $item->no_antrian : '-' }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                    <span type="button" class="text-white bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-{{ $item->status_verifikasi == 'sudah-verifikasi' ? '700' : '500' }}
                                        hover:bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-800 focus:ring-4
                                        focus:outline-none focus:ring-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-300
                                        font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                        me-2 dark:bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-600
                                        dark:hover:bg-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-700
                                        dark:focus:ring-{{ $item->status_verifikasi == 'sudah-verifikasi' ? 'green' : 'gray' }}-800">
                                        {{ $item->status_verifikasi == 'sudah-verifikasi' ? 'Sudah Verifikasi' : 'Belum Verifikasi' }}
                                    </span>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
