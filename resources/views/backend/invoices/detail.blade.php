<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header-box {
            background-color: #1b1f22;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            font-size: 14px;
            gap: 20px;
        }

        .header-box .section {
            flex: 1 1 30%;
            min-width: 200px;
        }

        .header-box .left h2 {
            margin-top: 0;
            color: #00cc00;
        }

        .header-box .section p {
            margin: 4px 0;
        }

        .section.center p {
            text-align: center;
        }

        .section.right p {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 20px;
        }

        thead {
            background-color: #008000;
            color: white;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        a {
            color: #0000ee;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <br>
    <div class="header-box">
        <div class="section left">
            <h2>Order # {{ $ticket->order_number }}</h2>
            <p><strong>Ticket #:</strong> {{ $ticket->uuid }}</p>
            <p><strong>Driver:</strong> {{ $truck->driver_name }}</p>
            <p><strong>Truck:</strong> {{ $truck->truck_number }} - {{ $company->company_name }}</p>
        </div>
        @php
            $start = \Carbon\Carbon::parse($ticket->users_arrival->first()->created_at);
            $end = \Carbon\Carbon::parse($ticket->eventpickdrop->first()->drop_time);
            $diffInSeconds = $start->diffInSeconds($end);
            $hours = floor($diffInSeconds / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);
            $seconds = $diffInSeconds % 60;
        @endphp
        <div class="section center">
            <p><strong>Scheduled Start:</strong>
                {{ \Carbon\Carbon::parse($ticket->ticket_assign->first()->assigned_at)->format('m/d/Y h:i:s A') }}</p>
            <p><strong>Actual Start:</strong> {{ $start->format('m/d/Y h:i:s A') }}</p>
            <p><strong>Actual End:</strong> {{ $end->format('m/d/Y h:i:s A') }}</p>
            @php
                $totalRate = $truck->hourly_rate + $truck->trailers->sum('rate_modifier');
            @endphp
            <p><strong>Rate:</strong> ${{ number_format($totalRate, 2) }} / hour</p>
        </div>

        <div class="section right">
            <p><strong>Total Time:</strong>
                {{ $hours }} hour{{ $hours !== 1 ? 's' : '' }}
                {{ $minutes }} minute{{ $minutes !== 1 ? 's' : '' }}
                {{ $seconds }} second{{ $seconds !== 1 ? 's' : '' }}
            </p>
            <p><strong>Adjustment:</strong> {{ number_format($ticket->adjusted_minutes, 2) }} minutes</p>
            <p><strong>Toll Total:</strong>{{ $ticket->tolls ?? 'N/A' }}</p>
            <p><strong>Adj. Reason:</strong> {{ $ticket->adjusted_minutes_reason }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Material Ticket #</th>
                <th>Pick Up</th>
                <th>Material</th>
                <th>Quantity</th>
                {{-- <th>Dump Ticket #</th> --}}
                <th>Delivered</th>
                <th>Total Minutes</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $ticket->uuid ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($ticket->eventpickdrop->first()->pickup_time)->format('m/d/Y h:i:s A') }}
                </td>
                <td>{{ $ticket->eventpickdrop->first()->material }}</td>
                <td>{{ number_format($ticket->eventpickdrop->first()->quantity, 1) }}</td>
                {{-- <td><a href="#">1453329586</a></td> --}}
                <td>{{ $end->format('m/d/Y h:i:s A') }}</td>
                @php
                    $start = \Carbon\Carbon::parse($ticket->eventpickdrop->first()->pickup_time);
                    $end = \Carbon\Carbon::parse($ticket->eventpickdrop->first()->drop_time);
                    $totalMinutes = $start->diffInMinutes($end);
                @endphp
                <td>{{ $totalMinutes }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
