@extends('backend.layout.app')
@section('content')
    <div class="col-md-10">
        <section class="main-sec">
            <div class="seperater">
                <div class="main-head">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pagetitle">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                                        <li class="breadcrumb-item">Tables</li>
                                        <li class="breadcrumb-item active">Tickets</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sm-header">
                                <a href="#"><img src="{{ asset('Backend/assets/images/Group 38.png') }}"
                                        alt=""></a>
                                <a href="#"><img src="{{ asset('Backend/assets/images/image 239.png') }}"
                                        alt=""></a>
                            </div>
                        </div>

                        <div class="usermanagesec">
                            <div class="row align-items-center">
                                <div class="col-md-11">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h2>Tickets Management</h2>

                                            </div>
                                            @session('success')
                                                <div class="alert alert-success" role="alert">
                                                    {{ $value }}
                                                </div>
                                            @endsession

                                            @if (count($errors) > 0)
                                                <div class="alert alert-danger">
                                                    <strong>Whoops!</strong> There were some problems with your
                                                    input.<br><br>
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="container mt-4">
                                                <div class="row">
                                                    <div class="col-md-12 bg-dark text-white p-4 rounded">
                                                        <h3>{{ $order->start_location }}</h3>
                                                        <p>Work Order # <strong>{{ $order->order_number }}</strong></p>
                                                        <p>Company: <strong>{{ $order->company->company_name }}</strong></p>
                                                        <p>Ticket Status: <strong>{{ $status->status }}</strong></p>

                                                        <!-- Start Site Contact -->
                                                        <div class="bg-white text-dark p-3 rounded mt-3">
                                                            <p class="mb-1">Start Site Contact</p>
                                                            <p><b>{{ $order->ordersitecontact->first()->site_contact }}</b>
                                                            </p>
                                                            <p>Truck Hourly Rate: {{ $truck->hourly_rate }} + Trailer Rate:
                                                                {{ $trailer->rate_modifier }} =
                                                                Total: {{ $truck->hourly_rate + $trailer->rate_modifier }}
                                                            </p>
                                                        </div>

                                                        <!-- Spacer -->
                                                        <div class="mt-3"></div>

                                                        <!-- Awaiting Arrival Section -->
                                                        <div class="bg-secondary text-white p-3 rounded mt-3">
                                                            <h3>Awaiting Arrival...</h3>
                                                            <div class="card p-3 mt-3">
                                                                <div id="map" style="height: 300px; width: 100%;">
                                                                </div>
                                                                <form method="POST"
                                                                    action="{{ route('tickets.arrival', $status->uuid) }}">
                                                                    @csrf

                                                                    <!-- Hidden fields to store user's current lat/lng -->
                                                                    <input type="hidden" name="location_lat"
                                                                        id="location_lat">
                                                                    <input type="hidden" name="location_lng"
                                                                        id="location_lng">

                                                                    <button type="submit"
                                                                        class="btn btn-success btn-lg w-100 mt-3"
                                                                        id="arrivalButton">
                                                                        I Have Arrived
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHuiMaFjSnFTQfRmAfTp9nZ9VpTICgNrc&callback=initMap&v=weekly&libraries=marker"
        defer></script>

    <script>
        async function initMap() {
            /* 1 — import the advanced-marker classes */
            const {
                AdvancedMarkerElement,
                PinElement
            } =
            await google.maps.importLibrary("marker"); /* docs: turn0search2 */

            /* 2 — static pickup location that came from Laravel */
            const startLocation = {
                lat: {{ $order->start_location_lat ?? 0 }},
                lng: {{ $order->start_location_lng ?? 0 }}
            };

            /* 3 — build the map (mapId is required for advanced markers) */
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: startLocation,
                mapId: "DEMO_MAP_ID" /* replace with a real map-style ID */
            });

            /* 4 — marker at the pickup point */
            new AdvancedMarkerElement({
                /* migration guide: turn0search9 */
                map,
                position: startLocation,
                title: "Pickup Location"
            });

            /* 5 — prepare directions objects */
            const directionsService = new google.maps.DirectionsService(); /* turn0search1 */
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map
            });

            /* 6 — helper to draw the blue route line */
            function drawRoute(origin) {
                directionsService.route({
                    origin,
                    destination: startLocation,
                    travelMode: google.maps.TravelMode.DRIVING
                }, (res, status) => {
                    if (status === "OK") directionsRenderer.setDirections(res);
                });
            }

            /* 7 — helper to stash coords into the hidden fields */
            function setHiddenInputs(lat, lng) {
                document.getElementById("location_lat").value = lat;
                document.getElementById("location_lng").value = lng;
            }

            /* 8 — default user marker so something shows even if geolocation fails */
            let userMarker = new AdvancedMarkerElement({
                map,
                position: {
                    lat: 40.653,
                    lng: -73.9545
                }, // Brooklyn fallback
                title: "Your Location",
                content: new PinElement({
                    background: "#4285F4",
                    glyph: "🧍" /* any emoji or glyph you like */
                }).element
            });

            /* 9 — ask the browser for the real position */
            if (navigator.geolocation) {
                /* MDN: turn0search3 */
                navigator.geolocation.getCurrentPosition(pos => {
                    const here = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    };
                    userMarker.position = here; // move marker
                    map.setCenter(here);
                    setHiddenInputs(here.lat, here.lng); // fill the hidden <input>s
                    drawRoute(here); // draw the blue line & ETA
                }, err => {
                    /* error codes: turn0search14 */
                    console.error(err);
                    drawRoute(userMarker.position); // still show a route
                    setHiddenInputs(userMarker.position.lat, userMarker.position.lng);
                });
            } else {
                // Geolocation not supported
                drawRoute(userMarker.position);
                setHiddenInputs(userMarker.position.lat, userMarker.position.lng);
            }
        }
    </script>


@endsection
