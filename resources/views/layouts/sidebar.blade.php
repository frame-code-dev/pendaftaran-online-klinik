<aside id="logo-sidebar" class="fixed top-0 left-0 z-5 w-64 h-screen pt-20 transition-transform -translate-x-full bg-blue-800 border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
    <div class="h-full px-5 py-3 pb-4 overflow-y-auto bg-blue-800 dark:bg-gray-800">
       <ul class="space-y-3 font-medium text-sm">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-900 dark:hover:bg-gray-700 group">
                    <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.143 1H1.857A.857.857 0 0 0 1 1.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 7 6.143V1.857A.857.857 0 0 0 6.143 1Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 17 6.143V1.857A.857.857 0 0 0 16.143 1Zm-10 10H1.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 7 16.143v-4.286A.857.857 0 0 0 6.143 11Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z"/>
                    </svg>
                    <span class="ms-3 font-semibold text-white">Dashboard</span>
                </a>
            </li>
            <hr class="border-blue-900 border dark:border-gray-700">
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-blue-900 dark:text-white dark:hover:bg-gray-700" aria-controls="master-data" data-collapse-toggle="master-data">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-white dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M6 5a2 2 0 0 1 2-2h4.157a2 2 0 0 1 1.656.879L15.249 6H19a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2v-5a3 3 0 0 0-3-3h-3.22l-1.14-1.682A3 3 0 0 0 9.157 6H6V5Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M3 9a2 2 0 0 1 2-2h4.157a2 2 0 0 1 1.656.879L12.249 10H3V9Zm0 3v7a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-7H3Z" clip-rule="evenodd"/>
                    </svg>
                      <span class="flex-1 text-sm ms-3 text-left rtl:text-right whitespace-nowrap">Master Data</span>
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                      </svg>
                </button>
                <ul id="master-data" class=" py-2 space-y-2 bg-blue-900 rounded mt-3 {{ Request::segment(2) == 'master-data' ? 'block' : 'hidden' }}">
                    <li class="">
                         <a href="{{ route('petugas.index') }}" class="{{ Request::segment(3) == 'petugas' ? 'active-dropdown' : '' }} flex items-center w-full p-2 text-white transition duration-75 pl-4 group hover:bg-blue-950 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                            </svg>
                            Data Petugas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dokter.index') }}" class="{{ Request::segment(3) == 'dokter' ? 'active-dropdown' : '' }} flex items-center w-full p-2 text-white transition duration-75 pl-4 group hover:bg-blue-950 dark:text-white dark:hover:bg-gray-700">
                           <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                           </svg>
                           Data Dokter
                       </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-white transition duration-75 pl-4 group hover:bg-blue-950 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                            </svg>
                            Data Jadwal Dokter
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('poliklinik.index') }}" class="{{ Request::segment(3) == 'poliklinik' ? 'active-dropdown' : '' }} flex items-center w-full p-2 text-white transition duration-75 pl-4 group hover:bg-blue-950 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                            </svg>
                            Data Poliklinik
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-white transition duration-75 pl-4 group hover:bg-blue-950 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                            </svg>
                            Data Pasien
                        </a>
                    </li>



                </ul>
             </li>

        </ul>
    </div>
</aside>
