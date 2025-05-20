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
                                        <li class="breadcrumb-item active">Jobs</li>
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
                                                <h2>Jobs Management</h2>
                                                @can('jobs-create')
                                                    <a class="btn btn-success btn-sm mb-2" href="{{ route('jobs.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Create New Job
                                                    </a>
                                                @endcan
                                            </div>
                                            @session('success')
                                                <div class="alert alert-success" role="alert">
                                                    {{ $value }}
                                                </div>
                                            @endsession

                                            <table id="jobsTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th><b>N</b>o</th>
                                                        <th><b>N</b>ame</th>
                                                        <th><b>D</b>escription</th>
                                                        <th><b>I</b>mage</th>
                                                        <th><b>A</b>ction</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i = 0; @endphp
                                                    @foreach ($jobs as $key => $job)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $job->name }}</td>
                                                            <td>{{ $job->description }}</td>
                                                            <td><img src="{{ asset('storage/' . $job->image) }}"
                                                                    alt="Job Image" width="100"></td>
                                                            <td>
                                                                <a class="btn btn-info btn-sm mb-2"
                                                                    href="{{ route('jobs.show', $job->id) }}"
                                                                    title="Show"><i class="fa-solid fa-list"></i>
                                                                </a>
                                                                @can('jobs-edit')
                                                                    <a class="btn btn-primary btn-sm mb-2"
                                                                        href="{{ route('jobs.edit', $job->id) }}"
                                                                        title="Edit"><i
                                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                                @endcan
                                                                @can('jobs-delete')
                                                                    <form method="POST"
                                                                        action="{{ route('jobs.destroy', $job->id) }}"
                                                                        id="delete-form-{{ $job->id }}"
                                                                        style="display:inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mb-2"
                                                                            onclick="confirmDelete({{ $job->id }})"
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
            $('#jobsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
        function confirmDelete(jobId) {
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
                    document.getElementById('delete-form-' + jobId).submit();
                }
            });
        }
    </script>
@endsection
