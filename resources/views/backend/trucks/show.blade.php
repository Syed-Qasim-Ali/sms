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
                                        <li class="breadcrumb-item active">Show</li>
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
                                                <h2>Trucks Management</h2>
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
                                            <div class="row">
                                                <!-- Left Side: Truck Details -->
                                                <div class="col-md-8">
                                                    <div class="p-4 border rounded shadow-sm bg-white">
                                                        <div class="mb-3">
                                                            <strong><b>T</b>ruck <b>N</b>o:</strong>
                                                            {{ $truck->truck_number }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>T</b>ruck <b>T</b>ype:</strong>
                                                            {{ $truck->truck_type }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>R</b>egistration <b>N</b>o:</strong>
                                                            {{ $truck->registration_number }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>M</b>odel:</strong> {{ $truck->model }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>C</b>ompany <b>C</b>apabilities:</strong>
                                                            {{ $truck->truck_capabilities }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>C</b>ompany <b>S</b>pecialties:</strong>
                                                            {{ $truck->truck_specialties }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>B</b>rand:</strong> {{ $truck->brand }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>Y</b>ear:</strong> {{ $truck->year }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>C</b>apacity:</strong> {{ $truck->capacity }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>F</b>uel <b>T</b>ype:</strong>
                                                            {{ $truck->fuel_type }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>H</b>ourly <b>R</b>ate:</strong>
                                                            {{ $truck->hourly_rate }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>D</b>river <b>N</b>ame:</strong>
                                                            {{ $truck->driver_name }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong><b>D</b>river <b>C</b>ontact:</strong>
                                                            {{ $truck->driver_contact }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>Status:</strong>
                                                            <label
                                                                class="badge bg-{{ $truck->status === 'inactive' ? 'warning' : 'success' }}">
                                                                {{ ucfirst($truck->status) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Side: Image & Documents -->
                                                <div class="col-md-4">
                                                    <div class="p-4 border rounded shadow-sm bg-white">
                                                        <!-- Truck Image -->
                                                        <div class="mb-3 text-center">
                                                            <strong>Truck Image:</strong>
                                                            @if ($truck->image)
                                                                <img src="{{ asset('storage/' . $truck->image) }}"
                                                                    alt="Truck Image" class="img-fluid rounded"
                                                                    style="max-width: 100%;">
                                                            @else
                                                                <p>No Image Available</p>
                                                            @endif
                                                        </div>

                                                        <!-- Truck Documents -->
                                                        <div class="mb-3">
                                                            <strong>Documents:</strong>
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
                                                    </div>
                                                </div>
                                            </div>
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
