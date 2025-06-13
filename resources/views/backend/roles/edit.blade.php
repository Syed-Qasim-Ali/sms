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
                                        <li class="breadcrumb-item">Roles</li>
                                        <li class="breadcrumb-item active">Edit</li>
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
                                                <h2>Edit Roles</h2>
                                                <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.index') }}">
                                                    <i class="fa-solid fa-arrow-left"></i> Back
                                                </a>
                                            </div>
                                            @session('success')
                                                <div class="alert alert-success" role="alert">
                                                    {{ $value }}
                                                </div>
                                            @endsession

                                            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>Name:</strong>
                                                            <input type="text" name="name" placeholder="Name"
                                                                class="form-control" value="{{ $role->name }}">
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <strong>Permission:</strong>
                                                            <br />
                                                            @foreach ($permission as $value)
                                                                <label><input type="checkbox"
                                                                        name="permission[{{ $value->id }}]"
                                                                        value="{{ $value->id }}" class="name"
                                                                        {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                                                    {{ $value->name }}</label>
                                                                <br />
                                                            @endforeach
                                                        </div>
                                                    </div> --}}
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <strong>Permissions:</strong>
                                                                <br />
                                                                @foreach ($permission->slice(0, ceil($permission->count() / 2)) as $value)
                                                                    <label>
                                                                        <input type="checkbox" name="permission[{{ $value->id }}]" value="{{ $value->id }}" class="name"
                                                                            {{ isset($rolePermissions) && is_array($rolePermissions) && in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                                                        {{ $value->name }}
                                                                    </label>
                                                                    <br />
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{-- <strong>Permissions (Column 2):</strong> --}}
                                                                <br />
                                                                @foreach ($permission->slice(ceil($permission->count() / 2)) as $value)
                                                                    <label>
                                                                        <input type="checkbox" name="permission[{{ $value->id }}]" value="{{ $value->id }}" class="name"
                                                                            {{ isset($rolePermissions) && is_array($rolePermissions) && in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                                                        {{ $value->name }}
                                                                    </label>
                                                                    <br />
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                        <button type="submit" class="btn btn-primary btn-sm mb-3"><i
                                                                class="fa-solid fa-floppy-disk"></i> Submit</button>
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
@endsection
