<x-app-layout>
    @push('js')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <script>

        $(function() {
            var today = new Date();
            var maxDate = new Date();
            maxDate.setDate(today.getDate() + 7); // 6 days from today
            var disabledDates = []; // array to store disabled dates
            let url_holiday = "https://api-harilibur.vercel.app/api?year=2024"
            var publicHolidays = []; // array to store public holidays
            $.ajax({
                url: url_holiday,
                type: "GET",
                success: function(data) {
                    console.log(data);
                    data.map((item) => {
                        var parts = item.holiday_date.split("-");
                        publicHolidays.push(new Date(parts[0], parts[1] - 1, parts[2]));
                    })
                    // Initialize datepicker after fetching holidays
                    initializeDatepicker();
                }
            })

            function initializeDatepicker() {
                // Loop to disable weekends (Saturday and Sunday)
                for (var i = 0; i <= 7; i++) {
                    var tempDate = new Date();
                    tempDate.setDate(today.getDate() + i);
                    if (tempDate.getDay() === 0 || tempDate.getDay() === 6) {
                        disabledDates.push(tempDate);
                    }
                }


                $("#datepicker").datepicker({
                    dateFormat: "yy-mm-dd",
                    defaultDate: today,
                    minDate: today,
                    maxDate: maxDate,
                    beforeShowDay: function(date) {
                        var day = date.getDay();
                        // Disable weekends
                        if (day === 0 || day === 6) {
                            return [false];
                        }
                        // Disable past and future dates
                        if (date < today || date > maxDate) {
                            return [false];
                        }
                        // Disable specific dates in disabledDates array
                        for (var i = 0; i < disabledDates.length; i++) {
                            if (date.getTime() === disabledDates[i].getTime()) {
                                return [false];
                            }
                        }
                        // Disable public holidays
                        for (var i = 0; i < publicHolidays.length; i++) {
                            if (date.getTime() === publicHolidays[i].getTime()) {
                                return [false];
                            }
                        }
                        return [true];
                    },
                    onSelect: function(dateText) {
                        var selectedDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
                        console.log(selectedDate);
                        $("#datepicker").val(selectedDate);
                        window.location.href = window.location.pathname + '?tanggal=' + selectedDate;
                        checkAndHandleDate(dateText);


                    }
                });

                $("#datepicker").on("change", function() {
                    var inputDate = $(this).val();
                    checkAndHandleDate(inputDate);
                });
            }

            function checkAndHandleDate(dateText) {
                var selectedDate = new Date(dateText);
                var isValid = true;

                // Check if the date is a weekend
                if (selectedDate.getDay() === 0 || selectedDate.getDay() === 6) {
                    isValid = false;
                }

                // Check if the date is a public holiday
                for (var i = 0; i < publicHolidays.length; i++) {
                    if (selectedDate.getTime() === publicHolidays[i].getTime()) {
                        isValid = false;
                    }
                }
                console.log(isValid);
                if (!isValid) {
                    window.location.href = window.location.pathname;
                    alert("Tanggal yang dipilih tidak valid. Silakan pilih tanggal lain.");
                    $(this).val("");
                } else {
                    window.location.href = window.location.pathname + '?tanggal=' + dateText;
                }
            }

        });
    </script>
    @endpush
    <div class="p-4 sm:ml-64 mt-20 h-fit">
        <div class=" max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto ">
            <div class="bg-blue-800 p-4 rounded-t-lg flex content-center gap-2">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center ">
                            <a href="{{ route('pasien.list-poliklinik') }}" class=" text-sm inline-flex items-center font-medium text-white hover:text-white dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                                </svg>
                                Pilih Dokter
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class=" italic font-medium text-white"> {{ Session::get('poliklinik')->name }}</span>
                            </div>
                        </li>

                    </ol>
                </nav>

            </div>
            <div class="p-5 space-y-4">
                <div class="mt-5">
                    <form class="flex items-center max-w-lg mx-auto" action="{{ route('pasien.list-dokter.search',Session::get('poliklinik')->id) }}" method="GET">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2"/>
                                </svg>
                            </div>
                            <input type="text" id="simple-search" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pencarian Dokter..." />
                        </div>
                        <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </form>
                </div>
                <hr>
                <div class="flex justify-end">
                    <div class="w-1/2 p-5 flex content-end end-0 align-bottom justify-end">
                        <div class="w-1/2">
                            <x-label-default for="" value="Tanggal Kunjungan">Tanggal Kunjungan</x-label-default>
                            <input type="text"
                                value="{{ Carbon\Carbon::parse(request('tanggal'))->format('Y-m-d') }}"
                                name="tgl_kunjungan"
                                id="datepicker"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Masukkan Tanggal Kunjungan"
                            >
                        </div>

                    </div>
                </div>
                <hr>
                <div class="w-full grid grid-cols-3 gap-5 ">
                    @forelse ($data as $item)
                        <a href="{{ route('pasien.konfirmasi-pendaftaran',$item->id) }}" class="">
                            <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 hover:border-2 hover:border-blue-600">
                                    <img class="rounded-full bg-cover w-fit mx-auto p-4" src="{{ $item->gambar != null ? asset('storage/dokter/'.$item->gambar) : 'https://flowbite.com/docs/images/examples/image-2@2x.jpg' }}" alt="" />
                                <div class="p-5 text-center">
                                        <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">{{ $item->name }}</h5>
                                    <hr>
                                    <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">{{ ucwords($item->poliklinik->name) }}</p>
                                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                        @foreach ($item->jadwal as $item_jadwal)
                                            @php
                                                $status = Session::has('jenis-pembayaran') ? Session::get('jenis-pembayaran') : 'umum';

                                            @endphp
                                            @if ($item_jadwal->status == $status)
                                                @php
                                                    $jadwalArray = $item_jadwal->toArray();
                                                    $jadwalHari = $jadwalArray[$hari_kunjungan == 'jumat' ? 'jumaat' : $hari_kunjungan] ?? null;
                                                @endphp
                                                Jadwal Praktek: {{ $jadwalHari != null ? $jadwalHari : '-' }}
                                            @endif
                                        @endforeach
                                    </p>
                                    <hr>
                                    <div class="mt-3">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                            </svg>
                                            Sisa Kuota : {{ $item->kuota_terisi }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    @empty
                        <div class="col-span-1">
                            <span class="text-center text-red-300">Tidak ada data</span>
                        </div>
                    @endforelse

                </div>


            </div>
        </div>
    </div>
</x-app-layout>
