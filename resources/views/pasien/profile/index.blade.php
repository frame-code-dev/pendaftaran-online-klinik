<x-app-layout>
    <div class="p-5 sm:ml-64 mt-20 h-fit">
        <div class="block max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mx-auto ">
            <div class="bg-blue-800 p-4 rounded-t-lg">
                <h4 class="mb-2 text-lg font-semibold text-white dark:text-white">{{ $title }}</h4>
            </div>
            <div class="p-5 space-y-4">
                <form action="{{ route('profile-pasien.update') }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div>
                                <x-label-default>Gambar</x-label-default>
                                <div>
                                    <figure class="max-w-lg">
                                        <img src="{{ $pasien->gambar != null ? asset('storage/pasien/'.$pasien->gambar) : 'https://flowbite.com/docs/images/examples/image-2@2x.jpg' }}" class="h-96 max-w-full rounded-lg photosPreview">
                                        <figcaption class="mt-2 text-sm text-start text-gray-500 dark:text-gray-400">Image Preview</figcaption>
                                    </figure>
                                </div>
                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file_input" aria-describedby="file_input_help"
                                        id="file_input"
                                        type="file"
                                        name="file_input">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="">
                                <x-label-default for="" >No. RM</x-label-default>
                                <x-input-default readonly name="no_rm" type="text" value="{{ old('no_rm',$pasien->no_rm) }}"  class="bg-gray-300" placeholder="Masukkan No. RM"></x-input-default>
                            </div>
                            <div class="">
                                <x-label-default for="" >NIK</x-label-default>
                                <x-input-default readonly name="nik" type="text" value="{{ old('nik',$pasien->nik) }}" class="bg-gray-100" placeholder="Masukkan No. RM"></x-input-default>
                            </div>
                            <div class="">
                                <x-label-default for="">Nama Lengkap</x-label-default>
                                <x-input-default readonly name="nama" type="text" value="{{ old('nama',$pasien->name) }}"  class="bg-gray-300" placeholder="Masukkan Nama Lengkap"></x-input-default>
                            </div>
                            <div class="">
                                <x-label-default for="">Tanggal Lahir</x-label-default>
                                <input type="text" value="{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('m-d-Y') }}" readonly datepicker datepicker-format="mm-dd-yyyy" name="tgl_lahir" id="tgl_lahir" class="bg-gray-300 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan Tanggal Lahir">
                            </div>
                            <div class="">
                                <x-label-default for="">Alamat</x-label-default>
                                <x-input-textarea rows="4" name="alamat" type="text" placeholder="Masukkan Alamat">{{ old('alamat',$pasien->alamat) }}</x-input-textarea>
                            </div>

                            <div >
                                <x-label-default for="">No. Handphone <span class="me-2 text-red-500">*</span></x-label-default>
                                <x-input-default name="no_hp" type="text" value="{{ old('no_hp',$pasien->no_hp) }}" placeholder="Masukkan No. Handphone"></x-input-default>
                                <span class="text-xs text-red-400">** Mencantumkan No. Telephone yang masih aktif dan tersambung dengan Whatsapp</span>
                            </div>

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
        </div>
    </div>
</x-app-layout>
