
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

                <form action="{{ route('santri.update', $santri->id_santri) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="id_santri" class="form-label">ID Santri</label>
                        <input
                            type="text"
                            class="form-control"
                            id="id_santri"
                            value="{{ $santri->id_santri }}"
                            disabled
                        >
                        <div class="form-text">
                            ID dibuat otomatis oleh database dan tidak dapat diubah.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Santri</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
                            value="{{ old('nama', $santri->nama) }}"
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
                            value="{{ old('kelas', $santri->kelas) }}"
                            maxlength="100"
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
                            value="{{ old('asrama', $santri->asrama) }}"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('santri.index') }}"
                           class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit"
                                class="btn btn-warning text-dark">
                            <i class="fa-solid fa-pen-to-square me-1"></i>
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
