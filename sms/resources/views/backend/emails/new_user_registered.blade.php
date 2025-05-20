<!DOCTYPE html>
<html>
<head>
    <title>New User Registered</title>
</head>
<body>
    <h1>New User Registration Notification</h1>
    <p>A new user has registered on your platform.</p>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Registered at:</strong> {{ $user->created_at }}</p>
</body>
</html>
