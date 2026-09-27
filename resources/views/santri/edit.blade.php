@extends('layouts.app')

@section('title', 'Edit Santri - SPPC')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Data Santri</h4>
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

                <form action="{{ route('santri.update', $santri->idSantri) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS (Nomor Induk Santri)</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis', $santri->nis) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_santri" class="form-label">Nama Santri</label>
                        <input type="text" class="form-control" id="nama_santri" name="nama_santri" value="{{ old('nama_santri', $santri->nama_santri) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="kamar" class="form-label">Kamar / Asrama</label>
                        <input type="text" class="form-control" id="kamar" name="kamar" value="{{ old('kamar', $santri->kamar) }}" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('santri.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection