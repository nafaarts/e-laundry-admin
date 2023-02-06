@extends('layouts.app')

@section('custom_styles')
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}" />

    <style>
        #map {
            height: 280px;
        }
    </style>
@endsection

@section('content')
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Detail Laundry') }}
                </h2>
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
            <div
                style="height: 250px; background-image: url('{{ asset('img/laundry/' . $laundry->image) }}'); background-size: cover; background-position: center center">
            </div>
            <div class="card-body">
                <table>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Nama Laundry</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->name }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Pemilik</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->user->name }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Nomor Izin</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->no_izin }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Alamat</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->address }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Kecamatan</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->district }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Kota</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->city }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Nomor Telepon</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->user->phone_number }}</th>
                    </tr>
                    <tr class="fs-6">
                        <td class="pe-4 py-1">Layanan Antar / Jemput</td>
                        <td class="px-2">:</td>
                        <th>{{ $laundry->has_pickup ? 'Ada' : 'Tidak' }}</th>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('laundry.edit', $laundry) }}"><i class="fas fa-fw fa-edit"></i> Ubah Laundry</a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="m-0">Layanan</h4>
                    <a href="{{ route('service.create', ['laundry' => $laundry->id]) }}"><i class="fas fa-fw fa-plus"></i>
                        Tambah Layanan</a>
                </div>
                <hr class="my-3">
                <div class="row">
                    @foreach ($laundry->services as $service)
                        <div class="col-md-6 col-sm-12">
                            <div class="border p-3 mb-2">
                                <div class="d-flex flex-md-row flex-column" style="gap: 1.75rem">
                                    <img src="{{ asset('img/icon/' . $service->icon) }}" alt="icon" height="100"
                                        width="100">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h5>{{ $service->name }}</h5>
                                            <div>
                                                <a href="{{ route('service.edit', [$service, 'laundry' => $laundry->id]) }}"
                                                    class="btn btn-sm btn-warning"><i class="fas fa-fw fa-edit"></i></a>
                                                <form
                                                    action="{{ route('service.destroy', [$service, 'laundry' => $laundry->id]) }}"
                                                    method="POST" onsubmit="return confirm('Yakin dihapus?')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-fw fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p>Rp {{ number_format($service->price) }} / {{ $service->unit }}</p>
                                        <small>{{ $service->description }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="m-0">Posisi Map</h4>
                <hr class="my-3">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection

@section('custom_scripts')
    <script src="{{ asset('js/leaflet.js') }}"></script>

    <script>
        let coords = {
            lat: @json(old('lat', $laundry->lat)),
            long: @json(old('long', $laundry->long))
        }

        var greenIcon = L.icon({
            iconUrl: "{{ asset('img/marker.png') }}",
            iconSize: [38, 55], // size of the icon
        });

        var map = L.map('map').setView([coords.lat, coords.long], 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker(new L.LatLng(coords.lat, coords.long), {
                icon: greenIcon,
                draggable: false
            }).addTo(map)
            .bindPopup('You are here!');
    </script>
@endsection
