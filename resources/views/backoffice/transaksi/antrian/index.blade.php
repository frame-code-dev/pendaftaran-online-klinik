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
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label-default>Dokter</x-label-default>
                                <select name="dokter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="">
                                    @foreach ($dokter as $item)
                                        <option value="{{ $item->id }}" {{  request('dokter') == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label-default>Poliklinik</x-label-default>
                                <select name="poliklinik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="">
                                    @foreach ($poliklinik as $item)
                                        <option value="{{ $item->id }}" {{  request('poliklinik') == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
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
                            <th scope="col" class="px-4 py-3">No.RM</th>
                            <th scope="col" class="px-4 py-3">No. Telephone</th>
                            <th scope="col" class="px-4 py-3">Kunjungan</th>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="px-4 py-3">Pembayaran</th>
                            <th scope="col" class="px-4 py-3">Poliklinik</th>
                            <th scope="col" class="px-4 py-3">Dokter</th>
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
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->no_kartu != null ? ucwords($item->no_kartu) : '-' }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->pasien->no_hp }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->tanggal_kunjungan }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->pasien->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->jenis_pembayaran) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->poliklinik->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->dokter->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  $item->no_antrian }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->status_pendaftaran) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                                    <a href="{{ route('antrian-klinik.update-manual',$item->id) }}" type="button" class="text-white bg-{{ $item->status_pendaftaran == 'pending' ? 'green' : 'gray' }}-{{ $item->status_pendaftaran == 'pending' ? '700' : '500' }}
                                        hover:bg-{{ $item->status_pendaftaran == 'pending' ? 'green' : 'gray' }}-800 focus:ring-4
                                        focus:outline-none focus:ring-{{ $item->status_pendaftaran == 'pending' ? 'green' : 'gray' }}-300
                                        font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                        me-2 dark:bg-{{ $item->status_pendaftaran == 'pending' ? 'green' : 'gray' }}-600
                                        dark:hover:bg-{{ $item->status_pendaftaran == 'pending' ? 'green' : 'gray' }}-700
                                        dark:focus:ring-{{ $item->status_pendaftaran == 'pending' ? 'green' : 'gray' }}-800">
                                       Update Data Kunjungan
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
