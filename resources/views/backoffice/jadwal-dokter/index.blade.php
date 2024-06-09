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
                @if (!Auth::user()->hasRole('petugas-pendaftaran'))
                    <div class="layout lg:flex grid grid-cols-1 lg:mt-0 mt-5 justify-end gap-5">
                        <div class="button-wrapper gap-2 flex lg:justify-end">
                            <x-a-primary-button href="{{ route('jadwal-dokter.create') }}">
                                <svg class="w-3.5 h-3.5 me-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                </svg>
                                Tambah Data</x-a-primary-button>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <div class="mb-5">
                    <form class="flex justify-end max-w-lg" action="{{ route('jadwal-dokter.index') }}" method="GET">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2"/>
                                </svg>
                            </div>
                            <input type="text" id="simple-search" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pencarian nama Dokter dan Poliklinik..." />
                        </div>
                        <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </form>
                </div>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="border">
                            <th class="px-4 py-3 border" rowspan="2" align="center">No</th>
                            <th scope="col" class="px-4 py-3 border" rowspan="2" align="center">Nama Dokter</th>
                            <th scope="col" class="px-4 py-3 border" rowspan="2" align="center">Poliklinik</th>
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
                                <td class="px-4 py-3 border font-bold text-sm" align="center">{{ $item->poliklinik->name }}</td>
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
                                    <div class="inline-flex rounded-md shadow-sm">
                                        <a href="{{ route('jadwal-dokter.show',$item->id) }}" aria-current="page" class="px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                          Show
                                        </a>
                                        <a href="{{ route('jadwal-dokter.edit',$item->id) }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                            Edit
                                        </a>
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('dokter'))
                                            <a href="{{ route('jadwal-dokter.destroy', $item->id) }}" data-confirm-delete="true" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                                Hapus
                                            </a>
                                        @endif
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
