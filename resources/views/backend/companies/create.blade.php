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
                                        <li class="breadcrumb-item">Companies</li>
                                        <li class="breadcrumb-item active">Create</li>
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
                                                <h2>Add New Company</h2>
                                                <a class="btn btn-success btn-sm mb-2"
                                                    href="{{ route('companies.index') }}">
                                                    <i class="fa-solid fa-arrow-left"></i> Back
                                                </a>
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

                                            <form action="{{ route('companies.store') }}" method="POST"
                                                class="p-4 border rounded shadow-sm bg-white">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Company Name</label>
                                                    <input type="text" name="company_name" id="name"
                                                        class="form-control" placeholder="Enter Company Name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Company Email</label>
                                                    <input type="email" name="email" id="email" class="form-control"
                                                        placeholder="Enter Company Email" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="company_capabilities" class="form-label">Company
                                                        Capabilities</label>
                                                    <input type="text" name="company_capabilities"
                                                        id="company_capabilities" class="form-control"
                                                        placeholder="Enter Company Capabilities">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="company_specialties" class="form-label">Company
                                                        Specialties</label>
                                                    <input type="text" name="company_specialties"
                                                        id="company_specialties" class="form-control"
                                                        placeholder="Enter Truck Specialties">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone Number</label>
                                                    <input type="tel" name="phone" id="phone" class="form-control"
                                                        placeholder="Enter Phone Number">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" name="address" id="address" class="form-control"
                                                        placeholder="Enter Address">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="website" class="form-label">Website URL</label>
                                                    <input type="url" name="website" id="website" class="form-control"
                                                        placeholder="Enter Website URL">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select">
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fa-solid fa-save"></i> Save
                                                </button>
                                            </form>

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
@endsection
