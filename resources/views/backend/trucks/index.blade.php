@extends('backend.layout.app')
@section('title', 'Trucks Management')
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
                                        <li class="breadcrumb-item active">Trucks</li>
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
                                                <h2>Trucks Management</h2>
                                                @can('trucks-create')
                                                    <a class="btn btn-success btn-sm mb-2" href="{{ route('trucks.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Add Truck
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
                                            <table id="trucksTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th><b>N</b>o</th>
                                                        <th><b>T</b>ruck <b>N</b>o.</th>
                                                        <th><b>R</b>egistration <b>N</b>o.</th>
                                                        <th><b>M</b>odel</th>
                                                        <th><b>Y</b>ear</th>
                                                        <th><b>S</b>tatus</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($trucks->count() > 0)
                                                        @foreach ($trucks as $key => $truck)
                                                            <tr>
                                                                <td>{{ ++$i }}</td>
                                                                <td>{{ $truck->truck_number }}</td>
                                                                <td>{{ $truck->registration_number }}</td>
                                                                <td>{{ $truck->model }}</td>
                                                                <td>{{ $truck->year }}</td>
                                                                <td>
                                                                    <label
                                                                        class="badge bg-{{ $truck->status === 'inactive' ? 'warning' : 'success' }}">
                                                                        {{ ucfirst($truck->status) }}
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-info btn-sm mb-2"
                                                                        href="{{ route('trucks.show', $truck->id) }}"
                                                                        title="Show"><i class="fa-solid fa-list"></i>
                                                                    </a>

                                                                    @can('trucks-edit')
                                                                        <a class="btn btn-primary btn-sm mb-2"
                                                                            href="{{ route('trucks.edit', $truck->id) }}"
                                                                            title="Edit">
                                                                            <i class="fa-solid fa-pen-to-square"></i>

                                                                        </a>
                                                                    @endcan

                                                                    @can('trucks-delete')
                                                                        <form id="delete-form-{{ $truck->id }}"
                                                                            method="POST"
                                                                            action="{{ route('trucks.destroy', $truck->id) }}"
                                                                            style="display:inline">
                                                                            @csrf
                                                                            @method('DELETE')

                                                                            <button type="button"
                                                                                onclick="confirmDelete({{ $truck->id }})"
                                                                                class="btn btn-danger btn-sm mb-2"
                                                                                title="Delete">
                                                                                <i class="fa-solid fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
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
            $('#trucksTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });

        function confirmDelete(truckId) {
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
                    document.getElementById('delete-form-' + truckId).submit();
                }
            });
        }
    </script>
@endsection
