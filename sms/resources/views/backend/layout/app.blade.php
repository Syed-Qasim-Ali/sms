<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="{{ asset('Backend/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Start Map CDN --}}
    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine"></script>

    <!-- Leaflet Geocoder for Autocomplete -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    {{-- End Map CDN --}}

    <!-- jQuery -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <title>Dashboard</title>
    <style>
        @media (max-width: 768px) {
            .sidebar-wrapper {
                position: fixed;
                left: -250px;
                width: 250px;
                height: 100%;
                background: #343a40;
                transition: left 0.3s ease-in-out;
                z-index: 1000;
            }

            .sidebar-wrapper.active {
                left: 0;
            }

            .toggle-btn {
                position: fixed;
                left: 10px;
                top: 10px;
                background: #343a40;
                color: white;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
                z-index: 1100;
            }

            .main-sec {
                margin-left: 0 !important;
                width: 100%;
                transition: margin-left 0.3s ease-in-out;
            }
        }

        .suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            width: 46%;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000;
        }

        .suggestion-item {
            padding: 5px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background: #f0f0f0;
        }

        .card {
            max-width: 100%;
            overflow-x: hidden;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            /* display: flex;
            flex-direction: column; */
        }

        .container-fluid {
            height: 100%;
            display: flex;
            flex-direction: row;
        }

        .order-details,
        .map-container {
            height: 100%;
            overflow-y: auto;
        }

        .main-sec {
            flex-grow: 1;
            /* Content ko expand karne dega */
        }

        .foot {
            position: relative;
            width: 100%;
            text-align: center;
            padding: 10px;
            /* background: #f8f9fa; */
        }

        /* Some basic styling */
        .draggable-item {
            width: 120px;
            height: 40px;
            background: #3490dc;
            color: white;
            /* line-height: -20px; */
            text-align: center;
            border-radius: 5px;
            cursor: move;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="row align-items-start">
        <div class="col-12 col-md-2 sidebar-col">
            <div class="sidebar-wrapper">
                <div class="sidebar" id="sidebar">
                    <div class="sidebar-logo">
                        <img src="{{ asset('Backend/assets/images/logo-white.png') }}" alt="">
                    </div>
                    <ul>
                        <div class="First_sec">
                            <li class="{{ Request::is('home*') ? 'active' : '' }}">
                                <a href="{{ route('home') }}" class="fsli d-flex align-items-center w-100">
                                    <img src="{{ asset('Backend/assets/images/dashboard.png') }}" alt="">
                                    <span class="ms-2">Dashboard</span>
                                </a>
                            </li>

                            <li class="{{ Request::is('tickets*') ? 'active' : '' }}">
                                <a href="{{ route('tickets.index') }}" class="fsli"><img
                                        src="{{ asset('Backend/assets/images/ticket.png') }}" alt="">
                                    Tickets</a>
                            </li>

                            {{-- @can('invoice')
                                <li class="{{ Request::is('invoices*') ? 'active' : '' }}">
                                    <a href="#" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/invoice.png') }}" alt="">
                                        Invoices</a>
                                </li>
                            @endcan --}}

                            @can('capabilities-list')
                                <li class="{{ Request::is('capabilities*') ? 'active' : '' }}">
                                    <a href="{{ route('capabilities.index') }}" class="fsli"><img
                                            src=" {{ asset('Backend/assets/images/capability.png') }}" alt="">
                                        Capabilities</a>
                                </li>
                            @endcan

                            @can('company-list')
                                <li class="{{ Request::is('companies*') ? 'active' : '' }}">
                                    <a href="{{ route('companies.index') }}" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/companies.png') }}" alt="">
                                        Companies</a>
                                </li>
                            @endcan

                            @can('specialties-list')
                                <li class="{{ Request::is('specialties*') ? 'active' : '' }}">
                                    <a href="{{ route('specialties.index') }}" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/specialities.png') }}" alt="">
                                        Specialities</a>
                                </li>
                            @endcan

                            @can('trucks-list')
                                <li class="{{ Request::is('trucks*') ? 'active' : '' }}">
                                    <a href="{{ route('trucks.index') }}" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/truck.png') }}" alt="">
                                        Trucks</a>
                                </li>
                            @endcan

                            @can('jobs-list')
                                <li class="{{ Request::is('jobs*') ? 'active' : '' }}">
                                    <a href="{{ route('jobs.index') }}" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/job.png') }}" alt="">
                                        Jobs</a>
                                </li>
                            @endcan

                            @can('orders-list')
                                <li class="{{ Request::is('orders*') ? 'active' : '' }}">
                                    <a href="{{ route('orders.index') }}" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/order.png') }}" alt="">
                                        Orders</a>
                                </li>
                            @endcan

                            @can('reports')
                                <li class="{{ Request::is('reports*') ? 'active' : '' }}">
                                    <a href="#" class="fsli"><img
                                            src="{{ asset('Backend/assets/images/report.png') }}" alt="">
                                        Reports</a>
                                </li>
                            @endcan

                            @can('users-list')
                                <li class="{{ Request::is('users*') ? 'active' : '' }}"><a
                                        href="{{ route('users.index') }}" class="fsli">
                                        <img src="{{ asset('Backend/assets/images/user.png') }}" alt="">
                                        Users</a>
                                </li>
                            @endcan

                            @can('roles-list')
                                <li class="{{ Request::is('roles*') ? 'active' : '' }}"><a
                                        href="{{ route('roles.index') }}" class="fsli">
                                        <img src="{{ asset('Backend/assets/images/roles.png') }}" alt="">
                                        Roles</a>
                                </li>
                            @endcan


                        </div>
                    </ul>
                    <div class="settings">
                        <h4>SYSTEM</h4>
                        <a href="#"><img src="{{ asset('Backend/assets/images/i.png') }}" alt="">Help
                            Center</a>
                        <a href="#"><img src="{{ asset('Backend/assets/images/setting.png') }}"
                                alt="">Settings</a>

                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img src="{{ asset('Backend/assets/images/log-out.png') }}" alt="">Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>

                        @if (session()->has('impersonated_by'))
                            <a href="{{ route('impersonate.stop') }}" class="btn btn-sm btn-danger">
                                Back to Admin
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
    integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    const sidebar = document.getElementById('sidebar');
    const indicator = document.getElementById('indicator');
    const menuItems = document.querySelectorAll('.sidebar ul li');

    // Move the indicator line on hover
    menuItems.forEach((item, index) => {
        item.addEventListener('mouseover', () => {
            const itemHeight = item.offsetHeight;
            const offsetTop = item.offsetTop; // Match the height of the current item
        });
    });
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    });
</script>
<script>
    document.querySelectorAll('li').forEach(li => {
        li.addEventListener('click', function() {
            const link = this.querySelector('a');
            if (link) {
                window.location.href = link.href;
            }
        });
    });
</script>
<script>
    function toggleSidebar() {
        let sidebar = document.querySelector('.sidebar-wrapper');
        let mainSec = document.querySelector('.main-sec');

        sidebar.classList.toggle('active');

        // Adjust content area when sidebar is open
        if (sidebar.classList.contains('active')) {
            mainSec.style.marginLeft = '250px';
        } else {
            mainSec.style.marginLeft = '0';
        }
    }
</script>

</html>
