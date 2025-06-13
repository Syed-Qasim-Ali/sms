@extends('backend.layout.app')
@section('title', 'Roles Management')
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
                                        <li class="breadcrumb-item active">Roles</li>
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
                                                <h2>Roles Management</h2>
                                                @can('roles-create')
                                                    <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Create New Role
                                                    </a>
                                                @endcan
                                            </div>
                                            @session('success')
                                                <div class="alert alert-success" role="alert">
                                                    {{ $value }}
                                                </div>
                                            @endsession

                                            <table id="rolesTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($roles as $key => $user)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>
                                                                <a class="btn btn-info btn-sm mb-2"
                                                                    href="{{ route('roles.show', $user->id) }}"
                                                                    title="Show"><i class="fa-solid fa-list"></i>
                                                                </a>
                                                                @can('roles-edit')
                                                                    <a class="btn btn-primary btn-sm mb-2"
                                                                        href="{{ route('roles.edit', $user->id) }}"
                                                                        title="Edit"><i
                                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                                @endcan
                                                                @can('roles-delete')
                                                                    <form method="POST"
                                                                        action="{{ route('roles.destroy', $user->id) }}"
                                                                        style="display:inline"
                                                                        id="delete-form-{{ $user->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mb-2" title="Delete"
                                                                            onclick="confirmDelete({{ $user->id }})">
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
        </section>
    </div>
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
        function confirmDelete(userId) {
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
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>
@endsection
