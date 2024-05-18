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
    <form method="POST" class="w-full max-w-full" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/4">
              <x-input-label for="email" :value="__('Email')" />

            </div>
            <div class="md:w-full">
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/4">
                <x-input-label for="password" :value="__('Password')" />

            </div>
            <div class="md:w-full">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 right-0 flex items-center px-2">
                        <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                        <label class="bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 font-mono cursor-pointer js-password-label" for="toggle">show</label>
                    </div>
                    <x-input-default name="password" type="password" value="{{ old('password') }}" class="js-password"></x-input-default>
                </div>
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
        {{-- <div class="flex-row text-center items-center justify-center mt-4 space-y-2">
            <div>
                <a class="underline text-sm text-blue-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                    {{ __('Daftar Sekarang !') }}
                </a>
            </div>
            <div>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-blue-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Lupa Password?') }}
                    </a>
                @endif
            </div>


        </div> --}}
    </form>
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
</x-guest-layout>
