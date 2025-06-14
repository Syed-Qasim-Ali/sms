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
                                        <li class="breadcrumb-item">Tickets</li>
                                        <li class="breadcrumb-item active">Update</li>
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
                                                <h2>Tickets Update</h2>

                                                <a class="btn btn-success btn-sm mb-2" href="{{ route('tickets.index') }}">
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
                                            <form action="{{ route('tickets.update', $ticket->uuid) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <!-- Pickup Time -->
                                                <div class="mb-3">
                                                    <label for="pickup_time" class="form-label">Pickup Time</label>
                                                    <input type="datetime-local" name="pickup_time" id="pickup_time"
                                                        class="form-control" required
                                                        value="{{ old('pickup_time', \Carbon\Carbon::parse($ticket->eventpickdrop->first()->pickup_time)->format('Y-m-d\TH:i')) }}">
                                                </div>

                                                <!-- Drop Time -->
                                                <div class="mb-3">
                                                    <label for="drop_time" class="form-label">Drop Time</label>
                                                    <input type="datetime-local" name="drop_time" id="drop_time"
                                                        class="form-control" required
                                                        value="{{ old('drop_time', \Carbon\Carbon::parse($ticket->eventpickdrop->first()->drop_time)->format('Y-m-d\TH:i')) }}">
                                                </div>

                                                <!-- Adjustment -->
                                                <div class="mb-3">
                                                    <label for="adjusted_minutes" class="form-label">Adjustment
                                                        (Min)</label>
                                                    <input type="number" name="adjusted_minutes" id="adjusted_minutes"
                                                        class="form-control" placeholder="Enter Adjustment"
                                                        value="{{ old('adjusted_minutes', $ticket->adjusted_minutes) }}">
                                                </div>

                                                <!-- Adjustment Minutes Reason -->
                                                <div class="mb-3">
                                                    <label for="adjusted_minutes_reason" class="form-label">Adjustment
                                                        Reason</label>
                                                    <input type="text" name="adjusted_minutes_reason"
                                                        id="adjusted_minutes_reason" class="form-control"
                                                        placeholder="Enter Adjustment Reason"
                                                        value="{{ old('adjusted_minutes_reason', $ticket->adjusted_minutes_reason) }}">
                                                </div>

                                                <!-- Tolls -->
                                                <div class="mb-3">
                                                    <label for="tolls" class="form-label">Toll Amount</label>
                                                    <input type="number" name="tolls" id="tolls" class="form-control"
                                                        placeholder="Enter Toll Amount"
                                                        value="{{ old('tolls', $ticket->tolls) }}">
                                                </div>

                                                <!-- Upload Images -->
                                                <div class="mb-3">
                                                    <label for="images" class="form-label">Upload Images</label>
                                                    <input type="file" name="images[]" id="images"
                                                        class="form-control" multiple accept="image/*">
                                                </div>

                                                <button type="submit" class="btn btn-primary">Submit</button>

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
