<x-app-layout>
    <div class="p-4 sm:ml-64 pt-20 h-screen">
        <section class="p-5 overflow-y-auto mt-5">
            <div class="head lg:flex grid grid-cols-1 justify-between w-full">
                <div class="heading flex-auto">
                    <p class="text-blue-950 font-sm text-xs">
                        Master Data
                    </p>
                    <h2 class="font-bold tracking-tighter text-2xl text-theme-text">
                        {{ $title }}
                    </h2>
                </div>
                <div class="layout lg:flex grid grid-cols-1 lg:mt-0 mt-5 justify-end gap-5">
                    <div class="button-wrapper gap-2 flex lg:justify-end">
                        <x-a-primary-button href="{{ route('jadwal-dokter.create') }}">
                            <svg class="w-3.5 h-3.5 me-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                            Tambah Data</x-a-primary-button>
                    </div>
                </div>
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="border">
                            <th class="px-4 py-3 border" rowspan="2" align="center">No</th>
                            <th scope="col" class="px-4 py-3 border" rowspan="2" align="center">Nama Dokter</th>
                            <th scope="col" class="px-4 py-3 border" colspan="7" align="center">Jadwal Dokter</th>
                            <th scope="col" class="px-4 py-3 border" rowspan="2" align="center">
                                Actions
                                <span class="sr-only"></span>
                            </th>
                        </tr>
                        <tr class="border dark:border-gray-700">
                            <th scope="col" class="px-4 py-3">Senin</th>
                            <th scope="col" class="px-4 py-3">Selasa</th>
                            <th scope="col" class="px-4 py-3">Rabu</th>
                            <th scope="col" class="px-4 py-3">Kamis</th>
                            <th scope="col" class="px-4 py-3">Jumaat</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Tanggal</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokter as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 border" align="center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 border font-bold text-sm" align="center">{{ $item->name }}</td>
                                <td colspan="7" class="w-fit">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        @forelse ($item->jadwal as $item_jadwal)
                                            <tr>
                                                <td class="px-4 py-3 border">{{ ucwords($item_jadwal->senin ?? '-') }}</td>
                                                <td class="px-4 py-3 border">{{ ucwords($item_jadwal->selasa ?? '-') }}</td>
                                                <td class="px-4 py-3 border">{{ ucwords($item_jadwal->rabu ?? '-') }}</td>
                                                <td class="px-4 py-3 border">{{ ucwords($item_jadwal->kamis ?? '-') }}</td>
                                                <td class="px-4 py-3 border">{{ ucwords($item_jadwal->jumaat ?? '-') }}</td>
                                                <td class="px-4 py-3 border">{{ ucwords($item_jadwal->status ?? '-') }}</td>
                                                <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($item_jadwal->created_at)->translatedFormat('d F Y') }}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" align="center"><span class="text-red-500 font-xs">Jadwal Dokter belum di Setting</span></td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </td>
                                <td class="px-4 py-3 border" align="center">
                                        <button id="{{ $item->id }}-button" data-dropdown-toggle="{{ $item->id }}-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                        <div id="{{ $item->id }}-dropdown" class="hidden z-50 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{ $item->id }}-button">
                                                <li>
                                                    <a href="{{ route('jadwal-dokter.show',$item->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white show-data">Show</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('jadwal-dokter.edit',$item->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white edit-data">Edit</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('jadwal-dokter.destroy', $item->id) }}" data-confirm-delete="true" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
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
