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

                                            <form method="POST" action="{{ route('trucks.update', $truck->id) }}"
                                                enctype="multipart/form-data" class="p-4 border rounded shadow-sm bg-white">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="truck_number" class="form-label">Truck Number</label>
                                                    <input type="text" name="truck_number" id="truck_number"
                                                        class="form-control" placeholder="Enter Truck Number"
                                                        value="{{ $truck->truck_number }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="truck_type" class="form-label">Truck Type</label>
                                                    <select name="truck_type" id="truck_type" class="form-select" required>
                                                        <option value="" disabled
                                                            {{ old('truck_type', $truck->truck_type) ? '' : 'selected' }}>
                                                            Select Truck Type</option>
                                                        <option value="pickup"
                                                            {{ old('truck_type', $truck->truck_type) == 'pickup' ? 'selected' : '' }}>
                                                            Pickup</option>
                                                        <option value="trailer"
                                                            {{ old('truck_type', $truck->truck_type) == 'trailer' ? 'selected' : '' }}>
                                                            Trailer</option>
                                                        <option value="tanker"
                                                            {{ old('truck_type', $truck->truck_type) == 'tanker' ? 'selected' : '' }}>
                                                            Tanker</option>
                                                        <option value="flatbed"
                                                            {{ old('truck_type', $truck->truck_type) == 'flatbed' ? 'selected' : '' }}>
                                                            Flatbed</option>
                                                    </select>

                                                </div>

                                                <div class="mb-3">
                                                    <label for="registration_number" class="form-label">Registration
                                                        Number</label>
                                                    <input type="text" name="registration_number"
                                                        id="registration_number" class="form-control"
                                                        placeholder="Enter Registration Number"
                                                        value="{{ $truck->registration_number }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="model" class="form-label">Truck Model</label>
                                                    <input type="text" name="model" id="model" class="form-control"
                                                        placeholder="Enter Model" value="{{ $truck->model }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="brand" class="form-label">Brand</label>
                                                    <input type="text" name="brand" id="brand" class="form-control"
                                                        placeholder="Enter Brand (e.g., Volvo)"
                                                        value="{{ $truck->brand }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="year" class="form-label">Manufacturing Year</label>
                                                    <input type="number" name="year" id="year" class="form-control"
                                                        placeholder="Enter Year" min="1980" max="2025"
                                                        value="{{ $truck->year }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="capacity" class="form-label">Capacity (in Tons)</label>
                                                    <input type="number" name="capacity" id="capacity"
                                                        class="form-control" placeholder="Enter Capacity"
                                                        value="{{ $truck->capacity }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="fuel_type" class="form-label">Fuel Type</label>
                                                    <select name="fuel_type" id="fuel_type" class="form-select">
                                                        <option value="">Select Fuel Type</option>
                                                        <option value="diesel"
                                                            {{ $truck->fuel_type == 'diesel' ? 'selected' : '' }}>Diesel
                                                        </option>
                                                        <option value="petrol"
                                                            {{ $truck->fuel_type == 'petrol' ? 'selected' : '' }}>Petrol
                                                        </option>
                                                        <option value="electric"
                                                            {{ $truck->fuel_type == 'electric' ? 'selected' : '' }}>
                                                            Electric</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="driver_name" class="form-label">Driver Name</label>
                                                    <input type="text" name="driver_name" id="driver_name"
                                                        class="form-control" placeholder="Enter Owner Name"
                                                        value="{{ $truck->driver_name }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="driver_contact" class="form-label">Driver Contact</label>
                                                    <input type="text" name="driver_contact" id="driver_contact"
                                                        class="form-control" placeholder="Enter Contact Number"
                                                        value="{{ $truck->driver_contact }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select" required>
                                                        <option value="" disabled
                                                            {{ old('status', $truck->status) ? '' : 'selected' }}>Select
                                                            Status</option>
                                                        <option value="active"
                                                            {{ old('status', $truck->status) == 'active' ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="inactive"
                                                            {{ old('status', $truck->status) == 'inactive' ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="documents" class="form-label">Uploaded Documents</label>
                                                    @if ($truck->documents)
                                                        <ul class="list-group">
                                                            @foreach (json_decode($truck->documents) as $doc)
                                                                <li class="list-group-item">
                                                                    <a href="{{ asset('storage/' . $doc) }}"
                                                                        target="_blank" class="text-primary">
                                                                        View Document
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p>No Documents Available</p>
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <label for="documents" class="form-label">Upload New Documents</label>
                                                    <input type="file" name="documents[]" id="documents"
                                                        class="form-control" multiple>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Truck Image</label>
                                                    @if ($truck->image)
                                                        <img src="{{ asset('storage/' . $truck->image) }}"
                                                            alt="Truck Image" class="img-fluid rounded"
                                                            style="max-width: 100%;">
                                                    @else
                                                        <p>No Image Available</p>
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Upload New Image</label>
                                                    <input type="file" name="image" id="image"
                                                        class="form-control">
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fa-solid fa-save"></i> Update Truck
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
