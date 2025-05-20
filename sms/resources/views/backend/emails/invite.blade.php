<!DOCTYPE html>
<html>

<head>
    <title>Invitation</title>
</head>

<body>
    <h2>Hello {{ $user->name }},</h2>
    <p>You have been invited to join our platform. Click the button below to accept your invitation:</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong>{{ $planpassword }} </p>
    <p>
        <a href="{{ $inviteLink }}"
            style="background: #28a745; padding: 10px 20px; color: white; text-decoration: none;">
            Login
        </a>
    </p>
    <p>If you did not request this, please ignore this email.</p>
</body>

</html>
