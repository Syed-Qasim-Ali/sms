<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="../../Backend/assets/css/style.css">
    <title>Sign In</title>
</head>

<body>
    <section class="main-sec-signin">
        <div class="container">
            <div class="sign-in-form">
                <img src="{{ asset('../../Backend/assets/images/logo.png') }}" alt="">
                <h2>Sign in</h2>
                <h4>Enter Your Email & Password to Login</h4>
                <form method="POST" action="{{ route('login') }}" class="signin">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $errors->first() }}</strong>
                        </div>
                    @endif

                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <input type="email" name="email" class="sign-field" placeholder="Email Address"
                                required>
                        </div>
                        @error('email')
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
                            <a href="{{ route('register') }}">Dont have account? Create Account</a>
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
