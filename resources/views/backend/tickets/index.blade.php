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
                                        <li class="breadcrumb-item active">Tickets</li>
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
                                                <h2>Tickets Management</h2>
                                                <a href="" {{-- <a href="{{ url('invoices/' . $orderno) }}" --}} class="btn btn-success btn-sm"><i
                                                        class="fas fa-file-invoice"></i>
                                                    Generate
                                                    Invoice</a>
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

                                            <table class="table datatable" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        {{-- <th><input type="checkbox" id="select-all"></th> --}}
                                                        <th>S.N</th>
                                                        <th>Order Number</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tickets as $ticket)
                                                        <tr>
                                                            {{-- <td>
                                                                <input type="checkbox" class="ticket-checkbox"
                                                                    name="selected_orders[]"
                                                                    value="{{ $ticket->order_number }}">
                                                            </td> --}}
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $ticket->order_number }}</td>
                                                            <td>{{ $ticket->status }}</td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ route('tickets.show', $ticket->uuid) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-eye"></i> Show
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            {{-- @if() --}}
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ route('tickets.show', $ticket->uuid) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-eye"></i> Show
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.ticket-checkbox');
            const invoiceBtn = document.getElementById('invoice-btn');

            function toggleInvoiceButton() {
                const anyChecked = document.querySelectorAll('.ticket-checkbox:checked').length > 0;
                invoiceBtn.style.display = anyChecked ? 'inline-block' : 'none';
            }

            // Select all logic
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleInvoiceButton(); // <-- this is important
            });

            // Individual checkbox logic
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', toggleInvoiceButton);
            });
        });
    </script> --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.ticket-checkbox');
            const invoiceBtn = document.getElementById('invoice-btn');

            function toggleInvoiceButton() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                invoiceBtn.style.display = anyChecked ? 'inline-block' : 'none';
            }

            checkboxes.forEach(cb => cb.addEventListener('change', toggleInvoiceButton));
        });
    </script> --}}
@endsection
