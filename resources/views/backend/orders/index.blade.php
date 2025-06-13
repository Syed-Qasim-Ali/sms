@extends('backend.layout.app')
@section('title', 'Orders Management')
@section('content')
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
                                                <h2>Orders Management</h2>
                                                @can('orders-create')
                                                    <a class="btn btn-success btn-sm mb-2" href="{{ route('orders.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Add New Order
                                                    </a>
                                                @endcan
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
                                            <table id="ordersTable" class="table table-striped" style="width: 100%">

                                                <thead>
                                                    <tr>
                                                        <th>S.N</th>
                                                        <th>Order Number</th>
                                                        <th>Date Of Work</th>
                                                        <th>Company</th>
                                                        <th>Status</th>
                                                        <th>Driver Order Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $key => $order)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            @if ($order->status === 'active')
                                                                <td><a
                                                                        href="{{ route('orders.show', $order->id) }}">{{ $order->order_number }}</a>
                                                                </td>
                                                            @else
                                                                <td>{{ $order->order_number }}</td>
                                                            @endif
                                                            <td>{{ $order->date }}</td>
                                                            <td>{{ $order->company->company_name ?? 'N/A' }}</td>
                                                            <td>
                                                                <form
                                                                    action="{{ route('orders.toggleStatus', $order->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="badge bg-{{ $order->status === 'draft' ? 'warning' : 'success' }}"
                                                                        style="border:none; background:none; cursor: pointer;">
                                                                        {{ ucfirst($order->status) }}
                                                                    </button>
                                                                </form>
                                                            </td>
                                                            <td>
                                                                @foreach ($order->tickets as $detailstatus)
                                                                    @php
                                                                        $statusClass = match ($detailstatus->status) {
                                                                            'accepted' => 'bg-success',
                                                                            'rejected' => 'bg-danger',
                                                                            'pending' => 'bg-warning',
                                                                            'under_review' => 'bg-info',
                                                                            'closed' => 'bg-secondary',
                                                                            'open' => 'bg-primary',
                                                                            default => 'bg-light text-dark',
                                                                        };
                                                                    @endphp

                                                                    <label class="badge {{ $statusClass }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $detailstatus->status)) }}
                                                                    </label>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @can('orders-edit')
                                                                    <a class="btn btn-primary btn-sm mb-2"
                                                                        href="{{ route('orders.edit', $order->id) }}"><i
                                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                                @endcan
                                                                {{-- @can('orders-edit') --}}
                                                                {{-- <a class="btn btn-primary btn-sm mb-2"
                                                                    href="{{ route('orders-view', $order->order_number) }}">
                                                                    <i class="fa-solid fa-eye"></i></a> --}}
                                                                {{-- @endcan --}}
                                                                @can('orders-delete')
                                                                    <form method="POST"
                                                                        action="{{ route('orders.destroy', $order->id) }}"
                                                                        id="delete-form-{{ $order->id }}"
                                                                        style="display:inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mb-2"
                                                                            onclick="confirmDelete({{ $order->id }})">
                                                                            <i class="fa-solid fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="alert alert-warning">
                                                <strong>Note:</strong> <small>To change the status of an order, simply click
                                                    on the status badge..</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="foot">
                <p>Copyright © 2024 1023Sms. All Rights Reserved.</p>
            </div>
        </section>

    </div>
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
        function confirmDelete(orderId) {
            if (!orderId) {
                console.error("Order ID is missing!");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('delete-form-' + orderId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error("Delete form not found for order ID:", orderId);
                    }
                }
            });
        }
    </script>


@endsection
