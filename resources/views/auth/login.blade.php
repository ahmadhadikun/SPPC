```blade
<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-emerald-50 px-4 py-10">

        <div class="w-full max-w-md">

            <!-- Header -->
            <div class="text-center mb-8">

                <div class="mx-auto w-20 h-20 bg-emerald-600 rounded-2xl flex items-center justify-center shadow-lg mb-5">
                    <span class="text-4xl">🍽️</span>
                </div>

                <h1 class="text-3xl font-bold text-emerald-800">
                    SPPC
                </h1>

                <p class="text-gray-500 mt-2">
                    Sistem Pengambilan Lauk
                </p>

                <p class="text-sm text-gray-400 mt-1">
                    Silakan masuk untuk melanjutkan
                </p>

            </div>


            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">

                <!-- Session Status -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')" />


                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">

                    @csrf


                    <!-- Email -->
                    <div>

                        <x-input-label
                            for="email"
                            :value="__('Email')"
                            class="text-gray-700" />

                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan email" />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2" />

                    </div>


                    <!-- Password -->
                    <div class="mt-5">

                        <x-input-label
                            for="password"
                            :value="__('Password')"
                            class="text-gray-700" />

                        <x-text-input
                            id="password"
                            class="block mt-2 w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password" />

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2" />

                    </div>


                    <!-- Remember Me -->
                    <div class="mt-5">

                        <label
                            for="remember_me"
                            class="inline-flex items-center">

                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                name="remember">

                            <span class="ms-2 text-sm text-gray-600">
                                {{ __('Remember me') }}
                            </span>

                        </label>

                    </div>


                    <!-- Login Button -->
                    <div class="mt-6">

                        <x-primary-button
                            class="w-full justify-center py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800">

                            {{ __('Masuk') }}

                        </x-primary-button>

                    </div>


                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))

                        <div class="text-center mt-5">

                            <a
                                class="text-sm text-emerald-600 hover:text-emerald-700 underline"
                                href="{{ route('password.request') }}">

                                {{ __('Lupa password?') }}

                            </a>

                        </div>

                    @endif


                    <!-- Register -->
                    @if (Route::has('register'))

                        <div class="text-center mt-4">

                            <span class="text-sm text-gray-500">
                                Belum punya akun?
                            </span>

                            <a
                                href="{{ route('register') }}"
                                class="text-sm text-emerald-600 hover:text-emerald-700 underline ms-1">

                                {{ __('Daftar sekarang') }}

                            </a>

                        </div>

                    @endif

                </form>

            </div>


            <!-- Footer -->
            <p class="text-center text-sm text-gray-400 mt-6">

                SPPC &copy; {{ date('Y') }}

            </p>

        </div>

    </div>

</x-guest-layout>
```