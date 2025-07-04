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
                                        <li class="breadcrumb-item active">Trailers</li>
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
                                                <h2>Trailers Management</h2>
                                                @can('trucks-create')
                                                    <a class="btn btn-success btn-sm mb-2"
                                                        href="{{ route('trailers.create') }}">
                                                        <i class="fa-solid fa-plus"></i> Add Trailer
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

                                            <form action="{{ route('trailers.update', $trailer->id) }}" method="POST"
                                                class="p-4 border rounded shadow-sm bg-white">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="trailer_number" class="form-label">Trailer Number</label>
                                                    <input type="text" name="trailer_number" id="trailer_number"
                                                        class="form-control" placeholder="Enter Trailer Number" required
                                                        value="{{ old('trailer_number', $trailer->trailer_number) }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="truck_id" class="form-label">Assign to Truck</label>
                                                    <select name="truck_id" id="truck_id" class="form-control" required>
                                                        <option value="">Select Truck</option>
                                                        @foreach ($trucks as $truck)
                                                            <option value="{{ $truck->id }}"
                                                                {{ $trailer->truck_id == $truck->id ? 'selected' : '' }}>
                                                                {{ $truck->truck_number }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="rate_modifier" class="form-label">Rate Modifier</label>
                                                    <input type="number" name="rate_modifier" id="rate_modifier"
                                                        class="form-control" placeholder="e.g., 10 for +10%"
                                                        value="{{ old('rate_modifier', $trailer->rate_modifier) }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select">
                                                        <option value="active"
                                                            {{ $trailer->status == 'active' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="inactive"
                                                            {{ $trailer->status == 'inactive' ? 'selected' : '' }}>Inactive
                                                        </option>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fa-solid fa-save"></i> Update Trailer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- usermanagesec -->
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
