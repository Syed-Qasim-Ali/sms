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
                                                                    <input type="text" name="location_lat"
                                                                        id="location_lat">
                                                                    <input type="text" name="location_lng"
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHuiMaFjSnFTQfRmAfTp9nZ9VpTICgNrc&callback=initMap" async
        defer></script>
    <script>
        function initMap() {
            const lat = {{ $order->start_location_lat ?? '0' }};
            const lng = {{ $order->start_location_lng ?? '0' }};
            const pickupAddress = @json($order->start_location ?? 'No address found');

            const startLocation = {
                lat: parseFloat(lat),
                lng: parseFloat(lng),
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                center: startLocation,
                zoom: 14,
            });

            const marker = new google.maps.Marker({
                position: startLocation,
                map: map,
                title: pickupAddress || "No Address Available",
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<strong>Pickup Location:</strong><br>${pickupAddress}`
            });

            infoWindow.open(map, marker);

            marker.addListener("click", function() {
                infoWindow.open(map, marker);
            });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Fill hidden input fields
                        document.getElementById("location_lat").value = lat;
                        document.getElementById("location_lng").value = lng;

                        // Display Google Map
                        const userLocation = {
                            lat: lat,
                            lng: lng
                        };

                        const map = new google.maps.Map(document.getElementById("map"), {
                            zoom: 15,
                            center: userLocation,
                        });

                        new google.maps.Marker({
                            position: userLocation,
                            map: map,
                            title: "You are here",
                        });
                    },
                    function(error) {
                        console.error("❌ Error getting location:", error.message);
                    }
                );
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        });
    </script>
@endsection
