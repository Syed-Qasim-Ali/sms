<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #28a745;
            --dark-green: #1e7e34;
            --light-green: #d4edda;
            --primary-black: #0a0a0a;
            --light-gray: #f8f9fa;
        }

        body {
            background-color: var(--light-gray);
            padding: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-container {
            max-width: 1000px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .invoice-header {
            background-color: var(--primary-black);
            color: #fff;
            padding: 25px 30px;
            position: relative;
        }

        .invoice-header h2 {
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
        }

        .invoice-header p {
            margin-bottom: 0;
            opacity: 0.9;
            line-height: 1.5;
        }

        .invoice-number {
            color: var(--primary-green) !important;
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 0;
        }

        .invoice-body {
            background-color: #fff;
            padding: 35px 30px;
            border-bottom: 3px solid var(--primary-green);
        }

        .section-title {
            color: var(--primary-black);
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid var(--light-green);
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: var(--primary-black);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 12px;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .table tr:nth-child(even) {
            background-color: var(--light-gray);
        }

        .balance-box {
            background-color: var(--primary-green);
            color: white;
            padding: 15px;
            text-align: right;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: 700;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .due-date {
            text-align: right;
            margin-top: 15px;
            font-weight: bold;
            color: var(--primary-black);
        }

        .contact-info {
            background-color: var(--light-green);
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .contact-info a {
            color: var(--dark-green);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .company-details {
            border-left: 4px solid var(--primary-green);
            padding-left: 15px;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div class="company-details">
                <h2>1023 Logistics LLC</h2>
                <p>12172 S IL Rte 47, Suite 210<br>Huntley, IL 60142<br>Phone: 727.300.6607<br>sales@1023logistics.com
                </p>
            </div>
            <div class="text-end">
                <h1 class="invoice-number">Invoice #{{ $invoice->invoice_number }}</h1>
            </div>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">

            <!-- Bill To / Order Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="section-title">Bill To</h5>
                    <p>{{ $orders->end_location }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="section-title">Order Details</h5>
                    <p><strong>Order #:</strong> {{ $orders->order_number }}<br>
                        <strong>Job:</strong> {{ $job->name }}<br>
                        <strong>Job #:</strong> {{ $job->id }}<br>
                        <strong>Ordered On:</strong> {{ $orders->created_at->format('d-m-Y') }}
                    </p>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
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
                                <td>${{ $orders->pay_rate }}
                                    {{ ucwords(str_replace('_', ' ', $orders->pay_rate_type)) }}</td>
                                <td>{{ $orders->quantity }}</td>
                                <td>{{ $ticket->tolls ?? 'N/A' }}</td>
                                <td>{{ $ticket->adjusted_minutes }}</td>
                                <td>{{ $ticket->adjusted_minutes_reason ?? 'N/A' }}</td>
                                <td>${{ $orders->quantity * $orders->pay_rate }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Section -->
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="contact-info">
                        <p><strong>Please make checks payable to 1023 Logistics LLC</strong><br>
                            If you have any questions concerning this invoice, <br>
                            contact Terry at 727.300.6607 or <a
                                href="mailto:sales@1023logistics.com">sales@1023logistics.com</a><br>
                            Thank you for your business!</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="balance-box">
                        Balance Due: ${{ $orders->quantity * $orders->pay_rate }}
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
