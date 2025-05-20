<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ticket Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        body {
            background: #1a1a1a url('https://www.transparenttextures.com/patterns/asfalt-dark.png');
            color: #fff;
        }

        .ticket-header,
        .event-details {
            background-color: #ccc;
            padding: 20px;
            margin-bottom: 20px;
            color: #000;
            border-radius: 5px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        table tr td {
            background-color: #fff;
            color: #000;
        }

        table thead th {
            background-color: #e6e6e6;
            color: #000;
        }
    </style>
</head>

<body>

    <div class="container mt-5">

        <!-- Ticket Header -->
        <div class="ticket-header">
            <h4>Ticket # {{ $ticket->uuid }}</h4>
            <p>Driver: {{ $user->name }}</p>
            {{-- <p>Arrived on {{ $ticket->eventpickdrop->created_at }}{{ $ticket->eventpickdrop->drop_time }}</p> --}}
            <p>Arrived on
                {{ \Carbon\Carbon::parse($ticket->eventpickdrop->first()->created_at)->format('F d, Y') }}
                {{ \Carbon\Carbon::parse($ticket->eventpickdrop->first()->drop_time)->format('h:i A') }}
            </p>
        </div>

        <!-- Event Details -->
        <div class="event-details">
            <h5>Event Details</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Time</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pickup</td>
                        <td>
                            {{ \Carbon\Carbon::parse($ticket->eventpickdrop->first()->pickup_time)->format('h:i A') }}
                        </td>
                        <td>{{ $ticket->eventpickdrop->first()->quantity }} tons of
                            {{ $ticket->eventpickdrop->first()->material }}</td>
                    </tr>
                    <tr>
                        <td>Delivery</td>
                        <td>
                            {{ \Carbon\Carbon::parse($ticket->eventpickdrop->first()->drop_time)->format('h:i A') }}
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Buttons -->
        <div class="btn-container">
            @if ($ticket->eventpickdrop->first()->status === 'pending')
                <form action="{{ route('response', $ticket->uuid) }}" method="post" style="display:inline;">
                    @csrf
                    <button class="btn btn-success btn-lg px-5" type="submit">Approve</button>
                </form>
                <form action="{{ route('deny', $ticket->uuid) }}" method="post" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="denied">
                    <button class="btn btn-danger btn-lg px-5" type="submit">Deny</button>
                </form>
            @else
                <p class="mt-3 text-success h5">Thanks for submitting your response</p>
            @endif
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @if (session('success'))
        <script>
            toastr.info("{{ session('success') }}");
        </script>
    @endif
</body>

</html>
