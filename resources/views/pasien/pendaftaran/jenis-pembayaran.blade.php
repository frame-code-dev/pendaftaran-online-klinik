<x-app-layout>
    <div class="p-4 px-10 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto max-h-fit pb-96">
            <div class="bg-blue-800 p-4 rounded-t-lg flex gap-2">
                <a href="{{ route('pasien.ketentuan') }}">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                      </svg>
                </a>
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">Pilih Jenis Pembayaran </h4>
            </div>
            <div class="grid grid-cols-2 gap-10 h-fit p-5 md:px-20">
                <a href="{{ route('pasien.jenis-pembayaran-bpjs') }}">
                    <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative h-full hover:border-2 hover:border-blue-600 p-5">
                        <img class=" mx-auto h-64 rounded-lg" src="{{ asset('img/image 30.png') }}" alt="" />
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center mt-10">BPJS</h5>
                    </div>
                </a>
                <a href="{{ route('pasien.list-poliklinik') }}">
                    <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative h-full hover:border-2 hover:border-blue-600 p-5">
                        <img class=" mx-auto h-64 rounded-lg" src="{{ asset('img/image 20.png') }}" alt="" />
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center mt-10">UMUM</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>

</x-app-layout>
