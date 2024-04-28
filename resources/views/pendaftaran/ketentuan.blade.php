<x-app-layout>
    <div class="p-4 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-7xl bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto ">
            <div class="bg-blue-800 p-4 rounded-t-lg">
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">Ketentuan Umum Pendaftaran Online</h4>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <ol class="max-w-full space-y-1 text-gray-500 list-decimal list-inside dark:text-gray-400">
                        <li>
                            <span class="font-semibold text-gray-900 dark:text-white">Bonnie Green</span> with <span class="font-semibold text-gray-900 dark:text-white">70</span> points
                        </li>
                        <hr>
                        <li>
                            <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span> with <span class="font-semibold text-gray-900 dark:text-white">63</span> points
                        </li>
                        <hr>

                        <li>
                            <span class="font-semibold text-gray-900 dark:text-white">Leslie Livingston</span> with <span class="font-semibold text-gray-900 dark:text-white">57</span> points
                        </li>
                    </ol>
                </div>

                <div class="flex justify-end">
                    <x-a-primary-button href="{{ route('pasien.jenis-pembayaran') }}">Lanjutkan</x-a-primary-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
