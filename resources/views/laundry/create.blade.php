@extends('layouts.app')

@section('custom_styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css"
        integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />

    <style>
        #map {
            height: 280px;
        }
    </style>
@endsection

@section('content')
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Tambah Laundry') }}
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

        <form action="{{ route('laundry.store') }}" method="POST" class="card" autocomplete="off"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label ">{{ __('Nama Laundry') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="{{ __('Nama Laundry') }}" value="{{ old('name') }}">
                </div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label ">{{ __('Pemilik') }}</label>
                    <select name="owner" class="form-control @error('owner') is-invalid @enderror">
                        @foreach ($users as $user)
                            <option @selected(old('owner') == $user->id) value="{{ $user->id }}">{{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('owner')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label ">{{ __('Nomor Izin') }}</label>
                    <input type="text" name="no_izin" class="form-control @error('no_izin') is-invalid @enderror"
                        placeholder="{{ __('Nomor Izin') }}" value="{{ old('no_izin') }}">
                </div>
                @error('no_izin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label ">{{ __('Alamat') }}</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                        placeholder="{{ __('Alamat') }}" value="{{ old('address') }}">
                </div>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label ">{{ __('Kecamatan') }}</label>
                    <input type="text" name="district" class="form-control @error('district') is-invalid @enderror"
                        placeholder="{{ __('Kecamatan') }}" value="{{ old('district') }}">
                </div>
                @error('district')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label ">{{ __('kabupaten / Kota') }}</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                        placeholder="{{ __('kabupaten / Kota') }}" value="{{ old('city') }}">
                </div>
                @error('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="d-flex" style="gap: 0.75rem">
                    <div class="mb-3 w-100">
                        <label class="form-label ">{{ __('Latitude') }}</label>
                        <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror"
                            placeholder="{{ __('Latitude') }}" id="lat" value="{{ old('lat') }}">
                    </div>
                    @error('lat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="mb-3 w-100">
                        <label class="form-label ">{{ __('Longitude') }}</label>
                        <input type="text" name="long" class="form-control @error('long') is-invalid @enderror"
                            placeholder="{{ __('Longitude') }}" id="long" value="{{ old('long') }}">
                    </div>
                    @error('long')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" @checked(old('has_pickup')) value="1"
                        name="has_pickup" id="has_pickup">
                    <label class="form-check-label" for="has_pickup">
                        Layanan Antar / Jemput
                    </label>
                </div>

                <div class="position-relative">
                    <div id="map" style="z-index: 1"></div>
                    <button id="my-position" type="button"
                        class="position-absolute m-3 text-primary bg-white fs-3 rounded-circle border-0 "
                        style="z-index: 2; bottom: 5px; right: 5px; height: 48px; width: 48px">
                        <i class="fas fa-fw fa-crosshairs"></i>
                    </button>
                </div>
                <small class="text-muted my-2 d-block" id="demo">-</small>

                <div class="mb-3">
                    <label class="form-label ">{{ __('Photo') }}</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                        onchange="preview()">
                </div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="frame" src="" style="max-height: 150px" />
            </div>

            <div class="card-footer">
                <a href="{{ route('laundry.index') }}" class="btn btn-secondary">{{ __('Kembali') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_scripts')
    <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"
        integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>

    <script>
        var x = document.getElementById("demo");
        const latInput = document.getElementById('lat')
        const longInput = document.getElementById('long')

        function preview() {
            frame.src = URL.createObjectURL(event.target.files[0]);
        }

        // start

        var map, markers = {};
        var you, circle;
        var myPosition = document.getElementById('my-position');

        var LeafIcon = L.Icon.extend({
            options: {
                shadowUrl: "{{ asset('icon_shadow.png') }}",
                iconSize: [25, 50],
                shadowSize: [50, 75],
            }
        });

        var currentPosition = new LeafIcon({
            iconUrl: "{{ asset('icon_you.svg') }}"
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        async function showPosition(position) {
            let coords = {
                lat: @json(old('lat')) ?? position.coords.latitude,
                long: @json(old('long')) ?? position.coords.longitude
            }

            x.innerHTML = "Latitude: <i>" + position.coords.latitude +
                "</i><br>Longitude: <i>" + position.coords.longitude + "</i>";

            console.log(coords);

            map = L.map('map', {
                attributionControl: false
            }).setView([coords.lat, coords.long], 18);

            map.options.minZoom = 17;
            map.options.maxZoom = 18;

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            you = L.marker(new L.LatLng(coords.lat, coords.long), {
                    icon: currentPosition,
                    draggable: true
                }).addTo(map)
                .bindPopup('You are here!');

            latInput.value = you.getLatLng().lat;
            longInput.value = you.getLatLng().lng;

            you.on('dragend', function(e) {
                var featureGroup = L.featureGroup([you]);
                map.fitBounds(featureGroup.getBounds());
                onDragMap(e)
            });
        }

        getLocation()

        function onDragMap(e) {
            x.innerHTML = "Latitude: " + you.getLatLng().lat +
                "<br>Longitude: " + you.getLatLng().lng;
            latInput.value = you.getLatLng().lat;
            longInput.value = you.getLatLng().lng;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    x.innerHTML = "User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    x.innerHTML = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    x.innerHTML = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    x.innerHTML = "An unknown error occurred."
                    break;
            }
        }

        function getMyPosition() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    lat = position.coords.latitude;
                    long = position.coords.longitude;
                    latInput.value = lat;
                    longInput.value = long;
                    var accuracy = position.coords.accuracy;
                    if (you) map.removeLayer(you);
                    if (circle) map.removeLayer(circle);
                    you = L.marker([lat, long], {
                        icon: currentPosition,
                        draggable: true
                    }).addTo(map);
                    circle = L.circle([lat, long], {
                        radius: accuracy
                    });
                    var featureGroup = L.featureGroup([you, circle]).addTo(map);
                    map.fitBounds(featureGroup.getBounds());
                    you.on('dragend', function(e) {
                        map.removeLayer(featureGroup)
                        featureGroup = L.featureGroup([you]).addTo(map);
                        map.fitBounds(featureGroup.getBounds());
                        onDragMap(e)
                    });
                }, showError);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        myPosition.addEventListener('click', getMyPosition);
    </script>
@endsection
