<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPC - Sistem Pengambilan Lauk Santri</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

    <div class="bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 min-h-screen">

    <div class="flex items-center justify-center min-h-screen p-6">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">

            <div class="text-center mb-8">

                <div class="w-20 h-20 bg-emerald-100 rounded-full mx-auto flex items-center justify-center text-4xl">
                    🍽️
                </div>

                <h1 class="text-3xl font-bold text-emerald-600 mt-4">
                    SPPC
                </h1>

                <p class="text-gray-500 mt-2">
                    Sistem Pengambilan Lauk Santri
                </p>

            </div>

            <form class="space-y-5">

                <div>
                    <label class="block text-sm text-gray-600 mb-2">
                        Username
                    </label>

                    <input
                        type="text"
                        placeholder="Masukkan username"
                        class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        placeholder="Masukkan password"
                        class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <a href="/dashboard"
                    class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition text-center">

                    Masuk

                </a>

            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                Pondok Pesantren Al-Amanah • SPPC
            </p>

        </div>

    </div>

</div>