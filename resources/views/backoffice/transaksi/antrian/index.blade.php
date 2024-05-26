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
                    <form action="{{ route('antrian-klinik.search') }}" method="GET" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-label-default>Dokter</x-label-default>
                                <select name="dokter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="">
                                    <option value="">Pilih Dokter</option>
                                    @foreach ($dokter as $item)

                                        <option value="{{ $item->id }}" {{  request('dokter') == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label-default>Poliklinik</x-label-default>
                                <select name="poliklinik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="">
                                    <option value="">Pilih Poliklinik</option>
                                    @foreach ($poliklinik as $item)
                                        <option value="{{ $item->id }}" {{  request('poliklinik') == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label-default>Tanggal</x-label-default>
                                <input name="tanggal" datepicker  value="{{ request('tanggal') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pilih tanggal">
                            </div>
                            <div class="flex align-bottom content-end items-end">
                                <div>
                                    <x-primary-button type="submit">Fitler</x-primary-button>
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
                            <th scope="col" class="px-4 py-3">Poliklinik</th>
                            <th scope="col" class="px-4 py-3">Dokter</th>
                            <th scope="col" class="px-4 py-3">Jam Praktek</th>
                            <th scope="col" class="px-4 py-3">Antrian</th>
                            <th scope="col" class="px-4 py-3">Keterangan</th>
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
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->pasien->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->jenis_pembayaran) }}</td>
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
                                    <button id="{{ $item->id }}-button" data-dropdown-toggle="{{ $item->id }}-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div id="{{ $item->id }}-dropdown" class="hidden z-50 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{ $item->id }}-button">
                                            <li>
                                                <a href="{{ route('antrian-klinik.update-manual',['id' => $item->id, 'status' => 'batal']) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white show-data">Batal</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('antrian-klinik.update-manual',['id' => $item->id, 'status' => 'selesai']) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white edit-data">Selesai</a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
