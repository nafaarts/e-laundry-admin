<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css"
        integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />

    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>

    <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"
        integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>
</head>

<body class="position-relative">

    <button id="my-position" class="position-absolute m-3 text-primary bg-white fs-3 rounded-circle border-0 "
        style="z-index: 2; bottom: 5px; right: 5px; height: 48px; width: 48px">
        <i class="fas fa-fw fa-crosshairs"></i>
    </button>

    <div id="map" style="z-index: 1"></div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="image-field"
                        style="height: 200px; background-size: cover; background-position: center center">
                    </div>
                    <hr>
                    <div class="datagrid">
                        <div class="datagrid-item mb-2">
                            <div class="datagrid-title">Nama</div>
                            <div id="name-field" class="datagrid-content fw-bold"></div>
                        </div>
                        <div class="datagrid-item mb-2">
                            <div class="datagrid-title">Owner</div>
                            <div id="owner-field" class="datagrid-content fw-bold">-</div>
                        </div>
                        <div class="datagrid-item mb-2">
                            <div class="datagrid-title">No. Izin</div>
                            <div id="permission-field" class="datagrid-content fw-bold">-</div>
                        </div>
                        <div class="datagrid-item mb-2">
                            <div class="datagrid-title">Alamat</div>
                            <div id="address-field" class="datagrid-content fw-bold">-</div>
                        </div>
                        <div class="datagrid-item mb-2">
                            <div class="datagrid-title">Nomor Telepon</div>
                            <div id="phone-field" class="datagrid-content fw-bold">-</div>
                        </div>
                        <div class="datagrid-item mb-2" id="rating-wrapper">
                            <div class="datagrid-title">Rating</div>
                            <div id="phone-field" class="datagrid-content fw-bold d-flex" style="gap: 5px">
                                <div class="rating" id="rating">
                                    {{-- <span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span> --}}
                                    {{-- <i class="fas fa-fw fa-star"></i> --}}
                                </div>

                                .
                                <span class="text-muted" id="ulasan"></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex w-100">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fas fa-fw fa-arrow-left"></i></button>
                        <a id="open-explore" class="ms-2 btn btn-primary flex-grow-1">Lihat
                            Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script defer>
        var map, markers = {};
        var you, circle;
        var myModal = new bootstrap.Modal(document.getElementById("exampleModal"), {});
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
                lat: position.coords.latitude,
                long: position.coords.longitude
            }

            console.log(coords);

            map = L.map('map', {
                attributionControl: false
            }).setView([coords.lat, coords.long], 18);

            map.options.minZoom = 15;
            map.options.maxZoom = 18;

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            await putLaundry()

            you = L.marker(new L.LatLng(coords.lat, coords.long), {
                    icon: currentPosition,
                    draggable: false
                }).addTo(map)
                .bindPopup('You are here!');

            map.on('moveend', putLaundry);
        }

        getLocation()

        function getMyPosition() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    lat = position.coords.latitude;
                    long = position.coords.longitude;
                    var accuracy = position.coords.accuracy;
                    if (you) map.removeLayer(you);
                    if (circle) map.removeLayer(circle);
                    you = L.marker([lat, long], {
                        icon: currentPosition,
                    }).addTo(map);
                    circle = L.circle([lat, long], {
                        radius: accuracy
                    });
                    var featureGroup = L.featureGroup([you, circle]).addTo(map);
                    map.fitBounds(featureGroup.getBounds());
                }, showError);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        myPosition.addEventListener('click', getMyPosition);

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation. please enable it");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        async function getNearestLaundry(bounds) {
            let {
                swLat,
                swLng,
                neLat,
                neLng
            } = bounds
            let request = await fetch(
                `/api/get-nearest-area?swLat=${swLat}&swLng=${swLng}&neLat=${neLat}&neLng=${neLng}`)
            let result = await request.json()
            return result
        }

        async function putLaundry() {
            let bounds = {
                swLat: map.getBounds()._southWest.lat,
                swLng: map.getBounds()._southWest.lng,
                neLat: map.getBounds()._northEast.lat,
                neLng: map.getBounds()._northEast.lng
            }

            var laundryIcon = new LeafIcon({
                iconUrl: "{{ asset('icon_laundry.svg') }}"
            });

            let laundries = await getNearestLaundry(bounds);
            laundries.forEach(function(item) {
                let id = item.id
                if (!markers[id]) {
                    let marker = new L.marker(new L.LatLng(item.lat, item.long), {
                        icon: laundryIcon,
                        draggable: false
                    }).addTo(map)

                    marker.on('click', async () => {
                        await getLaundry(id)
                        myModal.show();
                    })
                    markers[id] = marker
                }
            })
        }

        async function getLaundry(id) {
            let request = await fetch(
                `/api/laundry/${id}`)
            let result = await request.json()

            document.getElementById('name-field').textContent = result.name
            document.getElementById('owner-field').textContent = result.user.name
            document.getElementById('permission-field').textContent = result.no_izin
            document.getElementById('address-field').textContent = result.address
            document.getElementById('phone-field').textContent = result.user.phone_number
            document.getElementById('image-field').style.backgroundImage = `url(${result.image})`

            if (result.rate !== 0) {
                document.getElementById('rating-wrapper').classList.remove('d-none')
                let rates = '';
                for (let i = 0; i < result.rate; i++) {
                    rates += "<i class='fas fa-fw fa-star text-warning'></i>"
                }
                if (result.rate < 5) {
                    for (let i = 0; i < 5 - result.rate; i++) {
                        rates += "<i class='fas fa-fw fa-star text-muted'></i>"
                    }
                }
                document.getElementById('rating').innerHTML = rates
                document.getElementById('ulasan').textContent = `${result.reviews.length} ulasan`
            } else {
                document.getElementById('rating-wrapper').classList.add('d-none')
            }

            document.getElementById('open-explore').setAttribute('href', "{{ route('open.explore') }}?id=" + id)
        }
    </script>
</body>

</html>
