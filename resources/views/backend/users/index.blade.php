@extends('backend.layout.app')
@section('title', 'Users Management')
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
                                        <li class="breadcrumb-item active">Users</li>
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
                                                <h2>Users Management</h2>
                                                @can('users-create')
                                                    <a class="btn btn-success btn-sm mb-2" href="{{ route('users.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Invite New User
                                                    </a>
                                                @endcan
                                            </div>
                                            @session('success')
                                                <div class="alert alert-success" role="alert">
                                                    {{ $value }}
                                                </div>
                                            @endsession


                                            <table id="usersTable" class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th><b>N</b>o</th>
                                                        <th><b>N</b>ame</th>
                                                        <th><b>E</b>mail</th>
                                                        <th><b>R</b>ole</th>
                                                        <th><b>S</b>tatus</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data as $key => $user)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>
                                                                @if (!empty($user->getRoleNames()))
                                                                    @foreach ($user->getRoleNames() as $v)
                                                                        <label
                                                                            class="badge bg-success">{{ ucfirst($v) }}</label>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <label
                                                                    class="badge bg-{{ $user->status === 'pending' ? 'warning' : 'success' }}"
                                                                    id="status-{{ $user->id }}"
                                                                    onclick="toggleStatus({{ $user->id }})">
                                                                    {{ ucfirst($user->status) }}
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-info btn-sm mb-2"
                                                                    href="{{ route('users.show', $user->id) }}"
                                                                    title="Show"><i class="fa-solid fa-list"></i>
                                                                </a>
                                                                @can('users-edit')
                                                                    <a class="btn btn-primary btn-sm mb-2"
                                                                        href="{{ route('users.edit', $user->id) }}"
                                                                        title="Edit"><i
                                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                                @endcan
                                                                @if (auth()->user()->hasRole('super-admin'))
                                                                    <a href="{{ route('impersonate.start', $user->id) }}"
                                                                        class="btn btn-success btn-sm mb-2"
                                                                        title="Impersonate">
                                                                        <i class="fas fa-user-secret"></i>
                                                                    </a>
                                                                @endif
                                                                @can('users-delete')
                                                                    <form method="POST"
                                                                        action="{{ route('users.destroy', $user->id) }}"
                                                                        style="display:inline"
                                                                        id="delete-user-form-{{ $user->id }}">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm mb-2" title="Delete"
                                                                            onclick="confirmUserDelete({{ $user->id }})">
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
                                                <strong>Note:</strong> <small>If you want to change a user's status, you
                                                    need to click on their status.</small>
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
            $('#usersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
            });
        });
    </script>
    <script>
        function confirmUserDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-user-form-' + userId).submit();
                }
            });
        }
    </script>
    <script>
        function toggleStatus(userId) {
            let statusLabel = document.getElementById('status-' + userId);

            // SweetAlert2 Confirm Box
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to change the status of this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Change it!",
                cancelButtonText: "No, Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // User clicked "Yes"
                    fetch(`/users/${userId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update label text and color
                                statusLabel.textContent = data.new_status.charAt(0).toUpperCase() + data
                                    .new_status.slice(1);
                                statusLabel.className = "badge bg-" + (data.new_status === 'pending' ?
                                    'warning' : 'success');

                                // Show Toastr Notification
                                toastr.success("Status successfully changed to " + data.new_status);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error("Status change failed! Please try again.");
                        });
                } else {
                    // User clicked "Cancel"
                    toastr.info("Status change cancelled");
                }
            });
        }
    </script>
@endsection
