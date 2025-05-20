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
                                        <li class="breadcrumb-item active">Create</li>
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
                                                <h2>Add New Order</h2>
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

                                            <form action="{{ route('orders.store') }}" method="POST" autocomplete="off">
                                                @csrf

                                                <div class="card shadow-sm p-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="company_id" class="form-label fw-bold">Company
                                                                Name</label>
                                                            <select class="form-control" id="company_id" name="company_id">
                                                                <option value="">Select a Company</option>
                                                                @foreach ($companies as $company)
                                                                    <option value="{{ $company->id }}">
                                                                        {{ $company->company_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="job" class="form-label fw-bold">Job</label>
                                                            <select class="form-control" id="job" name="job">
                                                                <option value="">Select a Job</option>
                                                                <!-- Jobs will be populated dynamically -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label for="date" class="form-label fw-bold">Date</label>
                                                            <input type="date" class="form-control" id="date"
                                                                name="date">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="pay_rate" class="form-label fw-bold">Pay
                                                                Rate</label>
                                                            <div class="input-group">
                                                                <input type="number" step="0.01" class="form-control"
                                                                    id="pay_rate" name="pay_rate"
                                                                    placeholder="Enter pay rate">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <div class="mt-2 d-flex gap-3">
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="pay_rate_type" id="per_hour"
                                                                        value="per_hour">
                                                                    <label class="form-check-label" for="per_hour">Per
                                                                        Hour</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="pay_rate_type" id="per_ton" value="per_ton">
                                                                    <label class="form-check-label" for="per_ton">Per
                                                                        Ton</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="pay_rate_type" id="per_load"
                                                                        value="per_load">
                                                                    <label class="form-check-label" for="per_load">Per
                                                                        Load</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-md-6">
                                                            <label for="capabilities"
                                                                class="form-label fw-bold">Capabilities</label>
                                                            <select class="form-control select2" id="capabilities"
                                                                name="capabilities[]" multiple>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="specialties"
                                                                class="form-label fw-bold">Specialties</label>
                                                            <select class="form-control select2" id="specialties"
                                                                name="specialties[]" multiple>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-md-6">
                                                            <!-- Start Location -->
                                                            <label for="start_location" class="form-label fw-bold">Start
                                                                Location</label>
                                                            <input type="text" class="form-control"
                                                                id="start_location" placeholder="Enter start location"
                                                                name="start_location">
                                                            <input type="hidden" id="start_lat" name="start_lat">
                                                            <input type="hidden" id="start_lon" name="start_lon">
                                                            <div id="start_suggestions" class="suggestions"></div>
                                                        </div>

                                                        <!-- End Location -->
                                                        <div class="col-md-6">
                                                            <label for="end_location" class="form-label fw-bold">End
                                                                Location</label>
                                                            <input type="text" class="form-control" id="end_location"
                                                                placeholder="Enter end location" name="end_location">
                                                            <input type="hidden" id="end_lat" name="end_lat">
                                                            <input type="hidden" id="end_lon" name="end_lon">
                                                            <div id="end_suggestions" class="suggestions"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Map Container (Separate Row) -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div id="map"
                                                                style="width: 100%; height: 400px; display:none;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <label class="form-label fw-bold">Site Contact <span
                                                                        class="text-danger">*</span></label>
                                                                <button class="btn btn-outline-secondary btn-sm"
                                                                    type="button" onclick="addContact()">+</button>
                                                            </div>
                                                            <div id="contact-list">
                                                                <div class="input-group mb-2">
                                                                    <input type="tel" class="form-control"
                                                                        name="site_contacts[]"
                                                                        placeholder="Test Contact (+1 647 868 8454)">
                                                                    <button class="btn btn-outline-danger" type="button"
                                                                        onclick="removeContact(this)">×</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="material"
                                                                class="form-label fw-bold">Material</label>
                                                            <input type="text" class="form-control" id="material"
                                                                name="material" placeholder="Asphalt Millings">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="quantity" class="form-label fw-bold">Quantity
                                                                <small>(Tons)</small> </label>
                                                            <input type="number" class="form-control" id="quantity"
                                                                name="quantity">
                                                        </div>
                                                    </div>


                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <label class="form-label fw-bold">Timeslots
                                                                    <span class="text-danger">*</span></label>
                                                                <button class="btn btn-outline-secondary btn-sm"
                                                                    type="button" onclick="addTimeslot()">+</button>
                                                            </div>
                                                        </div>
                                                        <div <div class="col-md-6">QUANTITY</div>
                                                        <div class="col-md-6">START TIME</div>


                                                        <div id="timeslot-list">
                                                            <!-- Dynamic rows will be added here -->
                                                        </div>
                                                        <p class="p-2 text-muted">Please add at least one timeslot
                                                            using
                                                            the <strong>+</strong> icon above.</p>
                                                    </div>

                                                    <div class="mt-3">
                                                        <label for="instruction"
                                                            class="form-label fw-bold">Instructions</label>
                                                        <textarea class="form-control" id="instruction" name="instruction" rows="3"
                                                            placeholder="Please comply with all jobsite safety rules. Have a safe day."></textarea>
                                                    </div>

                                                    <div class="mt-3">
                                                        <label class="form-label fw-bold">Status</label><br>

                                                        <input type="checkbox" name="status" id="status_draft"
                                                            value="draft" onchange="toggleStatus(this)">
                                                        <label for="status_draft">Draft</label>

                                                        <input type="hidden" name="status" id="hidden_status"
                                                            value="active"> <!-- Default submit -->
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <button type="submit" class="btn btn-success">Submit Order</button>
                                                    <a href="{{ route('orders.index') }}"
                                                        class="btn btn-secondary">Cancel</a>
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

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHuiMaFjSnFTQfRmAfTp9nZ9VpTICgNrc&libraries=places&callback=initMap&loading=async"
        async defer></script>

    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let startAutocomplete, endAutocomplete;

        function initMap() {
            // Map init
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 37.0902,
                    lng: -95.7129
                },
                zoom: 4,
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false,
                polylineOptions: {
                    strokeColor: "blue",
                    strokeWeight: 5
                }
            });

            // Hide initially
            document.getElementById("map").style.display = "none";

            // Setup Autocomplete
            setupAutocomplete("start_location", "start_lat", "start_lon");
            setupAutocomplete("end_location", "end_lat", "end_lon");

            // Listen for manual clearing of input fields
            document.getElementById("start_location").addEventListener("input", handleLocationInput);
            document.getElementById("end_location").addEventListener("input", handleLocationInput);
        }

        function setupAutocomplete(inputId, latInputId, lonInputId) {
            const input = document.getElementById(inputId);
            const latInput = document.getElementById(latInputId);
            const lonInput = document.getElementById(lonInputId);

            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: {
                    country: "us"
                }
            });

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (!place.geometry || !place.geometry.location) return;

                const location = place.geometry.location;
                latInput.value = location.lat();
                lonInput.value = location.lng();

                checkAndDrawRoute();
            });
        }

        function checkAndDrawRoute() {
            const startLat = document.getElementById("start_lat").value;
            const startLon = document.getElementById("start_lon").value;
            const endLat = document.getElementById("end_lat").value;
            const endLon = document.getElementById("end_lon").value;

            if (startLat && startLon && endLat && endLon) {
                document.getElementById("map").style.display = "block";

                const start = new google.maps.LatLng(parseFloat(startLat), parseFloat(startLon));
                const end = new google.maps.LatLng(parseFloat(endLat), parseFloat(endLon));

                const request = {
                    origin: start,
                    destination: end,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(request, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        alert("Directions request failed due to " + status);
                    }
                });
            } else {
                document.getElementById("map").style.display = "none";
            }
        }

        function handleLocationInput() {
            const startInput = document.getElementById("start_location").value.trim();
            const endInput = document.getElementById("end_location").value.trim();

            if (!startInput || !endInput) {
                document.getElementById("map").style.display = "none";

                document.getElementById("start_lat").value = '';
                document.getElementById("start_lon").value = '';
                document.getElementById("end_lat").value = '';
                document.getElementById("end_lon").value = '';
            }
        }
    </script>


    {{-- <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let startAutocomplete, endAutocomplete;
        let mapContainer;

        function initMap() {
            mapContainer = document.getElementById("map");

            map = new google.maps.Map(mapContainer, {
                center: {
                    lat: 37.0902,
                    lng: -95.7129
                },
                zoom: 4
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map
            });

            // Setup Autocomplete
            startAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("start_location"), {
                    componentRestrictions: {
                        country: "us"
                    }
                }
            );
            endAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("end_location"), {
                    componentRestrictions: {
                        country: "us"
                    }
                }
            );

            startAutocomplete.addListener("place_changed", handlePlaceChange);
            endAutocomplete.addListener("place_changed", handlePlaceChange);
        }

        function handlePlaceChange() {
            const startPlace = startAutocomplete.getPlace();
            const endPlace = endAutocomplete.getPlace();

            if (startPlace && startPlace.geometry) {
                document.getElementById("start_lat").value = startPlace.geometry.location.lat();
                document.getElementById("start_lon").value = startPlace.geometry.location.lng();
            }

            if (endPlace && endPlace.geometry) {
                document.getElementById("end_lat").value = endPlace.geometry.location.lat();
                document.getElementById("end_lon").value = endPlace.geometry.location.lng();
            }

            showMapIfReady();
            calculateRoute();
        }

        function showMapIfReady() {
            const startLat = document.getElementById("start_lat").value;
            const startLon = document.getElementById("start_lon").value;
            const endLat = document.getElementById("end_lat").value;
            const endLon = document.getElementById("end_lon").value;

            if (startLat && startLon && endLat && endLon) {
                mapContainer.style.display = "block";
            } else {
                mapContainer.style.display = "none";
            }
        }

        function calculateRoute() {
            const start = new google.maps.LatLng(
                parseFloat(document.getElementById("start_lat").value),
                parseFloat(document.getElementById("start_lon").value)
            );
            const end = new google.maps.LatLng(
                parseFloat(document.getElementById("end_lat").value),
                parseFloat(document.getElementById("end_lon").value)
            );

            const request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            };

            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error("Directions request failed due to ", status);
                }
            });
        }
    </script> --}}


    {{-- <script>
        let map = L.map('map').setView([37.0902, -95.7129], 4); // Default: USA

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

            let searchQuery = `${query}, USA`;
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
                        color: 'blue',
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
    </script> --}}

    {{-- <script>
        document.getElementById('company_id').addEventListener('change', function() {
            let companyId = this.value;

            if (companyId) {
                fetch(`/get-company-data/${companyId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Update Jobs field
                        let jobField = document.getElementById('job');
                        jobField.innerHTML = ''; // Clear previous options

                        let activeJobs = data.jobs.filter(job => job.status === 'active');
                        let inactiveJobs = data.jobs.filter(job => job.status !== 'active');

                        if (activeJobs.length > 0) {
                            // Show active jobs
                            activeJobs.forEach(job => {
                                let option = document.createElement('option');
                                option.value = job.id;
                                option.textContent = job.name;
                                jobField.appendChild(option);
                            });
                        } else if (inactiveJobs.length > 0) {
                            // If only inactive jobs exist
                            let option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Job is Inactive';
                            jobField.appendChild(option);
                        } else {
                            // If no jobs at all
                            let option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No jobs recorded under the selected company';
                            jobField.appendChild(option);
                        }

                        // Update Capabilities field
                        let capabilitiesField = $('#capabilities');
                        capabilitiesField.empty(); // Purane options hatao

                        let activeCapabilities = data.capabilities.filter(capability => capability.status ===
                            'active');
                        let inactiveCapabilities = data.capabilities.filter(capability => capability.status !==
                            'active');

                        if (activeCapabilities.length > 0) {
                            activeCapabilities.forEach(capability => {
                                let newOption = new Option(capability.name, capability.id, false,
                                    false);
                                capabilitiesField.append(newOption);
                            });
                        } else if (inactiveCapabilities.length > 0) {
                            let newOption = new Option('Capability is Inactive', 'inactive', false,
                                false); // ✅ Fix: Empty value hataya
                            capabilitiesField.append(newOption);
                        } else {
                            let newOption = new Option('No capabilities recorded under the selected company',
                                'no-capability', false, false); // ✅ Fix
                            capabilitiesField.append(newOption);
                        }

                        // ✅ Select2 ko properly refresh karo
                        setTimeout(() => {
                            capabilitiesField.trigger('change.select2');
                        }, 200);

                        // Update Specialties field
                        let specialtiesField = $('#specialties');
                        specialtiesField.empty(); // Clear previous options

                        let activeSpecialties = data.specialties.filter(specialty => specialty.status ===
                            'active');
                        let inactiveSpecialties = data.specialties.filter(specialty => specialty.status !==
                            'active');

                        if (activeSpecialties.length > 0) {
                            activeSpecialties.forEach(specialty => {
                                let newOption = new Option(specialty.name, specialty.id, false, false);
                                specialtiesField.append(newOption);
                            });
                        } else if (inactiveSpecialties.length > 0) {
                            let newOption = new Option('Specialty is Inactive', 'inactive', false,
                                false); // ✅ Fix: Empty value replaced
                            specialtiesField.append(newOption);
                        } else {
                            let newOption = new Option('No specialties recorded under the selected company',
                                'no-specialty', false, false); // ✅ Fix
                            specialtiesField.append(newOption);
                        }

                        // ✅ Refresh select2 properly
                        setTimeout(() => {
                            specialtiesField.trigger('change.select2');
                        }, 200);

                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
        });
    </script> --}}

    <script>
        document.getElementById('company_id').addEventListener('change', function() {
            let companyId = this.value;

            if (companyId) {
                fetch(`/get-company-data/${companyId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // ✅ Jobs
                        let jobField = document.getElementById('job');
                        jobField.innerHTML = '';

                        if (data.jobs && data.jobs.length > 0) {
                            let activeJobs = data.jobs.filter(job => job.status === 'active');
                            let inactiveJobs = data.jobs.filter(job => job.status !== 'active');

                            if (activeJobs.length > 0) {
                                activeJobs.forEach(job => {
                                    let option = document.createElement('option');
                                    option.value = job.id;
                                    option.textContent = job.name;
                                    jobField.appendChild(option);
                                });
                            } else {
                                let option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'Job is Inactive';
                                jobField.appendChild(option);
                            }
                        } else {
                            let option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No jobs recorded under the selected company';
                            jobField.appendChild(option);
                        }

                        // ✅ Capabilities
                        let capabilitiesField = $('#capabilities');
                        capabilitiesField.empty();

                        if (data.capabilities && data.capabilities.length > 0) {
                            data.capabilities.forEach(c => {
                                capabilitiesField.append(new Option(c.name, c.id));
                            });
                        } else {
                            capabilitiesField.append(new Option('No capabilities found', 'no-capability'));
                        }

                        setTimeout(() => {
                            capabilitiesField.trigger('change.select2');
                        }, 200);

                        // ✅ Specialties
                        let specialtiesField = $('#specialties');
                        specialtiesField.empty();

                        if (data.specialties && data.specialties.length > 0) {
                            data.specialties.forEach(s => {
                                specialtiesField.append(new Option(s.name, s.id));
                            });
                        } else {
                            specialtiesField.append(new Option('No specialties found', 'no-specialty'));
                        }

                        setTimeout(() => {
                            specialtiesField.trigger('change.select2');
                        }, 200);
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
        });
    </script>



    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Options",
                allowClear: true
            });
        });
    </script>

    <script>
        function toggleStatus(checkbox) {
            if (checkbox.checked) {
                document.getElementById("hidden_status").value = "draft";
            } else {
                document.getElementById("hidden_status").value = "active";
            }
        }
    </script>
@endsection
