@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

<div class="flex">

    @include('components.sidebar')

    <div class="flex-1">

        @include('components.navbar')

        <main class="p-6">

            <div class="mb-6">

                <h1 class="text-2xl font-bold text-gray-800">
                    Tambah Pengguna
                </h1>

                <p class="text-gray-500 mt-1">
                    Tambahkan pengguna baru ke dalam sistem SPPC.
                </p>

            </div>

            <div class="bg-white rounded-2xl shadow p-6 max-w-2xl">

                <form method="POST" action="{{ route('users.store') }}">

                    @csrf

                    {{-- Nama --}}
                    <div class="mb-5">

                        <label for="name"
                            class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pengguna
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama pengguna"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div class="mb-5">

                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Password --}}
                    <div class="mb-6">

                        <label for="password"
                            class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >

                        @error('password')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-3">

                        <a href="{{ route('users.index') }}"
                            class="px-5 py-2.5 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                            Simpan Pengguna
                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>

</div>

@endsection