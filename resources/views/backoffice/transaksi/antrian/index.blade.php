<x-app-layout>
    @push('js')
    <script>
        $(document).ready(function() {
           let url = `{{ route('pendaftaran-offline.list-dokter') }}`
           $('#poliklinik').on('change', function() {
               let id = $(this).val();
               $('#dokter').empty()
               $('#dokter').append(`<option value="0">Pilih Dokter</option>`)
               if (id != 0 || id != '0') {
                   $.ajax({
                       type: "GET",
                       url: url,
                       data: {
                           id:id
                       },
                       success: function(data) {
                           console.log(data);
                           $.map(data, function(obj) {
                               $('#dokter').append(`<option value="${obj.id}">${obj.name}</option>`)
                           })
                       }
                   })
               }
           })
           $('.panggil').on('click', function() {
               let id = $(this).data('id');
               console.log(id);
               getSound(id);
           })
       })
        let voices = [];

        function loadVoices() {
            voices = window.speechSynthesis.getVoices();
            console.log(voices);
        }

       function getSound(id) {
            const text = id;
            const msg = new SpeechSynthesisUtterance();
            msg.text = text;
            msg.lang = 'id-ID'; // Kode bahasa untuk Bahasa Indonesia
            console.log(voices);
            // Cari suara wanita dalam daftar suara
            const voice = voices.find(voice => voice.lang === 'id-ID' && voice.name.toLowerCase().includes('female'));

            // Jika tidak ada suara wanita, gunakan suara wanita pertama yang tersedia
            msg.voice = voice || voices.find(voice => voice.lang === 'id-ID' && voice.name.toLowerCase().includes('female')) || voices[0];

            window.speechSynthesis.cancel();
            window.speechSynthesis.speak(msg);
       }
   </script>
    @endpush
    <div class="p-4 sm:ml-64 pt-20 h-screen">
        <section class="p-5 overflow-y-auto mt-5">
            <div class="head lg:flex grid grid-cols-1 justify-between w-full">
                <div class="heading flex-auto">
                    <p class="text-blue-950 font-sm text-xs">
                        Transaksi
                    </p>
                    <h2 class="font-bold tracking-tighter text-2xl text-theme-text">
                        {{ $title }}
                    </h2>
                </div>
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto mb-5">
                    <form action="{{ route('antrian-klinik.search') }}" method="GET" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-label-default>Poliklinik</x-label-default>
                                <select id="poliklinik" required name="poliklinik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="0">Pilih Poliklinik</option>
                                    @foreach ($poliklinik as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label-default>Dokter</x-label-default>
                                <select id="dokter" required name="dokter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="0">Pilih Dokter</option>
                                </select>
                            </div>
                            <div>
                                <x-label-default>Tanggal</x-label-default>
                                <input name="tanggal" datepicker  value="{{ request('tanggal') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pilih tanggal">
                            </div>
                            <div class="flex align-bottom content-end items-end">
                                <div>
                                    <x-primary-button type="submit">Filter</x-primary-button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="datatable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Kunjungan</th>
                            <th scope="col" class="px-4 py-3">No.RM</th>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="px-4 py-3">Pembayaran</th>
                            <th scope="col" class="px-4 py-3">Poliklinik</th>
                            <th scope="col" class="px-4 py-3">Dokter</th>
                            <th scope="col" class="px-4 py-3">Jam Praktek</th>
                            <th scope="col" class="px-4 py-3">Antrian</th>
                            <th scope="col" class="px-4 py-3">Keterangan</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->tanggal_kunjungan }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->pasien->no_rm }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->pasien->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->jenis_pembayaran) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->poliklinik->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{  ucwords($item->dokter->name) }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @foreach ($item->jadwal_dokter as $item_jadwal)
                                        @php
                                            $jadwalArray = $item_jadwal->toArray();
                                            $hari_kunjungan = strtolower(\Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('l'));
                                            $jadwalHari = $jadwalArray[$hari_kunjungan == 'jumat' ? 'jumaat' : $hari_kunjungan] ?? null;
                                        @endphp
                                        {{ $jadwalHari != null ? $jadwalHari : '-' }}
                                    @endforeach
                                </td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->no_antrian != null ? $item->no_antrian : '-' }}</td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if ($item->status_pendaftaran == 'proses' || $item->status_pendaftaran == 'pending')
                                        <span class="font-bold p-3 text-yellow-500 bg-yellow-50">{{ ucwords($item->status_pendaftaran) }}</span>
                                    @elseif($item->status_pendaftaran == 'selesai')
                                        <span class="font-bold p-3 text-green-500 bg-green-50">{{ ucwords($item->status_pendaftaran) }}</span>
                                    @else
                                        <span class="font-bold p-3 text-red-500 bg-red-50">{{ ucwords($item->status_pendaftaran) }}</span>

                                    @endif
                                </td>
                                <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @php
                                        $antrian = $item->no_antrian != null ? $item->no_antrian.' '.$item->dokter->name : $item->dokter->name;
                                        $pesan = 'nomor antrean '.$antrian;
                                        // $pesan = "firdo jatuh cinta anjayyyyy";
                                    @endphp
                                    <button data-id="{{ $pesan }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 panggil">Panggil</button>
                                    <div class="inline-flex rounded-md shadow-sm">
                                        <a href="{{ route('antrian-klinik.update-manual',['id' => $item->id, 'status' => 'batal']) }}" aria-current="page" class="px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                            Batal
                                        </a>
                                        <a href="{{ route('antrian-klinik.update-manual',['id' => $item->id, 'status' => 'selesai']) }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                            Selesai
                                        </a>

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
