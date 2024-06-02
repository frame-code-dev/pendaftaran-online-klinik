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
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('dokter.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">List Dokter</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $title }}</span>
                            </div>
                        </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative">
                <div class="flex items-start content-start gap-4">
                    <div class="w-1/2">
                        <img class="rounded-md w-96 h-96 mx-auto" src="{{ $data->gambar != null ? asset('storage/dokter/'.$data->gambar) : 'https://flowbite.com/docs/images/examples/image-2@2x.jpg' }}" alt="image description">
                    </div>
                    <div class="col-span-1 w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <tbody class="border p-4 w-full">
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Nama Dokter</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $data->name }}</td>
                                </tr>
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Poliklinik</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $data->poliklinik->name }}</td>
                                </tr>
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Jenis Kelamin</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">{{ $data->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <td width="20%" class="p-4">Status Dokter</td>
                                    <td width="1%">:</td>
                                    <td class="font-bold">
                                        <span class="cursor-pointer bg-{{ $data->status == 'aktif' ? 'green' : 'red' }}-100 text-{{ $data->status == 'aktif' ? 'green' : 'red' }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-{{ $data->status == 'aktif' ? 'green' : 'red' }}-900 dark:text-{{ $data->status == 'aktif' ? 'green' : 'red' }}-300">{{ ucwords($data->status) }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
