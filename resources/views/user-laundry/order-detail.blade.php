@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Detail Order') }}
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('laundry-orders') }}" class="btn d-sm-inline-block">
                    <i class="fas fa-fw fa-arrow-left"></i>
                    Kembali
                </a>
                <a href="{{ route('laundry-orders') }}" class="btn btn-sm btn-primary d-sm-inline-block"
                    data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-fw fa-edit"></i>
                    Ubah Status
                </a>
            </div>
        </div>
    </div>
    <hr>
    <div class="page-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon alert-icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>
                        {{ $message }}
                    </div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <table>
                    <tr>
                        <td>{{ __('Laundry') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td><b>{{ $order->laundry->name }}</b></td>
                    </tr>
                    <tr>
                        <td>{{ __('Kostumer') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td>{{ $order->user->name }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Berat') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td>{{ $order->getTotalWeight() }} KG</td>
                    </tr>
                    <tr>
                        <td>{{ __('Harga') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td>Rp {{ number_format($order->getTotalPrice(), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Diambil') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td>
                            @if ($order->is_pickedup)
                                <span class="badge bg-success"><i class="fas fa-fw fa-check"></i></span>
                            @else
                                <span class="badge bg-warning"><i class="fas fa-fw fa-times"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('Dibayar') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td>
                            @if ($order->is_paid)
                                <span class="badge bg-success"><i class="fas fa-fw fa-check"></i></span>
                            @else
                                <span class="badge bg-warning"><i class="fas fa-fw fa-times"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('Status') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td class="text-uppercase">{{ $order->status }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Tanggal') }}</td>
                        <td class="py-2 px-4">:</td>
                        <td class="text-uppercase">{{ $order->created_at }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 class="m-0">Detail Layanan</h6>
                <hr class="my-3">
                <div class="table-responsive">
                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Layanan') }}</th>
                                <th>{{ __('Berat') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Harga') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><b>{{ $detail->service->name }}</b></td>
                                    <td>{{ $detail->weight }} KG</td>
                                    <td>Rp {{ number_format($detail->service->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($order->review)
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="m-0">Ulasan</h6>
                    <hr class="my-3">
                    <table>
                        <tr>
                            <td>{{ __('Rating') }}</td>
                            <td class="py-2 px-4">:</td>
                            <td>
                                @for ($i = 0; $i < $order->review?->rating; $i++)
                                    <i class="fas fa-fw fa-star text-warning"></i>
                                @endfor
                                @if ($order->review?->rating < 5)
                                    @for ($i = 0; $i < 5 - $order->review?->rating; $i++)
                                        <i class="fas fa-fw fa-star text-muted"></i>
                                    @endfor
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Komentar') }}</td>
                            <td class="py-2 px-4">:</td>
                            <td>{{ $order->review?->comments }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('laundry-orders.update-status', $order) }}" method="GET" class="mb-3"
                        id="update-status">
                        <input type="hidden" name="type" value="STATUS">
                        <select class="form-select" aria-label="Change Status" name="status">
                            @foreach (['PESANAN_DIBUAT', 'DITERIMA', 'DICUCI', 'SETRIKA', 'SELESAI', 'DIBATALKAN'] as $item)
                                <option @selected($item === $order->status)>{{ $item }}</option>
                            @endforeach
                        </select>
                        <div class="input-group mt-3">
                            <input type="number" name="weight" class="form-control" placeholder="Masukan Berat"
                                value="{{ $order->getTotalWeight() }}" step="0.01">
                            <span class="input-group-text" id="basic-addon2">KG</span>
                        </div>
                    </form>

                    <div class="d-flex" style="gap: 10px">
                        <a href="{{ route('laundry-orders.update-status', [$order, 'type' => 'PAID', 'status' => !$order->is_paid]) }}"
                            @class([
                                'w-100 btn btn-sm d-sm-inline-block text-white',
                                'bg-success' => $order->is_paid,
                                'bg-secondary' => !$order->is_paid,
                            ])>
                            <i class="fas fa-fw fa-check"></i>
                            Konfirmasi Pembayaran
                        </a>
                        <a href="{{ route('laundry-orders.update-status', [$order, 'type' => 'PICKEDUP', 'status' => !$order->is_pickup]) }}"
                            @class([
                                'w-100 btn btn-sm d-sm-inline-block text-white',
                                'bg-success' => $order->is_pickedup,
                                'bg-secondary' => !$order->is_pickedup,
                            ])>
                            <i class="fas fa-fw fa-check"></i>
                            Konfirmasi Pengambilan
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button onclick="document.getElementById('update-status').submit()"
                        class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
@endsection
