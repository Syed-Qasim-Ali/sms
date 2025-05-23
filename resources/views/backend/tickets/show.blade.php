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
                                                                        id="location_lat" value="40.65269989999999">
                                                                    <input type="hidden" name="location_lng"
                                                                        id="location_lng" value="-73.9542887">

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
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHuiMaFjSnFTQfRmAfTp9nZ9VpTICgNrc&callback=initMap&libraries=marker"
        async defer loading="async"></script>
    <script>
        function initMap() {
            const startLat = parseFloat("{{ $order->start_location_lat ?? '0' }}");
            const startLng = parseFloat("{{ $order->start_location_lng ?? '0' }}");

            const startLocation = {
                lat: startLat,
                lng: startLng
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: startLocation,
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map
            });

            new google.maps.Marker({
                map,
                position: startLocation,
                title: "Pickup Location"
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    // ✅ Fill input fields
                    document.getElementById('location_lat').value = userLat;
                    document.getElementById('location_lng').value = userLng;

                    const userLocation = {
                        lat: userLat,
                        lng: userLng
                    };

                    // Add marker
                    new google.maps.Marker({
                        map,
                        position: userLocation,
                        title: "Your Location",
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    });

                    // Draw route
                    directionsService.route({
                        origin: userLocation,
                        destination: startLocation,
                        travelMode: google.maps.TravelMode.DRIVING,
                    }, function(response, status) {
                        if (status === "OK") {
                            directionsRenderer.setDirections(response);
                        } else {
                            console.error("Route failed:", status);
                        }
                    });

                }, function(error) {
                    // alert("Location access denied or unavailable.");
                    console.error("Geo Error:", error);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    </script>
@endsection
