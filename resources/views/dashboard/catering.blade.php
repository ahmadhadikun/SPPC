@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 py-4">
                <div class="card-body">
                    <h2 class="mb-3">Dashboard Petugas Catering</h2>
                    <p class="text-muted mb-4">Silakan mulai pemindaian kartu atau kode QR santri untuk pencatatan pengambilan lauk.</p>
                    
                    <a href="{{ route('scan.index') }}" class="btn btn-success btn-lg px-5 shadow">
                        <i class="fa-solid fa-qrcode me-2"></i> Buka Halaman Scanner QR
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection