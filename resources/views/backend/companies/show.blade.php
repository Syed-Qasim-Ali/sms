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
                                        <li class="breadcrumb-item">Companies</li>
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
                                                <h2>Companies Management</h2>
                                                <a class="btn btn-success btn-sm mb-2"
                                                    href="{{ route('companies.index') }}">
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
                                            <div class="p-4 border rounded shadow-sm bg-white">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Company Name:</strong>
                                                        {{ $company->company_name }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Email:</strong>
                                                        {{ $company->email }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Company Capabilities:</strong>
                                                        {{ $company->company_capabilities }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Company Specialties:</strong>
                                                        {{ $company->company_specialties }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Phone No:</strong>
                                                        {{ $company->phone }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Address:</strong>
                                                        {{ $company->address }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Website:</strong>
                                                        {{ $company->website }}
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <strong>Status:</strong>
                                                        <label
                                                            class="badge bg-{{ $company->status === 'inactive' ? 'warning' : 'success' }}">
                                                            {{ ucfirst($company->status) }}
                                                        </label>
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
