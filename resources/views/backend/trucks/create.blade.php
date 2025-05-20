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
                                        <li class="breadcrumb-item">Truck</li>
                                        <li class="breadcrumb-item active">Create</li>
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
                                                <h2>Add New Truck</h2>
                                                <a class="btn btn-success btn-sm mb-2" href="{{ route('trucks.index') }}">
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

                                            <form action="{{ route('trucks.store') }}" method="POST"
                                                enctype="multipart/form-data" class="p-4 border rounded shadow-sm bg-white">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="company_name" class="form-label">Company Name</label>
                                                    <select class="form-control" id="company_id" name="company_id" required>
                                                        <option value="">Select a Company</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->id }}">{{ $company->company_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="truck_number" class="form-label">Truck Number</label>
                                                    <input type="text" name="truck_number" id="truck_number"
                                                        class="form-control" placeholder="Enter Truck Number" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="truck_type" class="form-label">Truck Type</label>
                                                    <select name="truck_type" id="truck_type" class="form-select" required>
                                                        <option value="">Select Type</option>
                                                        <option value="pickup">Pickup</option>
                                                        <option value="trailer">Trailer</option>
                                                        <option value="tanker">Tanker</option>
                                                        <option value="flatbed">Flatbed</option>
                                                        <option value="flatbed">Other</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="registration_number" class="form-label">Registration
                                                        Number</label>
                                                    <input type="text" name="registration_number"
                                                        id="registration_number" class="form-control"
                                                        placeholder="Enter Registration Number" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="model" class="form-label">Truck Model</label>
                                                    <input type="text" name="model" id="model" class="form-control"
                                                        placeholder="Enter Model">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="truck_capabilities" class="form-label">Truck
                                                        Capabilities</label>
                                                    <input type="text" name="truck_capabilities" id="truck_capabilities"
                                                        class="form-control" placeholder="Enter Truck Capabilities">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="truck_specialties" class="form-label">Truck
                                                        Specialties</label>
                                                    <input type="text" name="truck_specialties" id="truck_specialties"
                                                        class="form-control" placeholder="Enter Truck Specialties">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="brand" class="form-label">Brand</label>
                                                    <input type="text" name="brand" id="brand"
                                                        class="form-control" placeholder="Enter Brand (e.g., Volvo)">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="year" class="form-label">Manufacturing Year</label>
                                                    <input type="number" name="year" id="year"
                                                        class="form-control" placeholder="Enter Year" min="1980"
                                                        max="2025">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="capacity" class="form-label">Capacity (in Tons)</label>
                                                    <input type="number" name="capacity" id="capacity"
                                                        class="form-control" placeholder="Enter Capacity">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="fuel_type" class="form-label">Fuel Type</label>
                                                    <select name="fuel_type" id="fuel_type" class="form-select">
                                                        <option value="">Select Fuel Type</option>
                                                        <option value="diesel">Diesel</option>
                                                        <option value="petrol">Petrol</option>
                                                        <option value="electric">Electric</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="hourly_rate" class="form-label">Hourly Rate</label>
                                                    <input type="number" name="hourly_rate" id="hourly_rate"
                                                        class="form-control" placeholder="Enter Hourly Rate (e.g., 150)">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="driver_name" class="form-label">Driver Name</label>
                                                    <input type="text" name="driver_name" id="driver_name"
                                                        class="form-control" placeholder="Enter Owner Name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="driver_contact" class="form-label">Driver Contact</label>
                                                    <input type="tel" name="driver_contact" id="driver_contact"
                                                        class="form-control" placeholder="Enter Contact Number">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select">
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="documents" class="form-label">Upload Documents</label>
                                                    <input type="file" name="documents[]" id="documents"
                                                        class="form-control" multiple>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Truck Image</label>
                                                    <input type="file" name="image" id="image"
                                                        class="form-control">
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fa-solid fa-save"></i> Save Truck
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
        </section>
        {{-- <div class="foot">
            <p>Copyright © 2024 1023Sms. All Rights Reserved.</p>
        </div> --}}
    </div>
@endsection
