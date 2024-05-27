<x-app-layout>
    <div class="p-4 sm:ml-64 pt-20 h-full">
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
                <form action="{{ route('dokter.update',$data->id) }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div>
                        <x-label-default for="" value="Nama Dokter">Nama Dokter</x-label-default>
                        <x-input-default name="name" type="text" value="{{ old('name',$data->name) }}" placeholder="Masukkan Nama Dokter"></x-input-default>
                    </div>
                    <div>
                        <x-label-default for="" value="Nama Poliklinik">Nama Poliklinik</x-label-default>
                        <select id="poliklinik" name="poliklinik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Pilih Poliklinik</option>
                            @foreach ($poliklinik as $item)
                                <option value="{{ $item->id }}" {{ $data->poliklinik_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div>
                        <x-label-default for="" value="Jam Praktek">Jam Praktek</x-label-default>
                        @php
                            $jam = explode('-',$data->jam_praktek);
                            $dari = $jam[0];
                            $sampai = $jam[1];
                        @endphp
                        <div class="flex items-center w-full">
                            <div class="relative w-1/2">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="time" name="dari" id="dari" value="{{ $dari }}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                            </div>
                            <span class="mx-4 text-gray-500">to</span>
                            <div class="relative w-1/2">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="time" name="sampai" id="sampai" value="{{ $sampai }}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                            </div>
                        </div>
                    </div> --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label-default for="" value="Kuota Umum">Kuota Umum</x-label-default>
                            <x-input-default name="kuota" type="number" value="{{ old('kuota',$data->kuota) }}" placeholder="Masukkan Kuota"></x-input-default>
                            <small class="text-xs text-red-800">Kosongin jika tidak memiliki Kuota</small>
                        </div>
                        <div>
                            <x-label-default for="" value="Kuota BPJS">Kuota BPJS</x-label-default>
                            <x-input-default name="kuota_bpjs" type="number" value="{{ old('kuota_bpjs',$data->kuota_bpjs) }}" placeholder="Masukkan Kuota"></x-input-default>
                            <small class="text-xs text-red-800">Kosongin jika tidak memiliki Kuota</small>
                        </div>
                    </div>
                    <div>
                        <x-label-default for="" value="Jenis Kelamin">Jenis Kelamin</x-label-default>
                        <div class="col-span-2 sm:col-span-2">
                            <select id="jenis_kelamin" name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Pilih Jenis Kelamin</option>
                                <option value="l" {{ $data->jenis_kelamin == 'l' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="p" {{ $data->jenis_kelamin == 'p' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <x-label-default>Gambar</x-label-default>
                        <div>
                            <figure class="max-w-lg">
                                <img src="{{ $data->gambar != null ? asset('storage/dokter/'.$data->gambar) : 'https://flowbite.com/docs/images/examples/image-2@2x.jpg' }}" class="h-96 max-w-full rounded-lg photosPreview">
                                <figcaption class="mt-2 text-sm text-start text-gray-500 dark:text-gray-400">Image Preview</figcaption>
                            </figure>
                        </div>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file_input" aria-describedby="file_input_help"
                                id="file_input"
                                type="file"
                                name="file_input">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>
                    </div>
                    <hr>
                    <div class="flex justify-end align-middle content-center bg-slate-100 p-3 rounded-md">
                        <div>
                            <x-primary-button type="submit">Simpan</x-primary-button>
                        </div>
                        <div>
                            <x-danger-button type="reset">Batal</x-danger-button>
                        </div>

                    </div>
                </form>
            </div>
        </section>
    </div>
</x-app-layout>
