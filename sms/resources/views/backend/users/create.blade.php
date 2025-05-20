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
                                        <li class="breadcrumb-item">User</li>
                                        <li class="breadcrumb-item active">Invite</li>
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
                                                <h2>Invite New User</h2>
                                                <a class="btn btn-success btn-sm mb-2" href="{{ route('users.index') }}">
                                                    <i class="fa-solid fa-arrow-left"></i> Back
                                                </a>
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

                                            <form method="POST" action="{{ route('users.store') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>First Name:</strong>
                                                            <input type="text" name="name" value="{{ old('name') }}"
                                                                placeholder="First Name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>Last Name:</strong>
                                                            <input type="text" name="lname" value="{{ old('lname') }}"
                                                                placeholder="Last Name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>Email:</strong>
                                                            <input type="email" name="email" value="{{ old('email') }}"
                                                                placeholder="Email" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>Phone Number:</strong>
                                                            <input type="text" name="phone_num"
                                                                value="{{ old('phone_num') }}" placeholder="Phone Number"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group position-relative">
                                                            <strong>Password:</strong>
                                                            <div class="input-group">
                                                                <input type="password" id="password" name="password"
                                                                    placeholder="Password" class="form-control" required>
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="togglePassword('password', 'eyeIcon1')">
                                                                    <i id="eyeIcon1" class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group position-relative">
                                                            <strong>Confirm Password:</strong>
                                                            <div class="input-group">
                                                                <input type="password" id="password_confirmation"
                                                                    name="password_confirmation"
                                                                    placeholder="Confirm Password" class="form-control"
                                                                    required>
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                                                                    <i id="eyeIcon2" class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="roles"
                                                                class="form-label"><strong>Role:</strong></label>
                                                            <select name="roles[]" id="roles" class="form-select"
                                                                required>
                                                                @foreach ($roles as $value => $label)
                                                                    <option value="{{ $value }}"
                                                                        {{ in_array($value, old('roles', [])) ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="status"
                                                                class="form-label"><strong>Status:</strong></label>
                                                            <select name="status" id="status" class="form-select"
                                                                required>
                                                                <option value="active"
                                                                    {{ old('status') == 'active' ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="pending"
                                                                    {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                                    Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="invited" value="1" required>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i
                                                                class="fa-solid fa-floppy-disk"></i>
                                                            Submit</button>
                                                    </div>
                                                </div>
                                            </form>
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
        function togglePassword(inputId, eyeIconId) {
            let passwordField = document.getElementById(inputId);
            let eyeIcon = document.getElementById(eyeIconId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
