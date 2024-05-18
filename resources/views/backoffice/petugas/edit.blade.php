<x-app-layout>
    @push('js')
    <script>
        const passwordToggle = document.querySelector('.js-password-toggle')

            passwordToggle.addEventListener('change', function() {
            const password = document.querySelector('.js-password'),
                passwordLabel = document.querySelector('.js-password-label')

            if (password.type === 'password') {
                password.type = 'text'
                passwordLabel.innerHTML = 'hide'
            } else {
                password.type = 'password'
                passwordLabel.innerHTML = 'show'
            }

            password.focus()
        })

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
                            <a href="{{ route('petugas.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">List Petugas</a>
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
                <form action="{{ route('petugas.update',$petugas->id) }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-label-default for="" value="Nama Petugas">Nama Petugas</x-label-default>
                        <x-input-default name="name" type="text" value="{{ old('name',$petugas->name) }}" placeholder="Masukkan Nama Petugas"></x-input-default>
                    </div>
                    <div>
                        <x-label-default for="" value="Email Petugas">Email</x-label-default>
                        <x-input-default name="email" type="email" value="{{ old('email',$petugas->email) }}" placeholder="Masukkan Email"></x-input-default>
                    </div>
                    <div>
                        <x-label-default for="" value="Password">Password</x-label-default>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                                <label class="bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 font-mono cursor-pointer js-password-label" for="toggle">show</label>
                            </div>
                            <x-input-default name="password" type="password" value="{{ old('password') }}" class="js-password" placeholder="Masukkan Password"></x-input-default>
                        </div>
                        <small class="text-xs text-red-800">Kosongkan jika tidak ingin merubah password</small>
                    </div>
                    <div>
                        <x-label-default>Hak Akses</x-label-default>
                        <select name="hak_akses" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="">
                            <option value="0">Pilih Hak Akses</option>
                            <option value="admin" {{ $petugas->roles->first()->name == 'admin' ? 'selected' :'' }}>Admin</option>
                            <option value="petugas-klinik" {{ $petugas->roles->first()->name == 'petugas-klinik' ? 'selected' :'' }}>Petugas Klinik</option>
                            <option value="petugas-pendaftaran" {{ $petugas->roles->first()->name == 'petugas-pendaftaran' ? 'selected' :'' }}>Petugas Pendaftaran</option>
                        </select>
                    </div>
                    <div>
                        <x-label-default>Gambar</x-label-default>
                        <div>
                            <figure class="max-w-lg">
                                <img src="{{ $petugas->gambar != null ? asset('storage/petugas/'.$petugas->gambar) : 'https://flowbite.com/docs/images/examples/image-1@2x.jpg' }}" class="h-96 max-w-full rounded-lg photosPreview">
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
