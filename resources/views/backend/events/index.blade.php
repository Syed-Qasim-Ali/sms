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
                                                <h2>Events Management</h2>

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

                                            <div class="container mt-4">
                                                <div class="row">
                                                    <div class="col-md-12 bg-dark text-white p-4 rounded">
                                                        <h6 class="text-center">Pickup</h6>
                                                        <form action="{{ Route('events.store', $event->ticket_uuid) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3 row">
                                                                <label for="material"
                                                                    class="col-sm-2 col-form-label"><strong>Material
                                                                        #</strong></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                        id="material" name="material">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3 row">
                                                                <label for="quantity"
                                                                    class="col-sm-2 col-form-label"><strong>Quantity
                                                                        #</strong></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                        id="quantity" name="quantity">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3 row">
                                                                <label for="ticket"
                                                                    class="col-sm-2 col-form-label"><strong>Ticket ID
                                                                        #</strong></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                        id="ticket" name="ticket_uuid"
                                                                        value="{{ $event->ticket_uuid }}">
                                                                </div>
                                                            </div>

                                                            <!-- Spacer -->
                                                            <div class="mt-3">
                                                                <input type="text" name="pickup_lat" id="location_lat">
                                                                <input type="text" name="pickup_lng" id="location_lng">
                                                            </div>



                                                            <button type="submit"
                                                                class="btn btn-success btn-lg w-100 mt-3">
                                                                Add Event
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
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Fill the hidden input fields
                        document.getElementById("location_lat").value = lat;
                        document.getElementById("location_lng").value = lng;

                        console.log("📍 Location captured:", lat, lng);
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
