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
                                            @if (session('success'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

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

                                            <!-- Modal -->
                                            <div class="modal fade" id="submitTicketModal" tabindex="-1"
                                                aria-labelledby="submitTicketModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Are you sure?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            This ticket will be closed and sent to the site contact for
                                                            approval.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Not Right Now</button>

                                                            <button type="submit" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#submitTollModal">Yes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Toll -->
                                            <div class="modal fade" id="submitTollModal" tabindex="-1"
                                                aria-labelledby="submitTollModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Any Tolls?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Did pay any tolls while on the job that you wish to include on
                                                            this ticket?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form
                                                                action="{{ route('ticket.submit', $pickuplatlng->ticket_uuid) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary">No</button>
                                                            </form>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Yes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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

                                                        <div class="bg-white text-dark p-3 rounded mt-3 shadow-sm"
                                                            style="border: 1px solid #ddd;">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-1">
                                                                <b>Checked In</b>
                                                                <b>{{ $checkedin->created_at->format('H:i:s') }}</b>
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="bg-white text-dark p-3 rounded shadow-sm"
                                                            style="border: 1px solid #ddd;">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-1">
                                                                <h6 class="mb-0">Pickup</h6>
                                                                <small
                                                                    class="text-muted"><b>{{ \Carbon\Carbon::parse($pickuplatlng->pickup_time)->format('h:i:s A') }}</b></small>
                                                            </div>
                                                            <p class="mb-1">{{ $pickuplatlng->quantity }} tons of
                                                                {{ $pickuplatlng->material }}</p>

                                                            <div id="map"
                                                                style="height: 200px; width: 100%; border-radius: 4px; overflow: hidden;">
                                                            </div>
                                                        </div>
                                                        @if (is_null($pickuplatlng->drop_lat) && is_null($pickuplatlng->drop_lng))
                                                            <form
                                                                action="{{ route('dropoff', $pickuplatlng->ticket_uuid) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="drop_lat" id="drop_lat">
                                                                <input type="hidden" name="drop_lng" id="drop_lng">
                                                                <button type="submit"
                                                                    class="btn btn-success rounded-circle"
                                                                    style="position: absolute; bottom: 15px; right: 15px; z-index: 10;">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <br><br>
                                                        @if (is_null($pickuplatlng->image))
                                                            <div class="position-relative p-3 rounded"
                                                                style="min-height: 100px;">
                                                                <h6 class="mb-1">Images</h6>
                                                                <p class="text-muted" style="font-size: 0.9rem;">Press the
                                                                    camera
                                                                    icon above to upload a new attachment.</p>

                                                                <form
                                                                    action="{{ route('image.upload', $pickuplatlng->ticket_uuid) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="file" name="image" id="imageUpload"
                                                                        accept="image/*" onchange="this.form.submit()"
                                                                        hidden>

                                                                    <!-- Floating Camera Button -->
                                                                    <button type="button"
                                                                        onclick="document.getElementById('imageUpload').click()"
                                                                        class="btn btn-light rounded-circle position-absolute d-flex justify-content-center align-items-center"
                                                                        style="top: -15px; right: -15px; width: 45px; height: 45px; box-shadow: 0 0 5px rgba(0,0,0,0.2); border: 3px solid #4CAF50;">
                                                                        <i class="fa fa-camera"
                                                                            style="font-size: 18px;"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                        @if (!is_null($pickuplatlng->drop_lat) && !is_null($pickuplatlng->drop_lng) && !is_null($pickuplatlng->drop_time))
                                                            <div class="bg-white text-dark p-3 rounded shadow-sm"
                                                                style="border: 1px solid #ddd;">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-1">
                                                                    <h6 class="mb-0">Delivery</h6>
                                                                    <small class="text-muted">
                                                                        <b>{{ \Carbon\Carbon::parse($pickuplatlng->drop_time)->format('h:i:s A') }}</b>
                                                                    </small>
                                                                </div>
                                                                <div id="dropMap"
                                                                    style="height: 200px; width: 100%; border-radius: 4px; overflow: hidden;">
                                                                </div>
                                                            </div>
                                                            {{-- <button type="submit"
                                                                class="btn btn-success btn-lg w-100 mt-3"
                                                                id="arrivalButton">
                                                                Submit Ticket
                                                            </button> --}}

                                                            @if ($status->status !== 'under_review')
                                                                <button type="button" class="btn btn-success w-100 mt-3"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#submitTicketModal">
                                                                    Submit Ticket
                                                                </button>
                                                            @endif
                                                        @endif
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lat = {{ $pickuplatlng->pickup_lat ?? 0 }};
            var lng = {{ $pickuplatlng->pickup_lng ?? 0 }};
            var location = {
                lat: lat,
                lng: lng
            };

            // Initialize Google Map
            var map = new google.maps.Map(document.getElementById("map"), {
                center: location,
                zoom: 14,
            });

            // Add marker
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "Pickup Location"
            });

            // Info window (optional)
            // var infoWindow = new google.maps.InfoWindow({
            //     content: "<strong>Pickup Location</strong>"
            // });

            infoWindow.open(map, marker);

            // Open info window on marker click
            marker.addListener("click", function() {
                infoWindow.open(map, marker);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        document.getElementById("drop_lat").value = lat;
                        document.getElementById("drop_lng").value = lng;

                        console.log("📍 Current location captured for drop-off:", lat, lng);
                    },
                    function(error) {
                        console.error("❌ Failed to get location:", error.message);
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lat = {{ $pickuplatlng->drop_lat ?? 0 }};
            var lng = {{ $pickuplatlng->drop_lng ?? 0 }};
            var location = {
                lat: lat,
                lng: lng
            };

            // Initialize Google Map
            var map = new google.maps.Map(document.getElementById("dropMap"), {
                center: location,
                zoom: 14,
            });

            // Add marker
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "DropOff Location"
            });

            // Info window (optional)
            // var infoWindow = new google.maps.InfoWindow({
            //     content: "<strong>DropOff Location</strong>"
            // });

            infoWindow.open(map, marker);

            // Open info window on marker click
            marker.addListener("click", function() {
                infoWindow.open(map, marker);
            });
        });
    </script>
@endsection
