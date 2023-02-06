@extends('layouts.app')

@section('content')
    <div class="page-body">
        <div class="row mb-3">
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Pengguna</small>
                    <h2 class="m-0">{{ $data['users'] }}</h2>
                </div>
            </div>
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Laundry</small>
                    <h2 class="m-0">{{ $data['laundry'] }}</h2>
                </div>
            </div>
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Layanan</small>
                    <h2 class="m-0">{{ $data['services'] }}</h2>
                </div>
            </div>
            <div class="col-md-3 col-6 p-1">
                <div class="card p-3">
                    <small class="text-muted">Pesanan</small>
                    <h2 class="m-0">{{ $data['orders'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-success">
        <div class="alert-title">
            {{ __('Selamat Datang, ') }} {{ auth()->user()->name ?? null }}
        </div>
        <div class="text-muted">
            {{ __('Kamu berhasil login!') }}
        </div>
    </div>
@endsection
