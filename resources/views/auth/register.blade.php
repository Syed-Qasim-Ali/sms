<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="../../Backend/assets/css/style.css">
    <title>Sign Up</title>
</head>

<body>
    <section class="main-sec-signin">
        <div class="container">
            <div class="sign-in-form">
                <img src="{{ asset('../../Backend/assets/images/logo.png') }}" alt="">
                <h2>Register</h2>
                <h4>Enter your detail to register </h4>
                <form method="POST" action="{{ route('register') }}" class="signin">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $errors->first() }}</strong>
                        </div>
                    @endif

                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="name" class="sign-field" placeholder="First Name" required>
                        </div>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-md-6">
                            <input type="text" name="lname" class="sign-field" placeholder="Last Name" required>
                        </div>
                        @error('lname')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-md-6">
                            <input type="email" name="email" class="sign-field" placeholder="Email Address"
                                required>
                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-md-6">
                            <input type="num" name="phone_num" class="sign-field" placeholder="Phone Number"
                                required>
                        </div>
                        @error('phone_num')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-md-12">
                            <div class="smth">
                                <input type="password" name="password" class="sign-field" placeholder="Password"
                                    required id="myInput">
                                <div class="eye">
                                    <i type="checkbox" onclick="myFunction()" class="fa-solid fa-eye-slash"></i>
                                </div>
                            </div>
                        </div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <div class="col-md-12">
                            <div class="smth">
                                <input type="password" name="password_confirmation" class="sign-field"
                                    placeholder="Confirm Password*" required id="myInput">
                                <div class="eye">
                                    <i type="checkbox" onclick="myFunction()" class="fa-solid fa-eye-slash"></i>
                                </div>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <div class="col-md-12">
                            <div class="smth">
                                <input type="text" name="roles" class="sign-field" value="driver" hidden>
                                <input type="text" name="status" class="sign-field" value="pending" hidden>
                            </div>
                        </div>
                        @error('roles')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-md-12">
                            <div class="help">
                                <div class="radio-box">
                                    <input type="radio" id="rm">
                                    <label for="rm">Remember Me</label>
                                </div>
                                <div class="forget">
                                    <a href="#">Forget Password</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" class="submitbtn" value="Sign in">
                        </div>
                        <div class="ca">
                            <a href="{{ route('login') }}">Already have an account? Sign in</a>
                        </div>
                    </div>
                </Form>
            </div>
        </div>
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function myFunction() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

</html>
