@extends('layouts.app')

@section('content')
    <div class="page-body">
        <div class="row mb-3">
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Pengguna</small>
                    <h4 class="m-0">{{ $data['costumer'] ?? 0 }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Layanan</small>
                    <h4 class="m-0">{{ $data['services'] ?? 0 }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Pesanan</small>
                    <h4 class="m-0">{{ $data['orders'] ?? 0 }}</h4>
                </div>
            </div>
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Transaksi</small>
                    <h4 class="m-0">Rp {{ number_format($data['amount'] ?? 0) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="alert-title">
            {{ __('Selamat Datang') }}, {{ auth()->user()->name ?? null }}
        </div>
        <div class="text-muted">
            {{ __('Kamu berhasil masuk!') }}
        </div>
    </div>
@endsection
