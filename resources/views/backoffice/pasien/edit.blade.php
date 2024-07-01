<x-app-layout>
    @push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: 0.35rem !important;
            border: 1px solid #d1d3e2;
            height: calc(1.95rem + 10px);
            background: #fff;
        }

        .select2-container--default .select2-selection--single:hover,
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default .select2-selection--single.active {
            box-shadow: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;

        }

        .select2-container--default .select2-selection--multiple {
            border-color: #eaeaea;
            border-radius: 0;
        }

        .select2-dropdown {
            border-radius: 0;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3838eb;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #eaeaea;
            background: #fff;

        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            top: 5px;
        }
    </style>
    @endpush
    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
        var urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
        var urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";
        var urlKelurahan = "https://ibnux.github.io/data-indonesia/kelurahan/";
        let provinsi_id = `{{ $data->provinsi_id }}`
        let kabupaten_id = `{{ $data->kabupaten_id }}`
        let kecamatan_id = `{{ $data->kecamatan_id }}`
        let desa_id = `{{ $data->desa_id }}`
        function clearOptions(id) {
            console.log("on clearOptions :" + id);

            //$('#' + id).val(null);
            $('#' + id).empty().trigger('change');
        }

        console.log('Load Provinsi...');
        $.getJSON(urlProvinsi, function (res) {

            res = $.map(res, function (obj) {
                obj.text = obj.nama
                return obj;
            });

            data = [{
                id: "",
                nama: "- Pilih Provinsi -",
                text: "- Pilih Provinsi -"
            }].concat(res);

            //implemen data ke select provinsi
            $("#select2-provinsi").select2({
                dropdownAutoWidth: true,
                width: '100%',
                data: data
            })

            if (provinsi_id) {
                $("#select2-provinsi").val(provinsi_id).trigger('change');
            }
        });

        var selectProv = $('#select2-provinsi');
        $(selectProv).change(function () {
            var value = $(selectProv).val();
            console.log(value);
            clearOptions('select2-kabupaten');

            if (value) {
                console.log("on change selectProv");

                var text = $('#select2-provinsi :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Kabupaten di '+text+'...')
                $.getJSON(urlKabupaten + value + ".json", function(res) {

                    res = $.map(res, function (obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Pilih Kabupaten -",
                        text: "- Pilih Kabupaten -"
                    }].concat(res);

                    //implemen data ke select provinsi
                    $("#select2-kabupaten").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })

                    if (kabupaten_id) {
                        $("#select2-kabupaten").val(kabupaten_id).trigger('change');
                    }
                })
            }
        });

        var selectKab = $('#select2-kabupaten');
        $(selectKab).change(function () {
            var value = $(selectKab).val();
            clearOptions('select2-kecamatan');
            clearOptions('select2-desa');
            if (value) {
                console.log("on change selectKab");

                var text = $('#select2-kabupaten :selected').text();
                console.log("value = " + value + " / " + "text = " + text);

                console.log('Load Kecamatan di '+text+'...')
                $.getJSON(urlKecamatan + value + ".json", function(res) {

                    res = $.map(res, function (obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    data = [{
                        id: "",
                        nama: "- Pilih Kecamatan -",
                        text: "- Pilih Kecamatan -"
                    }].concat(res);

                    //implemen data ke select provinsi
                    $("#select2-kecamatan").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })

                    if (kecamatan_id) {
                        $("#select2-kecamatan").val(kecamatan_id).trigger('change');
                    }
                })
            }
        });
        $('#select2-kecamatan').change(function () {
            var value = $(this).val();
            clearOptions('select2-desa');

            if (value) {
                console.log("on change selectKec");
                console.log('Load Desa di ' + value + '...');
                $.getJSON(urlKelurahan + value + ".json", function (res) {
                    res = $.map(res, function (obj) {
                        obj.text = obj.nama
                        return obj;
                    });

                    var data = [{
                        id: "",
                        nama: "- Pilih Desa/Kelurahan -",
                        text: "- Pilih Desa/Kelurahan -"
                    }].concat(res);

                    $("#select2-desa").select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        data: data
                    })

                    if (desa_id) {
                        $("#select2-desa").val(desa_id).trigger('change');
                    }
                })
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // NO RM
            $('#no_rm').on('keyup', function() {
                var noRmValue = $('#no_rm').val();
                var errorNoRm = $('#error_no_rm');

                // Reset error message
                errorNoRm.text('');

                // Check if the value is numeric
                if (!/^\d+$/.test(noRmValue)) {
                    // Display error message
                    errorNoRm.text('No. RM hanya boleh mengandung angka.');
                    $('#no_rm').val('');
                }
            });
            // NIK
            $('#nik').on('keyup', function() {
                var noRmValue = $('#nik').val();
                var errorNoRm = $('#error_nik');

                // Reset error message
                errorNoRm.text('');

                // Check if the value is numeric
                if (!/^\d+$/.test(noRmValue)) {
                    // Display error message
                    errorNoRm.text('NIK hanya boleh mengandung angka.');
                    $('#nik').val('');
                }
            });
            // RT
            $('#rt').on('keyup', function() {
                var noRmValue = $('#rt').val();
                var errorNoRm = $('#error_rt');

                // Reset error message
                errorNoRm.text('');

                // Check if the value is numeric
                if (!/^\d+$/.test(noRmValue)) {
                    // Display error message
                    errorNoRm.text('RT hanya boleh mengandung angka.');
                    $('#rt').val('');
                }
            });
            // RW
            $('#rw').on('keyup', function() {
                var noRmValue = $('#rw').val();
                var errorNoRm = $('#error_rw');

                // Reset error message
                errorNoRm.text('');

                // Check if the value is numeric
                if (!/^\d+$/.test(noRmValue)) {
                    // Display error message
                    errorNoRm.text('RW hanya boleh mengandung angka.');
                    $('#rw').val('');
                }
            });
            // NO HP
            $('#no_hp').on('keyup', function() {
                var noRmValue = $('#no_hp').val();
                var errorNoRm = $('#error_no_hp');

                // Reset error message
                errorNoRm.text('');

                // Check if the value is numeric
                if (!/^\d+$/.test(noRmValue)) {
                    // Display error message
                    errorNoRm.text('No. HP hanya boleh mengandung angka.');
                    $('#no_hp').val('');
                }
            });
        });
    </script>
    @endpush
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
                            <a href="{{ route('pasien.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">List Pasien</a>
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
                <form action="{{ route('pasien.update',$data->id) }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-2">
                            <x-label-default for="" >No. RM <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="no_rm" type="text" value="{{ old('no_rm',$data->no_rm) }}" id="no_rm" placeholder="Masukkan No. RM"></x-input-default>
                            <div id="error_no_rm" class="text-red-500 mt-2 text-xs"></div>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">NIK <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="nik" type="text" value="{{ old('nik',$data->nik) }}" id="nik" placeholder="Masukkan NIK"></x-input-default>
                            <div id="error_nik" class="text-red-500 mt-2 text-xs"></div>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">Nama Lengkap <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="nama" type="text" value="{{ old('nama',$data->name) }}" placeholder="Masukkan Nama Lengkap"></x-input-default>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">Jenis Kelamin <span class="me-2 text-red-500">*</span></x-label-default>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0" {{ old('jenis_kelamin',$data->jenis_kelamin) == "0" ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                <option value="l" {{ old('jenis_kelamin',$data->jenis_kelamin) == "l" ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="p" {{ old('jenis_kelamin',$data->jenis_kelamin) == "p" ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-span-4">
                            <x-label-default for="">Alamat <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-textarea rows="4" name="alamat" type="text" value="{{ old('alamat') }}" placeholder="Masukkan Alamat">{{ old('alamat',$data->alamat) }}</x-input-textarea>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">RT <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="rt" type="text" value="{{ old('rt',$data->rt) }}" id="rt" placeholder="Masukkan RT"></x-input-default>
                            <div id="error_rt" class="text-red-500 mt-2 text-xs"></div>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">RW <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="rw" type="text" value="{{ old('rw',$data->rw) }}" id="rw" placeholder="Masukkan RW"></x-input-default>
                            <div id="error_rw" class="text-red-500 mt-2 text-xs"></div>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">Tempat Lahir <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="tempat_lahir" type="text" value="{{ old('tempat_lahir',$data->tempat_lahir) }}" placeholder="Masukkan Tempat Lahir"></x-input-default>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">Tanggal Lahir <span class="me-2 text-red-500">*</span></x-label-default>
                            <input type="text" datepicker datepicker-format="mm-dd-yyyy" value="{{ \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('m-d-Y') }}" name="tgl_lahir" id="tgl_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan Tanggal Lahir">
                        </div>
                        <div>
                            <x-label-default for="">Provinsi <span class="me-2 text-red-500">*</span></x-label-default>
                            <select name="provinsi" class="select2-data-array browser-default bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="select2-provinsi"></select>
                        </div>
                        <div>
                            <x-label-default for="" >Kabupaten/Kota <span class="me-2 text-red-500">*</span></x-label-default>
                            <select name="kabupaten" class="select2-data-array browser-default bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="select2-kabupaten"></select>
                        </div>
                        <div>
                            <x-label-default for="" >Kecamatan <span class="me-2 text-red-500">*</span></x-label-default>
                            <select name="kecamatan" class="select2-data-array browser-default bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="select2-kecamatan"></select>
                        </div>
                        <div>
                            <x-label-default for="" >Desa/Kelurahan <span class="me-2 text-red-500">*</span></x-label-default>
                            <select name="desa" class="select2-data-array browser-default bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="select2-desa"></select>

                        </div>
                        <div class="col-span-2">
                            <x-label-default for="" >Agama <span class="me-2 text-red-500">*</span></x-label-default>
                            <select id="agama" name="agama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Pilih Agama</option>
                                <option value="islam" {{ $data->agama == 'islam' ? 'selected' : '' }}>Islam</option>
                                <option value="kristen" {{ $data->agama == 'kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="katolik" {{ $data->agama == 'katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="hindu" {{ $data->agama == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="buddha" {{ $data->agama == 'buddha' ? 'selected' : '' }}>Buddha </option>
                                <option value="khonghucu" {{ $data->agama == 'khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">Status Kawin <span class="me-2 text-red-500">*</span></x-label-default>
                            <select id="status_kawin" name="status_kawin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0" {{ old('status_nasabah',$data->status_kawin) == '0' ? 'selected' : '' }}>Pilih Status</option>
                                <option value="1" {{ old('status_nasabah',$data->status_kawin) == '1' ? 'selected' : ''}}>Belum Menikah</option>
                                <option value="2" {{ old('status_nasabah',$data->status_kawin) == '2' ? 'selected' : ''}}>Menikah</option>
                                <option value="3" {{ old('status_nasabah',$data->status_kawin) == '3' ? 'selected' : ''}}>Duda</option>
                                <option value="4" {{ old('status_nasabah',$data->status_kawin) == '4' ? 'selected' : ''}}>Janda</option>
                            </select>
                        </div>
                        <div>
                            <x-label-default for="">Pendidikan <span class="me-2 text-red-500">*</span></x-label-default>
                            <select id="pendidikan" name="pendidikan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0" {{ old('pendidikan',$data->pendidikan) == '0' ? 'selected' : '0' }}> Pilih Pendidikan</option>
                                <option value="TK" {{ old('pendidikan',$data->pendidikan) == 'TK' ? 'selected' : 'TK' }}> TK</option>
                                <option value="SD" {{ old('pendidikan',$data->pendidikan) == 'SD' ? 'selected' : 'SD'}}>  SD</option>
                                <option value="SMP" {{ old('pendidikan',$data->pendidikan) == 'SMP' ? 'selected' : 'SMP'}}>  SMP</option>
                                <option value="SMA" {{ old('pendidikan',$data->pendidikan) == 'SMA' ? 'selected' : 'SMA'}}>  SMA</option>
                                <option value="D1" {{ old('pendidikan',$data->pendidikan) == 'D1' ? 'selected' : 'D1'}}>  D1</option>
                                <option value="D2" {{ old('pendidikan',$data->pendidikan) == 'D2' ? 'selected' : 'D2'}}>  D2</option>
                                <option value="D3" {{ old('pendidikan',$data->pendidikan) == 'D3' ? 'selected' : 'D3'}}>  D3</option>
                                <option value="D4" {{ old('pendidikan',$data->pendidikan) == 'D4' ? 'selected' : 'D4'}}>  D4</option>
                                <option value="S1" {{ old('pendidikan',$data->pendidikan) == 'S1' ? 'selected' : 'S1'}}>  S1</option>
                                <option value="S2" {{ old('pendidikan',$data->pendidikan) == 'S2' ? 'selected' : 'S2'}}>  S2</option>
                                <option value="S3" {{ old('pendidikan',$data->pendidikan) == 'S3' ? 'selected' : 'S3'}}>  S3</option>
                            </select>

                        </div>
                        <div>
                            <x-label-default for="" >Pekerjaan <span class="me-2 text-red-500">*</span></x-label-default>
                            <select id="pekerjaan" name="pekerjaan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0" {{ old('pekerjaan',$data->pekerjaan) == '0' ? 'selected' : '' }}>Pilih Status</option>
                                <option value="PNS/TNI-POLRI" {{ old('pekerjaan',$data->pekerjaan) == 'PNS/TNI-POLRI' ? 'selected' : ''}}>PNS/TNI-POLRI</option>
                                <option value="Swasta" {{ old('pekerjaan',$data->pekerjaan) == 'Swasta' ? 'selected' : ''}}>Swasta</option>
                                <option value="Wiraswasta" {{ old('pekerjaan',$data->pekerjaan) == 'Wiraswasta' ? 'selected' : ''}}>Wiraswasta</option>
                                <option value="Petani" {{ old('pekerjaan',$data->pekerjaan) == 'Petani' ? 'selected' : ''}}>Petani</option>
                                <option value="Buruh" {{ old('pekerjaan',$data->pekerjaan) == 'Buruh' ? 'selected' : ''}}>Buruh</option>
                                <option value="Ibu Rumah Tangga" {{ old('pekerjaan',$data->pekerjaan) == 'Ibu Rumah Tangga' ? 'selected' : ''}}>Ibu Rumah Tangga</option>
                                <option value="Pelajar" {{ old('pekerjaan',$data->pekerjaan) == 'Pelajar' ? 'selected' : ''}}>Pelajar</option>
                                <option value="Tidak Bekerja" {{ old('pekerjaan',$data->pekerjaan) == 'Tidak Bekerja' ? 'selected' : ''}}>Tidak Bekerja</option>
                            </select>
                        </div>
                        <div>
                            <x-label-default for="" >Suku <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="suku" type="text" value="{{ old('suku',$data->suku) }}" placeholder="Masukkan Suku"></x-input-default>
                        </div>
                        <div>
                            <x-label-default for="" >Bahasa <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="bahasa" type="text" value="{{ old('bahasa',$data->bahasa) }}" placeholder="Masukkan Bahasa"></x-input-default>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">No. Handphone <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="no_hp" type="text" value="{{ old('no_hp',$data->no_hp) }}" id="no_hp" placeholder="Masukkan No. Handphone"></x-input-default>
                            <div id="error_no_hp" class="text-red-500 mt-2 text-xs"></div>
                        </div>
                        <div class="col-span-2">
                            <x-label-default for="">Nama Orang Tua/ Penanggung Jawab <span class="me-2 text-red-500">*</span></x-label-default>
                            <x-input-default name="nama_ortu" type="text" value="{{ old('nama_ortu',$data->nama_ortu) }}" placeholder="Masukkan Orang Tua/ Penanggung Jawab"></x-input-default>
                        </div>
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
