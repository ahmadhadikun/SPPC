<aside class="w-64 bg-emerald-700 text-white min-h-screen p-5">

    <div class="mb-10">

        <h1 class="text-2xl font-bold">
            🍽️ SPPC
        </h1>

        <p class="text-sm text-emerald-200">
            Sistem Pengambilan Lauk
        </p>

    </div>

    <nav class="space-y-2">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="block px-4 py-3 rounded-xl hover:bg-emerald-600">

            Dashboard

        </a>


        {{-- Pengambilan Lauk --}}
        <a href="#"
            class="block px-4 py-3 rounded-xl hover:bg-emerald-600">

            Pengambilan Lauk

        </a>


        {{-- Riwayat --}}
        <a href="#"
            class="block px-4 py-3 rounded-xl hover:bg-emerald-600">

            Riwayat

        </a>


        {{-- Manajemen Pengguna --}}
        <a href="{{ route('users.index') }}"
            class="block px-4 py-3 rounded-xl hover:bg-emerald-600">

            Manajemen Pengguna

        </a>


        {{-- Profil --}}
        <a href="{{ route('profile.edit') }}"
            class="block px-4 py-3 rounded-xl hover:bg-emerald-600">

            Profil

        </a>


        <hr class="border-emerald-600 my-5">


        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit"
                class="w-full text-left px-4 py-3 rounded-xl bg-red-500 hover:bg-red-600">

                Logout

            </button>

        </form>

    </nav>

</aside>