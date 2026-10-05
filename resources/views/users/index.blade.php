@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')

<div class="flex">

    @include('components.sidebar')

    <div class="flex-1">

        @include('components.navbar')

        <main class="p-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Manajemen Pengguna
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Kelola data pengguna sistem SPPC.
                    </p>
                </div>

                <a href="{{ route('users.create') }}"
                    class="inline-flex items-center justify-center bg-emerald-600 text-white px-5 py-2.5 rounded-xl hover:bg-emerald-700 transition">
                    + Tambah Pengguna
                </a>

            </div>


            <!-- Pesan Berhasil -->
            @if (session('success'))

                <div class="mb-6 bg-emerald-100 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl">
                    {{ session('success') }}
                </div>

            @endif


            <!-- User Table -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">

                <div class="p-6 border-b">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Daftar Pengguna
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Berikut adalah pengguna yang terdaftar dalam sistem.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50">

                            <tr class="border-b">

                                <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                                    No
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                                    Nama
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                                    Email
                                </th>

                                <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                                    Terdaftar
                                </th>

                                <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($users as $user)

                                <tr class="border-b hover:bg-gray-50">

                                    <!-- No -->
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $loop->iteration }}
                                    </td>


                                    <!-- Nama -->
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-800">
                                            {{ $user->name }}
                                        </div>

                                        @if ($user->id === auth()->id())

                                            <span class="text-xs text-emerald-600">
                                                Akun aktif
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Email -->
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $user->email }}
                                    </td>


                                    <!-- Tanggal -->
                                    <td class="px-6 py-4 text-gray-600">

                                        {{ $user->created_at->format('d M Y') }}

                                    </td>


                                    <!-- Aksi -->
                                    <td class="px-6 py-4">

                                        <div class="flex justify-center gap-2">


                                            <!-- Edit -->
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 text-sm hover:bg-blue-200">

                                                Edit

                                            </a>


                                            <!-- Hapus -->
                                            @if ($user->id !== auth()->id())

                                                <form method="POST"
                                                    action="{{ route('users.destroy', $user->id) }}"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus pengguna ini?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-sm hover:bg-red-200">

                                                        Hapus

                                                    </button>

                                                </form>

                                            @else

                                                <button type="button"
                                                    disabled
                                                    class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">

                                                    Hapus

                                                </button>

                                            @endif


                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-gray-500">

                                        Belum ada pengguna.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>

@endsection