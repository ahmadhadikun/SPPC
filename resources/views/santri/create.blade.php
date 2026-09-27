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
                        <label for="nis" class="form-label">NIS (Nomor Induk Santri)</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_santri" class="form-label">Nama Santri</label>
                        <input type="text" class="form-control" id="nama_santri" name="nama_santri" value="{{ old('nama_santri') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="kamar" class="form-label">Kamar / Asrama</label>
                        <input type="text" class="form-control" id="kamar" name="kamar" value="{{ old('kamar') }}" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('santri.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection