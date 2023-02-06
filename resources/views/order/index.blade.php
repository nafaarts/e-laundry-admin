@extends('layouts.app')

@section('custom_styles')
    <style>
        th,
        td {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h2 class="page-title mb-3">
                    Data Pesanan
                </h2>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-md-end justify-content-center">
                <div class="btn-group">
                    <a @class([
                        'btn btn-primary',
                        'active' => !in_array(request('category'), [
                            'proses',
                            'selesai',
                            'dibatalkan',
                        ]),
                    ]) href="?category=all" aria-current="page">Semua</a>
                    <a @class([
                        'btn btn-primary',
                        'active' => request('category') == 'proses',
                    ]) href="?category=proses">Proses</a>
                    <a @class([
                        'btn btn-primary',
                        'active' => request('category') == 'selesai',
                    ]) href="?category=selesai">Selesai</a>
                    <a @class([
                        'btn btn-primary',
                        'active' => request('category') == 'dibatalkan',
                    ]) href="?category=dibatalkan">Dibatalkan</a>
                </div>
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

        <div class="card p-4">
            <div class="table-responsive">
                <table class="display" id="myTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Laundry') }}</th>
                            <th>{{ __('Kostumer') }}</th>
                            <th>{{ __('Berat') }}</th>
                            <th>{{ __('Harga') }}</th>
                            <th>{{ __('diambil') }}</th>
                            <th>{{ __('dibayar') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Tanggal') }}</th>
                            <th>{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <p class="mb-1 fw-bold">{{ $order->laundry->name }}</p>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->getTotalWeight() }} KG</td>
                                <td>Rp {{ number_format($order->getTotalPrice(), 0, ',', '.') }}</td>
                                <td>
                                    @if ($order->is_pickedup)
                                        <span class="badge bg-success"><i class="fas fa-fw fa-check"></i></span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-fw fa-times"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->is_paid)
                                        <span class="badge bg-success"><i class="fas fa-fw fa-check"></i></span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-fw fa-times"></i></span>
                                    @endif
                                </td>
                                <td class="text-uppercase">{{ $order->status }}</td>
                                <td>
                                    <span style="display:none;">{{ $order->created_at }}</span>
                                    {{ $order->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-fw fa-info-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('custom_scripts')
    <script defer>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [[ 3, "desc" ]],
            });
        });
    </script>
@endsection
