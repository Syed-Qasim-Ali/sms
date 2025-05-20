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
                                        <li class="breadcrumb-item active">Show</li>
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
                                                <h2>Show Order</h2>
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

                                            <div id="fullscreenLoader"
                                                style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
     background-color: rgba(255, 255, 255, 0.8); z-index: 9999; align-items: center; justify-content: center;">
                                                <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>

                                            <div class="container mt-4">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <!-- Map Section -->
                                                        <div class="card p-3">
                                                            <div id="map"
                                                                style="height: 300px; width: 100%; border-radius: 10px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <!-- Order Details Section -->
                                                        <div class="card p-3">
                                                            <h5><a href="#">{{ $order->order_number }}</a></h5>
                                                            <input type="hidden" id="orderNumberInput"
                                                                value="{{ $order->order_number }}">
                                                            <p><strong><i class="fas fa-calendar-alt"></i> Date:</strong>
                                                                {{ $order->created_at }} </p>
                                                            <p><strong><i class="fas fa-cube"></i> Material:</strong>
                                                                {{ $order->material }} </p>
                                                            <p><strong><i class="fas fa-map-marker-alt"></i>
                                                                    Pickup:</strong> {{ $order->start_location }} </p>
                                                            @foreach ($order->ordersitecontact as $sites)
                                                                <p><strong><i class="fas fa-phone-alt"></i> Test
                                                                        Contact:</strong> {{ $sites->site_contact }}</p>
                                                            @endforeach
                                                            <p class="mt-2"><strong>Pending Truck Assignment</strong></p>
                                                            <p><strong><i class="fas fa-dollar-sign"></i> Rate:</strong>
                                                                ${{ $order->pay_rate }} /
                                                                {{ ucfirst($order->pay_rate_type) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach ($lastTimeSlots as $timeSlot)
                                                <div class="row mt-4">
                                                    <!-- Timeblock Section -->
                                                    <div class="col-md-6">
                                                        <div class="card p-3">
                                                            <h6>Timeblocks</h6>
                                                            <div id="order-status-wrapper">
                                                                <div
                                                                    class="d-flex justify-content-between bg-dark text-white p-2">
                                                                    <span>{{ \Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }}
                                                                        CDT</span>
                                                                    <span>
                                                                        <span id="accepted-count-{{ $timeSlot->id }}">
                                                                            {{ $timeSlot->tickets->where('status', 'open')->count() }}
                                                                        </span> ACCEPTED |
                                                                        <span id="pending-count-{{ $timeSlot->id }}">
                                                                            {{ $timeSlot->tickets->where('status', 'pending')->count() }}
                                                                        </span> PENDING
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="assigned-order card p-3"
                                                            id="time-slot-{{ $timeSlot->id }}"
                                                            data-time-slot-id="{{ $timeSlot->id }}">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Order
                                                                    #{{ $order->order_number }}
                                                                </h5>
                                                                <p>Truck assigned: <span class="assigned-truck">None</span>
                                                                </p>
                                                                <p>Required Trucks: <span
                                                                        class="required-truck">{{ $timeSlot->truck_quantity }}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            @endforeach

                                            <!-- Available Trucks Section -->
                                            <div class="col-md-3">
                                                <div class="card p-3">
                                                    <h6>Available Trucks</h6>
                                                    {{-- @foreach ($trucks as $truck)
                                                        <div class="draggable-item btn btn-outline-secondary w-100 my-1"
                                                            data-truck-id="{{ $truck->id }}" id="selectedTruckCard">
                                                            Unit #{{ $truck->truck_number }} -
                                                            {{ ucfirst($truck->truck_type) }}
                                                        </div>
                                                    @endforeach --}}
                                                    @foreach ($trucks as $truck)
                                                        <div class="draggable-item btn btn-outline-secondary w-100 my-1"
                                                            data-truck-id="{{ $truck->id }}" id="selectedTruckCard">
                                                            Unit #{{ $truck->truck_number }} -
                                                            {{ ucfirst($truck->truck_type) }}
                                                            @if (in_array($truck->id, $backloggedTrucks))
                                                                <!-- Check if the truck is in backlog -->
                                                                <span class="badge bg-warning">In Backlog</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
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
        function inviteTruck(truckId, timeSlotId, buttonRef = null) {
            toastr.info('Trying to send invitation...');
            let orderNumber = document.getElementById("orderNumberInput").value;

            if (!truckId) {
                toastr.error("❌ No truck selected!");
                return;
            }
            if (!orderNumber) {
                toastr.error("❌ Order number is required!");
                return;
            }

            // Show loader
            document.getElementById("fullscreenLoader").style.display = "flex";

            fetch('/trucks/invite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        truck_id: truckId,
                        order_number: orderNumber,
                        time_slot_id: timeSlotId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("📡 API Response:", data);
                    if (data.success) {
                        toastr.success('✅ Invitation sent successfully!');
                    } else {
                        toastr.error('❌ ' + data.error);
                    }

                    if (buttonRef) {
                        const container = buttonRef.closest('.assigned-order');
                        container.querySelector('.assigned-truck').textContent = 'None';
                        buttonRef.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('❌ An error occurred while sending the invitation.');
                })
                .finally(() => {
                    // Hide loader
                    document.getElementById("fullscreenLoader").style.display = "none";
                });
        }
    </script>

    {{-- <script>
        // Function to call when button is clicked
        function assignTruckFromBacklog() {
            console.log('Checking Backlog');
            toastr.info('Assigning truck from backlog...');

            fetch('/assign-from-backlog', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data);
                    if (data.message) {
                        toastr.success(data.message);
                    } else {
                        toastr.error('❌ Something went wrong!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('❌ Error assigning truck.');
                });
        }

        // Trigger the function every 5 seconds (5000ms)
        setInterval(assignTruckFromBacklog, 5000);
    </script> --}}

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHuiMaFjSnFTQfRmAfTp9nZ9VpTICgNrc&callback=initMap" async
        defer></script>
    <script>
        window.initMap = function() {
            const lat = parseFloat({{ $order->start_location_lat ?? 0 }});
            const lng = parseFloat({{ $order->start_location_lng ?? 0 }});
            const pickupAddress = @json($order->start_location ?? 'No address found');

            const startLocation = {
                lat: lat,
                lng: lng
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                center: startLocation,
                zoom: 14,
            });

            const marker = new google.maps.Marker({
                position: startLocation,
                map: map,
                title: "Pickup Location: " + pickupAddress,
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<strong>Pickup Location:</strong><br>${pickupAddress}`
            });

            infoWindow.open(map, marker);

            marker.addListener("click", function() {
                infoWindow.open(map, marker);
            });
        };
    </script>

    <script>
        $(document).ready(function() {
            // Initialize draggable on all available truck buttons (draggable-item)
            $(".draggable-item").draggable({
                helper: function() {
                    var helper = $(this).clone();
                    helper.removeClass('w-100'); // Remove 'w-100' class from the helper
                    return helper[0];
                },
                cursorAt: {
                    left: 100,
                    top: 50
                },
                revert: "invalid",
                delay: 150,
                zIndex: 10000,
                appendTo: "body",
                start: function(event, ui) {
                    // Optionally, remove 'w-100' class from the original element
                    $(this).removeClass('w-100');
                    console.log("Dragging: ", $(this).text().trim());
                },
                stop: function(event, ui) {
                    // Optionally, add 'w-100' class back to the original element
                    $(this).addClass('w-100');
                }
            });
            $(".assigned-order").droppable({
                accept: ".draggable-item", // Accept only elements with class draggable-item
                hoverClass: "drop-hover", // Optional CSS class for visual feedback on hover.
                drop: function(event, ui) {
                    // Get the text and truck id of the dragged truck
                    let truckText = ui.helper.text()
                        .trim(); // Use ui.helper to get the text of the cloned element
                    let truckId = ui.helper.data("truck-id");
                    console.log("Truck Dropped: " + truckText + " with ID: " + truckId);


                    let timeSlotId = $(this).data("time-slot-id");
                    console.log("Time Slot ID: " + timeSlotId);

                    // Set the assigned truck text inside the assigned-order container.
                    // Find the assigned-truck span within this drop target.
                    $(this).find(".assigned-truck").text(truckText);

                    // Create and append the "Invite" button inside the assigned-order container
                    var inviteButton = $('<button>', {
                        text: 'Invite',
                        class: 'btn btn-primary invite-btn',
                        click: function() {
                            // Define the action to be performed when the button is clicked
                            inviteTruck(truckId, timeSlotId, $(this));
                            // You can add further actions here, such as opening a modal or sending an invitation
                        }
                    });
                    $(this).append(inviteButton);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function refreshOrderStatusSection() {
                console.log("🔄 Refreshing accepted & pending counts...");
                $("#order-status-wrapper").load(window.location.pathname + " #order-status-wrapper>*", "");
            }

            // Refresh every 5 seconds
            setInterval(refreshOrderStatusSection, 3000);
        });
    </script>

    {{-- <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        var PUSHER_APP_KEY = "{{ $pusherAppKey }}";
        var PUSHER_APP_CLUSTER = "{{ $pusherAppCluster }}";
    </script>

    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher(PUSHER_APP_KEY, {
            cluster: PUSHER_APP_CLUSTER,
            encrypted: true
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script> --}}
@endsection
