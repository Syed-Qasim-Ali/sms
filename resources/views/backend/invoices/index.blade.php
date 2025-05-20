<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 30px;
        }

        .invoice-header {
            background-color: #0a0a0a;
            color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        .invoice-header img {
            height: 50px;
        }

        .invoice-body {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .balance-box {
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-align: right;
            border-radius: 5px;
        }

        .due-date {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h2>1023 Logistics LLC</h2>
                <p>12172 S IL Rte 47, Suite 210<br>Huntley, IL 60142<br>Phone: 727.300.6607<br>sales@1023logistics.com
                </p>
            </div>
            <div class="text-end">
                <h1 class="text-success">Invoice #{{ $orders->order_number }}</h1>
            </div>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">

            <!-- Bill To / Order Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Bill To</h5>
                    <p>{{ $orders->end_location }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Order Details</h5>
                    <p>Order #: {{ $orders->order_number }}<br>Job: {{ $job->name }}<br>Job #:
                        {{ $job->id }}<br>Ordered On: {{ $orders->created_at->format('d-m-Y') }}</p>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket #</th>
                            <th>Date</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Tolls</th>
                            <th>Adjustment (Min)</th>
                            <th>Reason</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders->tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->uuid }}</td>
                                <td>{{ $ticket->created_at->format('d-m-Y') }}</td>
                                <td>${{ $orders->pay_rate }} / {{ $orders->pay_rate_type }}</td>
                                <td>{{ $orders->quantity }}</td>
                                <td>N/A</td>
                                <td>0.0</td>
                                <td>Travel Time</td>
                                <td>${{ $orders->quantity * $orders->pay_rate }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <p><strong>Please make checks payable to 1023 Logistics LLC</strong><br>
                        If you have any questions concerning this invoice, <br>
                        contact Terry at 727.300.6607 or <a
                            href="mailto:sales@1023logistics.com">sales@1023logistics.com</a><br>
                        Thank you for your business!</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="balance-box">
                        Balance Due: {{ $orders->quantity * $orders->pay_rate }}
                    </div>
                    <?php
                    use Carbon\Carbon;
                    ?>
                    <div class="due-date">
                        Due Date: {{ Carbon::now()->format('l, F j, Y') }}
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
