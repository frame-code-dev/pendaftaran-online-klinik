<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center space-y-2 mb-5">
        <div class="mx-auto items-center flex justify-center">
            <a href="/">
                <x-application-logo class="w-20 h-20 mx-auto fill-current text-gray-500" />
            </a>
        </div>
        <h4 class="font-bold">Sistem Pendaftaran Online</h4>
        <h4 class="text-pretty text-sm">Rumah Sakit Gigi dan Mulut Universitas Jember</h4>
        <hr>
    </div>
    <h4 class="text-center font-bold my-4">LOGIN</h4>
    <form method="POST" class="w-full max-w-full" action="{{ route('pasien.login.store') }}">
        @csrf
        <!-- Email Address -->
        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/4">
              <x-input-label for="NIK" :value="__('NIK')" />

            </div>
            <div class="md:w-full">
              <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" required autofocus autocomplete="Masukkan NIK" />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/4">
                <x-input-label for="Tanggal Lahir" :value="__('Tanggal Lahir')" />

            </div>
            <div class="md:w-full">
                <input type="text" datepicker datepicker-format="mm-dd-yyyy" name="tgl_lahir" id="tgl_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan Tanggal Lahir">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>
        <div class="mt-4 flex justify-center w-full">
            <x-primary-button class="ms-3 w-full justify-center items-center inline-block">
                <span>Login</span>
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
