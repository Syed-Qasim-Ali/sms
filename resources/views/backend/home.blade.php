@extends('backend.layout.app')
@section('content')
    <style>
        .notification-icon {
            position: relative;
            display: inline-block;
        }

        .order-count-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 5px 8px;
            border-radius: 50%;
            transform: translate(50%, -50%);
        }
    </style>

    <div class="main">
        <section class="main-sec">
            <div class="seperater">
                <button id="openbar"><i class="fa-solid fa-bars"></i></button>
                <div class="main-head">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-6">
                            <div class="pagetitle">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                                        <li class="breadcrumb-item">Tables</li>
                                        <li class="breadcrumb-item active">Orders</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="sm-header">
                                <a href="#" class="notification-icon">
                                    <img src="{{ asset('Backend/assets/images/Group 38.png') }}" alt="">
                                    <span id="order-count" class="order-count-badge">{{ $pendingOrders ?? 0 }}</span>
                                </a>
                                <a href="#"><img src="{{ asset('Backend/assets/images/image 239.png') }}"
                                        alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="usermanagesec">
                    <div class="row align-items-center">
                        <div class="col-md-11">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h2>Order To Assign</h2>
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
                                    <table class="table datatable" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Number</th>
                                                <th>Date Of Work</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($message))
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="alert alert-warning text-center">{{ $message }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif

                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('d M Y , h:i A') }}</td>
                                                    <td>
                                                        <!-- Accept Button -->
                                                        <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#acceptModal{{ $order->uuid }}">
                                                            <img src="{{ asset('Backend/assets/images/accept.png') }}"
                                                                alt="" width="20px">
                                                        </button>

                                                        <!-- Reject Button -->
                                                        <form action="{{ route('orders.reject', $order->uuid) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm"><img
                                                                    src="{{ asset('Backend/assets/images/delete.png') }}"
                                                                    alt="" width="20px"></button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- ✅ Accept Confirmation Modal (inside the loop) -->
                                                <div class="modal fade" id="acceptModal{{ $order->uuid }}" tabindex="-1"
                                                    aria-labelledby="acceptModalLabel{{ $order->uuid }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title"
                                                                    id="acceptModalLabel{{ $order->uuid }}">Confirm
                                                                    Acceptance</h5>
                                                                <button type="button" class="btn-close text-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('orders.accept', $order->uuid) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label for="truckSelect{{ $order->uuid }}"
                                                                            class="form-label">Select Truck</label>
                                                                        <select name="truck_id"
                                                                            id="truckSelect{{ $order->uuid }}"
                                                                            class="form-select truck-dropdown"
                                                                            data-order="{{ $order->uuid }}">
                                                                            <option value="">-- Select Truck --
                                                                            </option>
                                                                            @foreach ($trucks as $truck)
                                                                                <option value="{{ $truck->id }}">
                                                                                    {{ $truck->truck_number }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3 trailer-section"
                                                                        id="trailerSection{{ $order->uuid }}"
                                                                        style="display: none;">
                                                                        <label class="form-label">Select Trailer</label>
                                                                        <select name="trailer_id"
                                                                            id="trailerSelect{{ $order->uuid }}"
                                                                            class="form-select">
                                                                            <!-- options will be loaded dynamically -->
                                                                        </select>
                                                                    </div>
                                                                    <input type="hidden" value="Start" name="status">

                                                            </div>
                                                            <div class="modal-footer">

                                                                <button type="submit" class="btn btn-success">Yes,
                                                                    Accept</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <div class="foot">
            <p>Copyright © 2024 1023Sms. All Rights Reserved.</p>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('.truck-dropdown').change(function() {
                var truckId = $(this).val();
                var orderId = $(this).data('order');

                if (truckId) {
                    $.ajax({
                        url: '/trucks/' + truckId + '/trailers',
                        method: 'GET',
                        success: function(data) {
                            let trailerSelect = $('#trailerSelect' + orderId);
                            trailerSelect.empty(); // clear old options

                            if (data.length > 0) {
                                trailerSelect.append(
                                    '<option value="">-- Select Trailer --</option>');
                                data.forEach(function(trailer) {
                                    trailerSelect.append('<option value="' + trailer
                                        .id + '">' + trailer.trailer_number +
                                        '</option>');
                                });
                                $('#trailerSection' + orderId).show();
                            } else {
                                trailerSelect.append(
                                    '<option value="">No trailers found</option>');
                                $('#trailerSection' + orderId).show();
                            }
                        },
                        error: function() {
                            alert('Could not fetch trailers');
                        }
                    });
                } else {
                    $('#trailerSection' + orderId).hide();
                }
            });
        });
    </script>

    <script>
        function updateOrderCount() {
            console.log("Fetching updated order count..."); // ✅ Debugging log

            $.ajax({
                url: "", // 👈 Current page ko AJAX request bhej raha hai
                type: "GET",
                success: function(response) {
                    console.log("AJAX request successful!"); // ✅ Debugging log

                    let parser = new DOMParser();
                    let doc = parser.parseFromString(response, 'text/html');
                    let newCount = doc.getElementById('order-count').innerText;

                    console.log("New Order Count Fetched:", newCount); // ✅ Debugging log

                    document.getElementById('order-count').innerText = newCount;
                },
                error: function(error) {
                    console.error("Error fetching order count:", error);
                }
            });
        }
        setInterval(updateOrderCount, 3000);
    </script>
@endsection
