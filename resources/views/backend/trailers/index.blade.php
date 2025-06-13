@extends('backend.layout.app')
@section('title', 'Trailer Management')
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
                                        <li class="breadcrumb-item active">Trailers</li>
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
                                                <h2>Trailers Management</h2>
                                                @can('trucks-create')
                                                    <a class="btn btn-success btn-sm mb-2"
                                                        href="{{ route('trailers.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Add Trailer
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

                                            <table id="TrailersTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th><b>N</b>o</th>
                                                        <th><b>T</b>railer <b>N</b>o.</th>
                                                        <th><b>T</b>ruck <b>N</b>o.</th>
                                                        <th><b>R</b>ate <b>M</b>odifier</th>
                                                        <th><b>S</b>tatus</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($trucks as $truck)
                                                        @foreach ($truck->trailers as $trailer)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $trailer->trailer_number }}</td>
                                                                <td>{{ $trailer->truck_id }}</td>
                                                                <td>{{ $trailer->rate_modifier }}</td>
                                                                <td>
                                                                    <label
                                                                        class="badge bg-{{ $trailer->status === 'inactive' ? 'warning' : 'success' }}">
                                                                        {{ ucfirst($trailer->status) }}
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    @can('trucks-edit')
                                                                        <a class="btn btn-primary btn-sm mb-2"
                                                                            href="{{ route('trailers.edit', $trailer->id) }}"
                                                                            title="Edit">
                                                                            <i class="fa-solid fa-pen-to-square"></i>

                                                                        </a>
                                                                    @endcan

                                                                    @can('trucks-delete')
                                                                        <form id="delete-form-{{ $trailer->id }}"
                                                                            method="POST"
                                                                            action="{{ route('trailers.destroy', $trailer->id) }}"
                                                                            style="display:inline">
                                                                            @csrf
                                                                            @method('DELETE')

                                                                            <button type="button"
                                                                                onclick="confirmDelete({{ $trailer->id }})"
                                                                                class="btn btn-danger btn-sm mb-2"
                                                                                title="Delete">
                                                                                <i class="fa-solid fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
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
            $('#TrailersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });

        function confirmDelete(trailerId) {
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
                    document.getElementById('delete-form-' + trailerId).submit();
                }
            });
        }
    </script>
@endsection
