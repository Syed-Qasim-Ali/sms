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
                                        <li class="breadcrumb-item">Orders</li>
                                        <li class="breadcrumb-item active">Update</li>
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
                                                <h2>Edit Order</h2>
                                                <a class="btn btn-success btn-sm mb-2" href="{{ route('orders.index') }}">
                                                    <i class="fa-solid fa-arrow-left"></i> Back
                                                </a>
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
                                            <form action="{{ route('orders.update', $orders->id) }}" method="POST"
                                                autocomplete="off">

                                                @csrf
                                                @method('PUT')

                                                <div class="card shadow-sm p-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="company_id" class="form-label fw-bold">Company
                                                                Name</label>
                                                            <select class="form-control" id="company_id" name="company_id">
                                                                <option value="">Select a Company</option>
                                                                @foreach ($companies as $company)
                                                                    <option value="{{ $company->id }}"
                                                                        {{ $orders->company_id == $company->id ? 'selected' : '' }}>
                                                                        {{ $company->company_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="job" class="form-label fw-bold">Job</label>
                                                            <select class="form-control" id="job" name="job">
                                                                <option value="">Select a Job</option>
                                                                @foreach ($jobs as $job)
                                                                    <option value="{{ $job->id }}"
                                                                        {{ $orders->job == $job->id ? 'selected' : '' }}>
                                                                        {{ $job->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label for="date" class="form-label fw-bold">Date</label>
                                                            <input type="date" class="form-control" id="date"
                                                                name="date" value="{{ $orders->date }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="pay_rate" class="form-label fw-bold">Pay
                                                                Rate</label>
                                                            <div class="input-group">
                                                                <input type="number" step="0.01" class="form-control"
                                                                    id="pay_rate" name="pay_rate"
                                                                    placeholder="Enter pay rate"
                                                                    value="{{ $orders->pay_rate }}">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <div class="mt-2 d-flex gap-3">
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="pay_rate_type" id="per_hour" value="per_hour"
                                                                        {{ old('pay_rate_type', $orders->pay_rate_type) == 'per_hour' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="per_hour">Per
                                                                        Hour</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="pay_rate_type" id="per_ton" value="per_ton"
                                                                        {{ old('pay_rate_type', $orders->pay_rate_type) == 'per_ton' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="per_ton">Per
                                                                        Ton</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="pay_rate_type" id="per_load" value="per_load"
                                                                        {{ old('pay_rate_type', $orders->pay_rate_type) == 'per_load' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="per_load">Per
                                                                        Load</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-md-6">
                                                            <!-- Start Location -->
                                                            <label for="start_location" class="form-label fw-bold">Start
                                                                Location</label>
                                                            <input type="text" class="form-control"
                                                                id="start_location" placeholder="Enter start location"
                                                                name="start_location"
                                                                value="{{ $orders->start_location }}">
                                                            <input type="hidden" id="start_lat" name="start_lat"
                                                                value="{{ $orders->start_location_lat }}">
                                                            <input type="hidden" id="start_lon" name="start_lon"
                                                                value="{{ $orders->start_location_lng }}">
                                                            <div id="start_suggestions" class="suggestions"></div>
                                                        </div>

                                                        <!-- End Location -->
                                                        <div class="col-md-6">
                                                            <label for="end_location" class="form-label fw-bold">End
                                                                Location</label>
                                                            <input type="text" class="form-control" id="end_location"
                                                                placeholder="Enter end location" name="end_location"
                                                                value="{{ $orders->end_location }}">
                                                            <input type="hidden" id="end_lat" name="end_lat"
                                                                value="{{ $orders->end_location_lat }}">
                                                            <input type="hidden" id="end_lon" name="end_lon"
                                                                value="{{ $orders->end_location_lng }}">
                                                            <div id="end_suggestions" class="suggestions"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Map Container (Separate Row) -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div id="map"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <label class="form-label fw-bold">Site Contact
                                                                    <span class="text-danger">*</span></label>
                                                                <button class="btn btn-outline-secondary btn-sm"
                                                                    type="button" onclick="addContact()">+</button>
                                                            </div>
                                                            @foreach ($orders->ordersitecontact as $contacts)
                                                                <div id="contact-list">
                                                                    <div class="input-group mb-2">
                                                                        <input type="text" class="form-control"
                                                                            name="site_contacts[]"
                                                                            placeholder="Test Contact (+1 647 868 8454)"
                                                                            value="{{ $contacts->site_contact }}">
                                                                        <button class="btn btn-outline-danger"
                                                                            type="button"
                                                                            onclick="removeContact(this)">×</button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="material"
                                                                class="form-label fw-bold">Material</label>
                                                            <input type="text" class="form-control" id="material"
                                                                name="material" placeholder="Asphalt Millings"
                                                                value="{{ $orders->material }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="quantity" class="form-label fw-bold">Quantity
                                                                <small>(Tons)</small> </label>
                                                            <input type="number" class="form-control" id="quantity"
                                                                name="quantity" value="{{ $orders->quantity }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <label class="form-label fw-bold">Timeslots <span
                                                                        class="text-danger">*</span></label>
                                                                <button class="btn btn-outline-secondary btn-sm"
                                                                    type="button" onclick="addTimeslot()">+</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">QUANTITY</div>
                                                        <div class="col-md-6">START TIME</div>

                                                        @foreach ($orders->ordertimeslot as $time)
                                                            <div class="timeslot-row row mt-2">
                                                                <div class="col-md-6">
                                                                    <input type="number" class="form-control"
                                                                        name="timeslots[quantity][]"
                                                                        value="{{ $time->truck_quantity }}">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="time" class="form-control"
                                                                        name="timeslots[start_time][]"
                                                                        value="{{ $time->start_time }}">
                                                                </div>
                                                                <div class="col-md-1 text-center">
                                                                    <button class="btn btn-danger btn-sm" type="button"
                                                                        onclick="removeTimeslot(this)">✖</button>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <div id="timeslot-list">
                                                            <!-- Dynamic rows will be added here -->
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <label for="instruction"
                                                            class="form-label fw-bold">Instructions</label>
                                                        <textarea class="form-control" id="instruction" name="instruction" rows="3">{{ $orders->instruction }}</textarea>
                                                    </div>

                                                    <div class="mt-3">
                                                        <label class="form-label fw-bold">Status</label><br>

                                                        <!-- Checkbox -->
                                                        <input type="checkbox" id="status_draft" value="draft"
                                                            onchange="toggleStatus(this)"
                                                            {{ old('status', $orders->status ?? 'active') === 'draft' ? 'checked' : '' }}>
                                                        <label for="status_draft">Draft</label>

                                                        <!-- Hidden field (Default value active) -->
                                                        <input type="hidden" name="status" id="hidden_status"
                                                            value="{{ old('status', $orders->status ?? 'active') }}">
                                                    </div>

                                                    <div class="mt-4">
                                                        <button type="submit" class="btn btn-success">Update
                                                            Order</button>
                                                        <a href="{{ route('orders.index') }}"
                                                            class="btn btn-secondary">Cancel</a>
                                                    </div>
                                                </div>

                                            </form>
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
        function addContact() {
            let container = document.createElement("div");
            container.className = "input-group mb-2";

            let input = document.createElement("input");
            input.type = "text";
            input.className = "form-control";
            input.name = "site_contacts[]";
            input.placeholder = "Enter Contact Number";

            let removeBtn = document.createElement("button");
            removeBtn.className = "btn btn-outline-danger";
            removeBtn.type = "button";
            removeBtn.textContent = "×";
            removeBtn.onclick = function() {
                removeContact(this);
            };

            container.appendChild(input);
            container.appendChild(removeBtn);
            document.getElementById("contact-list").appendChild(container);
        }

        function removeContact(element) {
            element.parentElement.remove();
        }
    </script>

    <script>
        function addTimeslot() {
            let timeslotList = document.getElementById("timeslot-list");

            // Create row div
            let row = document.createElement("div");
            row.className = "row mt-3 align-items-center timeslot-row";

            // Quantity input
            let quantityDiv = document.createElement("div");
            quantityDiv.className = "col-md-6";
            let quantityInput = document.createElement("input");
            quantityInput.type = "number";
            quantityInput.className = "form-control";
            quantityInput.name = "timeslots[quantity][]";
            quantityInput.placeholder = "Enter quantity";
            quantityDiv.appendChild(quantityInput);

            // Start Time input
            let startTimeDiv = document.createElement("div");
            startTimeDiv.className = "col-md-5";
            let startTimeInput = document.createElement("input");
            startTimeInput.type = "time";
            startTimeInput.className = "form-control";
            startTimeInput.name = "timeslots[start_time][]";
            startTimeDiv.appendChild(startTimeInput);

            // Remove Button
            let removeDiv = document.createElement("div");
            removeDiv.className = "col-md-1 text-center";
            let removeBtn = document.createElement("button");
            removeBtn.className = "btn btn-danger btn-sm";
            removeBtn.innerHTML = "✖";
            removeBtn.type = "button";
            removeBtn.onclick = function() {
                timeslotList.removeChild(row);
            };
            removeDiv.appendChild(removeBtn);

            // Append elements to row
            row.appendChild(quantityDiv);
            row.appendChild(startTimeDiv);
            row.appendChild(removeDiv);

            // Append row to timeslot-list
            timeslotList.appendChild(row);
        }
    </script>

    <script>
        let map = L.map('map').setView([24.8607, 67.0011], 12); // Default: Karachi

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let routingControl;
        let mapContainer = document.getElementById("map");
        mapContainer.style.display = "none"; // Hide the map initially

        function getSuggestions(query, inputFieldId, suggestionsDivId) {
            let suggestionsBox = document.getElementById(suggestionsDivId);

            if (!suggestionsBox) {
                console.error(`Element with ID '${suggestionsDivId}' not found.`);
                return;
            }

            if (query.length < 3) {
                suggestionsBox.innerHTML = "";
                return;
            }

            let country = "USA"; // or dynamically set this based on user or app config
            let searchQuery = `${query}, ${country}`;
            let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    suggestionsBox.innerHTML = "";
                    if (!data || data.length === 0) {
                        suggestionsBox.innerHTML = `<div class="suggestion-item">No results found</div>`;
                        return;
                    }

                    data.forEach(place => {
                        let div = document.createElement("div");
                        div.classList.add("suggestion-item");
                        div.innerText = place.display_name;

                        div.onclick = function() {
                            let inputField = document.getElementById(inputFieldId);
                            inputField.value = place.display_name;
                            inputField.setAttribute("data-lat", place.lat);
                            inputField.setAttribute("data-lon", place.lon);
                            suggestionsBox.innerHTML = "";

                            showMapIfReady(); // ✅ Check if both locations are selected
                            calculateRoute();
                        };

                        suggestionsBox.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error("Error fetching location data:", error);
                    suggestionsBox.innerHTML = `<div class="suggestion-item">Error fetching data</div>`;
                });
        }

        function showMapIfReady() {
            let startInput = document.getElementById("start_location");
            let endInput = document.getElementById("end_location");

            let startLat = startInput.getAttribute("data-lat");
            let startLon = startInput.getAttribute("data-lon");
            let endLat = endInput.getAttribute("data-lat");
            let endLon = endInput.getAttribute("data-lon");

            if (startLat && startLon && endLat && endLon) {
                mapContainer.style.display = "block"; // Show map only when both locations are selected
            } else {
                mapContainer.style.display = "none"; // Hide map if locations are missing
            }
        }

        function calculateRoute() {
            let startInput = document.getElementById("start_location");
            let endInput = document.getElementById("end_location");

            let startLat = startInput.getAttribute("data-lat");
            let startLon = startInput.getAttribute("data-lon");
            let endLat = endInput.getAttribute("data-lat");
            let endLon = endInput.getAttribute("data-lon");

            // Hidden inputs mein values save karein
            document.getElementById("start_lat").value = startLat;
            document.getElementById("start_lon").value = startLon;
            document.getElementById("end_lat").value = endLat;
            document.getElementById("end_lon").value = endLon

            if (!startLat || !startLon || !endLat || !endLon) {
                return;
            }

            let startLatLng = L.latLng(parseFloat(startLat), parseFloat(startLon));
            let endLatLng = L.latLng(parseFloat(endLat), parseFloat(endLon));

            if (routingControl) {
                map.removeControl(routingControl);
            }

            routingControl = L.Routing.control({
                waypoints: [startLatLng, endLatLng],
                routeWhileDragging: true,
                lineOptions: {
                    styles: [{
                        color: 'red',
                        weight: 5
                    }] // Customize route appearance
                },
                createMarker: function(i, waypoint, n) {
                    return L.marker(waypoint.latLng, {
                        draggable: false
                    });
                }
            }).addTo(map);

            document.querySelector(".leaflet-routing-container").style.display = "none";
        }

        function debounce(func, delay) {
            let timer;
            return function() {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, arguments), delay);
            };
        }

        document.getElementById("start_location").addEventListener("keyup", debounce(function() {
            getSuggestions(this.value, "start_location", "start_suggestions");
        }, 500));

        document.getElementById("end_location").addEventListener("keyup", debounce(function() {
            getSuggestions(this.value, "end_location", "end_suggestions");
        }, 500));
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Options",
                allowClear: true
            });

            // Ensure Select2 recognizes selected values
            setTimeout(() => {
                $('.select2').trigger('change');
            }, 500);
        });
    </script>

    <script>
        function toggleStatus(checkbox) {
            let hiddenStatus = document.getElementById("hidden_status");
            hiddenStatus.value = checkbox.checked ? "draft" : "active";
        }

        // Ensure correct checkbox state on page load
        document.addEventListener("DOMContentLoaded", function() {
            let checkbox = document.getElementById("status_draft");
            let hiddenStatus = document.getElementById("hidden_status");

            console.log("Hidden Status Value:", hiddenStatus.value); // Debugging

            checkbox.checked = (hiddenStatus.value.trim() === "draft");

            // Update checkbox & hidden field sync on form reset
            document.querySelector("form").addEventListener("reset", function() {
                setTimeout(() => {
                    hiddenStatus.value = checkbox.checked ? "draft" : "active";
                }, 10);
            });
        });
    </script>

@endsection
