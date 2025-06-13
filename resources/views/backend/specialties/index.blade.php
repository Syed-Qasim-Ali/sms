@extends('backend.layout.app')
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
                                        <li class="breadcrumb-item active">Specialties</li>
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
                                                <h2>Specialties Management</h2>
                                                @can('specialties-create')
                                                    <a class="btn btn-success btn-sm mb-2"
                                                        href="{{ route('specialties.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Add New Specialty
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
                                            <table id="specialtiesTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>S.N</th>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($specialties as $specialty)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $specialty->name }}</td>
                                                            <td>
                                                                <label
                                                                    class="badge bg-{{ $specialty->status === 'inactive' ? 'warning' : 'success' }}">
                                                                    {{ ucfirst($specialty->status) }}
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-info btn-sm mb-2"
                                                                    href="{{ route('specialties.show', $specialty->id) }}"
                                                                    title="Show"><i class="fa-solid fa-list"></i></a>

                                                                @can('specialties-edit')
                                                                    <a class="btn btn-primary btn-sm mb-2"
                                                                        href="{{ route('specialties.edit', $specialty->id) }}"
                                                                        title="Edit"><i
                                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                                @endcan
                                                                @can('specialties-delete')
                                                                    <form id="delete-form-{{ $specialty->id }}" method="POST"
                                                                        action="{{ route('specialties.destroy', $specialty->id) }}"
                                                                        style="display:inline">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mb-2"
                                                                            onclick="confirmDelete(event, {{ $specialty->id }})"
                                                                            title="Delete">
                                                                            <i class="fa-solid fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endcan
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
            $('#specialtiesTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
        function confirmDelete(event, specialtyId) {
            event.preventDefault(); // Prevent the form from submitting immediately

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
                    document.getElementById('delete-form-' + specialtyId).submit();
                }
            });
        }
    </script>
@endsection
