<x-app-layout>
    <div class="p-4 px-10 sm:ml-64 mt-20 h-fit">
        <div class=" card bg-white p-5 pt-0 px-0 mt-4 border rounded-md w-full relative overflow-x-auto">
            <div class="bg-blue-800 p-4 rounded-t-lg flex gap-2">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center ">
                            <a href="{{ route('pasien.jenis-pembayaran') }}" class=" text-sm inline-flex items-center font-medium text-white hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                                </svg>
                                Jenis Pembayaran
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class=" italic font-medium text-white">{{ Session::get('jenis-pembayaran') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="w-full mx-auto space-y-4 px-5">
                <form action="{{ route('pasien.jenis-pembayaran-bpjs.store') }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-2 space-y-4">
                        <div>
                            <div class="no_kartu">
                                <x-label-default for="" value="No. BPJS">No. BPJS</x-label-default>
                                <x-input-default name="no_bpjs" type="text" value="{{ old('no_bpjs') }}" placeholder="Masukkan No. BPJS"></x-input-default>
                            </div>
                        </div>
                        <div>
                            <x-label-default for="" value="Upload Surat Rujukan/SKDP">Upload Surat Rujukan/SKDP</x-label-default>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file_input" aria-describedby="file_input_help"
                                    id="file_input"
                                    type="file"
                                    name="file_input">
                        </div>
                    </div>
                    <div class="flex justify-end align-middle content-center bg-slate-100 p-3 rounded-md">
                        <div>
                            <x-primary-button type="submit">Lanjutkan</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
