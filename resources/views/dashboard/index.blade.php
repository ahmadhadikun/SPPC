@extends('layouts.app')

@section('title', 'Dashboard SPPC')

@section('content')

<div class="flex">

    @include('components.sidebar')

    <div class="flex-1">

        @include('components.navbar')

        <main class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div class="bg-white rounded-2xl shadow p-5">

                    <p class="text-gray-500">
                        Lauk Hari Ini
                    </p>

                    <h3 class="text-3xl font-bold mt-2">
                        🍗 Ayam Goreng
                    </h3>

                </div>

                <div class="bg-white rounded-2xl shadow p-5">

                    <p class="text-gray-500">
                        Sudah Ambil
                    </p>

                    <h3 class="text-3xl font-bold mt-2 text-emerald-600">
                        125
                    </h3>

                </div>

                <div class="bg-white rounded-2xl shadow p-5">

                    <p class="text-gray-500">
                        Belum Ambil
                    </p>

                    <h3 class="text-3xl font-bold mt-2 text-orange-500">
                        37
                    </h3>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow mt-8 p-6">

                <h3 class="text-xl font-bold mb-5">
                    Riwayat Pengambilan
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="border-b">

                                <th class="text-left py-3">
                                    Nama
                                </th>

                                <th class="text-left py-3">
                                    Lauk
                                </th>

                                <th class="text-left py-3">
                                    Jam
                                </th>

                                <th class="text-left py-3">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr class="border-b">

                                <td class="py-4">
                                    Ahmad
                                </td>

                                <td>
                                    Ayam Goreng
                                </td>

                                <td>
                                    11.45
                                </td>

                                <td>

                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">
                                        Selesai
                                    </span>

                                </td>

                            </tr>

                            <tr class="border-b">

                                <td class="py-4">
                                    Fikri
                                </td>

                                <td>
                                    Ayam Goreng
                                </td>

                                <td>
                                    11.52
                                </td>

                                <td>

                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">
                                        Selesai
                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <td class="py-4">
                                    Ridho
                                </td>

                                <td>
                                    Ayam Goreng
                                </td>

                                <td>
                                    -
                                </td>

                                <td>

                                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                                        Belum
                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>

@endsection