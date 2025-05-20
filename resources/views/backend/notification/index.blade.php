<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending Approval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            color: white;
            background-color: #dc3545;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Your account is under approval 🚀</h2>
        <p>Please wait for the admin to review and approve your account.</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn">Logout</button>
        </form>
    </div>

</body>

</html>
