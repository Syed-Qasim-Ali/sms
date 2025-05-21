@extends('backend.layout.app')
<style>
    #loading-spinner {
        margin-left: 10px;
    }
</style>
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
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
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
                                                <button id="invoice-btn" class="btn btn-success btn-sm"
                                                    style="display: none" onclick="generateInvoice()">
                                                    <i class="fas fa-file-invoice"></i> Generate Invoice
                                                    <span id="loading-spinner"
                                                        class="spinner-border spinner-border-sm text-light"
                                                        style="display: none;" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </span>

                                                </button>
                                                {{-- <a id="invoice-btn" href="#" class="btn btn-success btn-sm"
                                                    style="display: none" onclick="generateInvoice()">
                                                    <i class="fas fa-file-invoice"></i> Generate Invoice
                                                    <span id="loading-spinner"
                                                        class="spinner-border spinner-border-sm text-light"
                                                        style="display: none;" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </span>
                                                </a> --}}

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
                                                        <th><input type="checkbox" id="select-all"></th>
                                                        <th>S.N</th>
                                                        <th>Order Number</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tickets as $ticket)
                                                        <tr>
                                                            <td>
                                                                @if ($ticket->status == 'closed' && $ticket->invoice_id)
                                                                    <input type="checkbox" class="ticket-checkbox"
                                                                        name="selected_orders[]"
                                                                        value="{{ $ticket->uuid }}">
                                                                @endif
                                                            </td>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $ticket->order_number }}</td>
                                                            <td>{{ $ticket->status }}</td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ route('tickets.show', $ticket->uuid) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-eye"></i> Show
                                                                    </a>
                                                                    @if ($ticket->status == 'admin_review')
                                                                        <a href="{{ route('tickets.edit', $ticket->uuid) }}"
                                                                            class="btn btn-primary btn-sm">
                                                                            <i class="fas fa-pen"></i> Edit
                                                                        </a>
                                                                    @endif
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

    <script>
        let isGenerating = false; // flag to indicate invoice generation is in progress

        function toggleInvoiceButton() {
            const anyChecked = document.querySelectorAll('.ticket-checkbox:checked').length > 0;
            invoiceBtn.style.display = anyChecked ? 'inline-block' : 'none';
            // Only enable/disable button if not currently generating an invoice
            if (!isGenerating) {
                invoiceBtn.disabled = !anyChecked;
            }
        }
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.ticket-checkbox');
        const invoiceBtn = document.getElementById('invoice-btn');
        document.addEventListener('DOMContentLoaded', function() {


            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleInvoiceButton();
            });
            toggleInvoiceButton();

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', toggleInvoiceButton);
            });
        });

        // Function to send the selected ticket UUIDs to the controller and generate invoice
        function generateInvoice() {
            let selectedTickets = [];
            document.querySelectorAll('.ticket-checkbox:checked').forEach((checkbox) => {
                selectedTickets.push(checkbox.value);
            });

            if (selectedTickets.length > 0) {
                isGenerating = true; // disable invoice button until process completes

                // Show loading spinner and disable the button
                document.getElementById('loading-spinner').style.display = 'inline-block';
                document.getElementById('invoice-btn').disabled = true;

                // Send the selected tickets' UUIDs to the controller
                fetch("{{ route('tickets.generate-invoice') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            tickets: selectedTickets
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading spinner and enable the button accordingly
                        document.getElementById('loading-spinner').style.display = 'none';
                        document.getElementById('invoice-btn').disabled = false;
                        isGenerating = false;
                        // Call toggleInvoiceButton to re-enable the button based on checkbox state
                        toggleInvoiceButton();
                        console.log('data', data);

                        if (data.success) {
                            window.location.href = data.invoice_url; // Redirect to invoice page
                        } else {
                            alert('Something went wrong!');
                        }
                    })
                    .catch(error => {
                        document.getElementById('invoice-btn').disabled = false;

                        // Hide loading spinner and enable the button in case of error
                        document.getElementById('loading-spinner').style.display = 'none';
                        isGenerating = false;
                        toggleInvoiceButton();
                        console.error('Error:', error);
                        alert('An error occurred while generating the invoice. Please try again.');
                    });
            } else {
                alert('Please select at least one ticket');
            }
        }
    </script>
@endsection
