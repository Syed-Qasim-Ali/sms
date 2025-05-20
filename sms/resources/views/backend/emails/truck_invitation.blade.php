<!DOCTYPE html>
<html>

<head>
    <title>Truck Invitation</title>
</head>

<body>
    <h2>🚛 Truck Invitation</h2>
    <p>Hello,</p>
    <p>Your truck <strong>#{{ $truck->truck_number }} ({{ $truck->truck_type }})</strong> has been invited.</p>
    <p>Click the link below to view details:</p>
    <a href="{{ url('/trucks/' . $truck->id) }}">View Truck</a>
    <br>
    <p>Thank you!</p>
</body>

</html>
