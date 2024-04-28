<x-app-layout>
    <div class="p-4 sm:ml-64 mt-20 h-fit">
        <div class=" max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto ">
            <div class="bg-blue-800 p-4 rounded-t-lg flex content-center gap-2">
                <a href="{{ route('pasien.ketentuan') }}">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                      </svg>
                </a>
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">Pilih Poliklinik </h4>
            </div>
            <div class="p-5 space-y-4">
                <div class="mt-5">
                    <form class="flex items-center max-w-lg mx-auto">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2"/>
                                </svg>
                            </div>
                            <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pencarian Poliklinik..." required />
                        </div>
                        <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </form>
                </div>
                <div class="w-full grid grid-cols-3 gap-5 ">
                    <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-full bg-cover w-fit mx-auto p-4" src="{{ asset('img/image 16.png') }}" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">KLINIK SPESIALIS PERIODONSIA</h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">(PERAWATAN PENYANGGA GIGI)</p>
                        </div>
                    </div>
                    <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-full bg-cover w-fit mx-auto p-4" src="{{ asset('img/image 16.png') }}" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">KLINIK SPESIALIS  ORTODONSIA</h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">(PERAWATAN MERAPIKAN GIGI)</p>
                        </div>
                    </div>
                    <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-full bg-cover w-fit mx-auto p-4" src="{{ asset('img/image 16.png') }}" alt="" />
                        </a>
                        <div class="p-5 text-center">
                            <a href="#">
                                <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">KLINIK INTEGRASI 1</h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">(PEMERIKSAAN PASIEN MAHASISWA PROFESI)</p>
                        </div>
                    </div>



                </div>


            </div>
        </div>
    </div>
</x-app-layout>
