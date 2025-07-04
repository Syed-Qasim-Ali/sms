@extends('backend.layout.app')
@section('title', 'Companies Management')
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
                                        <li class="breadcrumb-item active">Companies</li>
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
                                                <h2>Companies Management</h2>
                                                @can('company-create')
                                                    <a class="btn btn-success btn-sm mb-2"
                                                        href="{{ route('companies.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Add Company
                                                    </a>
                                                @endcan
                                            </div>

                                            @if (session('success'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

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

                                            <table id="companiesTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th><b>N</b>o</th>
                                                        <th><b>C</b>ompany <b>N</b>ame</th>
                                                        <th><b>E</b>mail</th>
                                                        <th><b>W</b>ebsite</th>
                                                        <th><b>S</b>tatus</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($companies->count() > 0)
                                                        @foreach ($companies as $key => $company)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $company->company_name }}</td>
                                                                <td>{{ $company->email }}</td>
                                                                <td>{{ $company->website }}</td>
                                                                <td>
                                                                    <label
                                                                        class="badge bg-{{ $company->status === 'inactive' ? 'warning' : 'success' }}">
                                                                        {{ Str::ucfirst($company->status) }}
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-info btn-sm mb-2"
                                                                        href="{{ route('companies.show', $company->id) }}"
                                                                        title="Show">
                                                                        <i class="fa-solid fa-list"></i>
                                                                    </a>

                                                                    @can('company-edit')
                                                                        <a class="btn btn-primary btn-sm mb-2"
                                                                            href="{{ route('companies.edit', $company->id) }}"
                                                                            title="Edit">
                                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                                        </a>
                                                                    @endcan

                                                                    @can('company-delete')
                                                                        <form id="delete-form-{{ $company->id }}"
                                                                            method="POST"
                                                                            action="{{ route('companies.destroy', $company->id) }}"
                                                                            style="display:inline">
                                                                            @csrf
                                                                            @method('DELETE')

                                                                            <button type="button"
                                                                                onclick="confirmDelete({{ $company->id }})"
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
            </div>
            <div class="foot">
                <p>Copyright © 2024 1023Sms. All Rights Reserved.</p>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            $('#companiesTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
        function confirmDelete(companyId) {
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
                    document.getElementById('delete-form-' + companyId).submit();
                }
            });
        }
    </script>
@endsection
