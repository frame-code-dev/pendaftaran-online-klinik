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
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto mb-5">
                    <form action="{{ route('history-transaksi.search') }}" method="GET" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <x-label-default for="" value="Tanggal Kunjungan">Tanggal Kunjungan</x-label-default>
                                <div date-rangepicker class="flex items-center w-full">
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                            </svg>
                                        </div>
                                        <input name="start" value="{{ request('start') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start">
                                    </div>
                                    <span class="mx-4 text-gray-500">to</span>
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                            </svg>
                                        </div>
                                        <input name="end" value="{{ request('end') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end">
                                    </div>
                                </div>
                            </div>
                            <div class="flex align-bottom content-end items-end">
                                <div>
                                    <x-primary-button type="submit">Filter</x-primary-button>
                                </div>
                                <div>
                                    <a href="{{ route('history-transaksi.pdf', request()->all()) }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <svg class="w-3.5 h-3.5 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('history-transaksi.excel', request()->all()) }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <svg class="w-3.5 h-3.5 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m8-2h3m-3 3h3m-4 3v6m4-3H8M19 4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 12v6h8v-6H8Z"/>
                                        </svg>
                                        Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Kunjungan</th>
                            <th scope="col" class="px-4 py-3">No.RM</th>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="px-4 py-3">Pembayaran</th>
                            <th scope="col" class="px-4 py-3">Jenis Pendaftaran</th>
                            <th scope="col" class="px-4 py-3">Poliklinik</th>
                            <th scope="col" class="px-4 py-3">Dokter</th>
                            <th scope="col" class="px-4 py-3">Jam Praktek</th>
                            <th scope="col" class="px-4 py-3">Antrian</th>
                            <th scope="col" class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->tanggal_kunjungan }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->pasien->no_rm }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->pasien->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->jenis_pembayaran) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->jenis_pendaftaran) }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
