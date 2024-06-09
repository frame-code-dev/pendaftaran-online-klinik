<x-app-layout>
    @include('backoffice.dokter.modal.update-status')
    @push('js')
        <script>
            // update status
            $(document).ready(function() {
                $('.update-modal').click(function() {
                    var id = $(this).data('id');
                    $('#id').val(id);
                })
            })
            $(document).ready(function() {
                $('.dropdown').on('click',function() {
                    let id = $(this).data('id');
                    console.log(id);
                })
            })
        </script>
    @endpush
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
                        <x-a-primary-button href="{{ route('dokter.create') }}">
                            <svg class="w-3.5 h-3.5 me-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                            Tambah Data</x-a-primary-button>
                    </div>
                </div>
                @endif
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Foto</th>
                            <th scope="col" class="px-4 py-3">Nama Dokter</th>
                            <th scope="col" class="px-4 py-3">Nama Poliklinik</th>
                            <th scope="col" class="px-4 py-3">Kuota UMUM</th>
                            <th scope="col" class="px-4 py-3">Kuota BPJS</th>
                            <th scope="col" class="px-4 py-3">Sisa Kuota UMUM</th>
                            <th scope="col" class="px-4 py-3">Sisa Kuota BPJS</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <img class="h-auto max-w-12 rounded-lg" src="{{ $item->gambar != null ? asset('storage/dokter/'.$item->gambar) : 'https://flowbite.com/docs/images/examples/image-2@2x.jpg' }}" alt="image description">
                                </td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->name }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ ucwords($item->poliklinik->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->kuota != null ? $item->kuota : '-' }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->kuota_bpjs != null ? $item->kuota_bpjs : '-' }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->kuota_terisi_umum ?? 0 }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->kuota_terisi_bpjs ?? 0 }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <span data-modal-target="update-modal" data-modal-toggle="update-modal" data-id="{{ $item->id }}" class="update-modal cursor-pointer bg-{{ $item->status == 'aktif' ? 'green' : 'red' }}-100 text-{{ $item->status == 'aktif' ? 'green' : 'red' }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-{{ $item->status == 'aktif' ? 'green' : 'red' }}-900 dark:text-{{ $item->status == 'aktif' ? 'green' : 'red' }}-300">{{ ucwords($item->status) }}</span>
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <div class="inline-flex rounded-md shadow-sm">
                                        <a href="{{ route('dokter.show',$item->id) }}" aria-current="page" class="px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                          Show
                                        </a>
                                        <a href="{{ route('dokter.edit',$item->id) }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                            Edit
                                        </a>
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('dokter'))
                                            <a href="{{ route('dokter.destroy', $item->id) }}" data-confirm-delete="true" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
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
