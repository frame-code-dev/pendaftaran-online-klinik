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
            })
        </script>
    @endpush
    <div class="p-4 px-10 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto max-h-fit pb-96">
            <div class="bg-blue-800 p-4 rounded-t-lg flex gap-2">
                <a href="{{ route('dashboard.pasien') }}">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                      </svg>
                </a>
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">Jadwal Dokter </h4>
            </div>
            <form class="w-full" action="{{ route('pasien.list-jadwal-dokter.search') }}" method="GET">
                <div class="grid grid-cols-3 gap-3 p-5">
                    <div class="col-span-2 grid grid-cols-2 gap-3">
                        <div>
                            <x-label-default for="" value="Nama Poliklinik">Nama Poliklinik</x-label-default>
                            <select id="poliklinik" required name="poliklinik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Pilih Poliklinik</option>
                                @foreach ($poliklinik as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-label-default for="" value="Pilih Dokter">Pilih Dokter</x-label-default>
                            <select id="dokter" required name="dokter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Pilih Dokter</option>
                            </select>
                        </div>
                    </div>
                    <div class="align-middle content-center mt-6">
                        <x-primary-button type="submit">Filter Data</x-primary-button>
                    </div>
                </div>
            </form>
            <hr>
            @if ($data != null)
                <div class="w-full mx-auto space-y-4 mt-5 p-5">
                    <div class="flex items-start content-start gap-4">
                        <div class="w-1/2">
                            <img class="rounded-md w-96 h-96 mx-auto" id="gambar" src="{{ $data->gambar != null ? asset('storage/dokter/'.$data->gambar) : 'https://flowbite.com/docs/images/examples/image-2@2x.jpg' }}" alt="image description">
                        </div>
                        <div class="col-span-1 w-full space-y-4">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <tbody class="border p-4 w-full">
                                    <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <td width="20%" class="p-4">Nama Dokter</td>
                                        <td width="1%">:</td>
                                        <td class="font-bold">{{ $data->name }}</td>
                                    </tr>
                                    <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <td width="20%" class="p-4">Poliklinik</td>
                                        <td width="1%">:</td>
                                        <td class="font-bold">{{ $data->poliklinik->name }}</td>
                                    </tr>
                                    <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <td width="20%" class="p-4">Jenis Kelamin</td>
                                        <td width="1%">:</td>
                                        <td class="font-bold">{{ $data->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <td width="20%" class="p-4">Status Dokter</td>
                                        <td width="1%">:</td>
                                        <td class="font-bold">
                                            <span class="cursor-pointer bg-{{ $data->status == 'aktif' ? 'green' : 'red' }}-100 text-{{ $data->status == 'aktif' ? 'green' : 'red' }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-{{ $data->status == 'aktif' ? 'green' : 'red' }}-900 dark:text-{{ $data->status == 'aktif' ? 'green' : 'red' }}-300">{{ ucwords($data->status) }}</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border " >
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 border bg-gray-300">Status</th>
                                        <th scope="col" class="px-4 py-3 border">Senin</th>
                                        <th scope="col" class="px-4 py-3 border bg-gray-300">Selasa</th>
                                        <th scope="col" class="px-4 py-3 border">Rabu</th>
                                        <th scope="col" class="px-4 py-3 border bg-gray-300">Kamis</th>
                                        <th scope="col" class="px-4 py-3 border">Jumaat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->jadwal as $item)
                                    <tr class="border">
                                        <td class="px-4 py-3 border bg-gray-300">
                                            <x-input-default name="status_{{ $item->status }}[]" type="text" value="{{ old('status',$item->status) }}" class="{{ $item->status  == 'umum' ? 'bg-blue-300' : 'bg-gray-800 text-white' }}" style="width: 100px" readonly></x-input-default>
                                        </td>
                                        <td class="px-4 py-3 border ">
                                            @php
                                                $jam = explode('-',$item->senin);
                                                $dari = $jam[0];
                                                $sampai = $jam[1];
                                            @endphp
                                            <div>
                                                <div class="flex items-center w-full">
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dari }}" name="daristatus_{{ $item->status }}_senin" id="dari" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                    <span class="mx-4 text-gray-500">to</span>
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $sampai }}" name="sampaistatus_{{ $item->status }}_senin" id="sampai" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border bg-gray-300">
                                            @php
                                                $jam = explode('-',$item->selasa);
                                                $dari = $jam[0];
                                                $sampai = $jam[1];
                                            @endphp
                                            <div>
                                                <div class="flex items-center w-full">
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dari }}" name="daristatus_{{ $item->status }}_selasa" id="dari" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                    <span class="mx-4 text-gray-500">to</span>
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $sampai }}" name="sampaistatus_{{ $item->status }}_selasa" id="sampai" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border ">
                                            @php
                                                $jam = explode('-',$item->rabu);
                                                $dari = $jam[0];
                                                $sampai = $jam[1];
                                            @endphp
                                            <div>
                                                <div class="flex items-center w-full">
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dari }}" name="daristatus_{{ $item->status }}_rabu" id="dari" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                    <span class="mx-4 text-gray-500">to</span>
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $sampai }}" name="sampaistatus_{{ $item->status }}_rabu" id="sampai" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border bg-gray-300">
                                            @php
                                                $jam = explode('-',$item->kamis);
                                                $dari = $jam[0];
                                                $sampai = $jam[1];
                                            @endphp
                                            <div>
                                                <div class="flex items-center w-full">
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dari }}" name="daristatus_{{ $item->status }}_kamis" id="dari" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                    <span class="mx-4 text-gray-500">to</span>
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $sampai }}" name="sampaistatus_{{ $item->status }}_kamis" id="sampai" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border ">
                                            @php
                                                $jam = explode('-',$item->jumaat);
                                                $dari = $jam[0];
                                                $sampai = $jam[1];
                                            @endphp
                                            <div>
                                                <div class="flex items-center w-full">
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dari }}" name="daristatus_{{ $item->status }}_jumaat" id="dari" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                    <span class="mx-4 text-gray-500">to</span>
                                                    <div class="relative w-1/2">
                                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input type="text" readonly value="{{ $sampai }}" name="sampaistatus_{{ $item->status }}_jumaat" id="sampai" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <span class="text-center font-bold text-sm italic p-5 mt-4">Data Tidak Ditemukan</span>
            @endif
        </div>
    </div>

</x-app-layout>
