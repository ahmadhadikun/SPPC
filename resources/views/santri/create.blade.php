
@extends('layouts.app')

@section('title', 'Tambah Santri - SPPC')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Tambah Data Santri Baru</h4>
            </div>

            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('santri.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Santri</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
                            value="{{ old('nama') }}"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input
                            type="text"
                            class="form-control"
                            id="kelas"
                            name="kelas"
                            value="{{ old('kelas') }}"
                            maxlength="100"
                            placeholder="Contoh: 8A"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="asrama" class="form-label">Asrama</label>
                        <input
                            type="text"
                            class="form-control"
                            id="asrama"
                            name="asrama"
                            value="{{ old('asrama') }}"
                            maxlength="100"
                            placeholder="Contoh: Asrama A"
                            required
                        >
                    </div>

                    <div class="alert alert-info">
                        <i class="fa-solid fa-qrcode me-1"></i>
                        QR Code akan dibuat otomatis setelah data santri berhasil disimpan.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('santri.index') }}"
                           class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
