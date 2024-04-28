<x-app-layout>
    <div class="p-4 sm:ml-64 mt-20 h-fit">
        <div class="text-center py-10">
            <h1 class="font-bold text-3xl dark:text-white">SELAMAT DATANG DI SISTEM INFORMASI PENDAFTARAN ONLINE RUMAH</h1>
            <h5 class="font-medium text-lg dark:text-white">SAKIT GIGI DAN MULUT UNIVERSITAS JEMBER</h5>
        </div>
        <hr>

        <div class="grid grid-cols-3 gap-2 h-fit p-5 md:px-20">
            <a href="{{ route('pasien.ketentuan') }}">
                <div class="max-w-lg bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative mt-40 h-full hover:border-2 hover:border-blue-600">
                    <div class="absolute top-36 left-1/2 transform -translate-x-1/2 -translate-y-full w-11/12">
                        <img class="object-cover w-full h-64 rounded-lg" src="{{ asset('img/image 16.png') }}" alt="" />
                    </div>
                    <div class="p-5 text-center w-full relative top-[65%]">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pendaftaran Online</h5>
                    </div>
                </div>
            </a>
            <a href="#">
                <div class="max-w-lg bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative mt-40 h-full hover:border-2 hover:border-blue-600">
                    <div class="absolute top-36 left-1/2 transform -translate-x-1/2 -translate-y-full w-11/12">
                        <img class="object-cover w-full h-64 rounded-lg" src="{{ asset('img/image 16.png') }}" alt="" />
                    </div>
                    <div class="p-5 text-center w-full relative top-[65%]">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Jadwal Dokter</h5>
                    </div>
                </div>
            </a>
            <a href="#">
                <div class="max-w-lg bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative mt-40 h-full hover:border-2 hover:border-blue-600">
                    <div class="absolute top-36 left-1/2 transform -translate-x-1/2 -translate-y-full w-11/12">
                        <img class="object-cover w-full h-64 rounded-lg" src="{{ asset('img/image 16.png') }}" alt="" />
                    </div>
                    <div class="p-5 text-center w-full relative top-[65%]">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Riwayat Kunjungan</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
