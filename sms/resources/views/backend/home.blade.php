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
                                        <li class="breadcrumb-item active">Orders</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                                <th>Contractor</th>
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

                                            @php $i = 1; @endphp
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->company->company_name ?? 'N/A' }}</td>
                                                    <td>{{ $order->date }}</td>
                                                    <td>
                                                        <!-- Accept Button -->
                                                        <form action="{{ route('orders.accept', $order->uuid) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm"><img
                                                                    src="{{ asset('Backend/assets/images/accept.png') }}"
                                                                    alt="" width="20px"></button>
                                                        </form>

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

        // Auto-refresh order count every 1 second
        setInterval(updateOrderCount, 3000);
    </script>
    {{-- <script>
        $(document).ready(function() {
            console.log("Script Loaded!"); // ✅ Check if script is running

            // Make sure dropdown works
            $("#searchDropdown").on("click", function(event) {
                event.preventDefault();
                console.log("Dropdown Clicked!"); // ✅ Check if click works
                $(this).next(".dropdown-menu").toggleClass("show");
            });

            // Close dropdown when clicking outside
            $(document).on("click", function(event) {
                if (!$(event.target).closest(".nav-item.dropdown").length) {
                    $(".dropdown-menu").removeClass("show");
                }
            });
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            console.log("✅ Script Loaded!");

            // 🟢 Ensure dropdown toggles correctly
            $("#searchDropdown").on("click", function(event) {
                event.preventDefault();
                console.log("🔔 Dropdown Clicked!");
                $(this).next(".dropdown-menu").toggleClass("show");
            });

            // 🟢 Auto-close when clicking outside
            $(document).on("click", function(event) {
                if (!$(event.target).closest(".nav-item.dropdown").length) {
                    $(".dropdown-menu").removeClass("show");
                }
            });

            // 🔄 Fetch notifications every 5 seconds
            function fetchNotifications() {
                $.ajax({
                    url: '/admin/notifications',
                    method: 'GET',
                    success: function(notifications) {
                        console.log("📩 Notifications received:", notifications);
                        const notificationList = $('#notifications');
                        notificationList.empty();

                        if (notifications.length > 0) {
                            notifications.forEach(notification => {
                                notificationList.append(
                                    `<li class="list-group-item">${notification.message}</li>`
                                );
                            });
                        } else {
                            notificationList.append(
                                '<li class="list-group-item text-center">No new notifications</li>');
                        }
                    },
                    error: function(error) {
                        console.error("❌ Error fetching notifications:", error);
                    }
                });
            }

            function fetchNotificationsCount() {
                $.ajax({
                    url: '/admin/notifications-count',
                    method: 'GET',
                    success: function(count) {
                        console.log("🔢 Notification Count:", count);
                        const notificationBadge = $('#notifications-count');
                        notificationBadge.text(count > 0 ? count : '');
                    },
                    error: function(error) {
                        console.error("❌ Error fetching notification count:", error);
                    }
                });
            }

            // 🔄 Refresh notification count every 5 seconds
            setInterval(fetchNotificationsCount, 5000);
        });
    </script> --}}
@endsection
