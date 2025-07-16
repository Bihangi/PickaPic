<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photographer Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px;">
        <h2>Photographer Login</h2>
        <p>Welcome back!<br>Enter your Credentials to access your account</p>
        
        @if($errors->any())
            <div style="color: red; margin-bottom: 10px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="#">
            @csrf
            <label>Email address</label>
            <input type="email" name="email" placeholder="Enter Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <a href="#" style="font-size: 13px;">forgot password</a>

            <button type="submit">LOGIN</button>
        </form>

        <div style="margin: 20px 0; text-align: center;">Or</div>

        <div class="google-btn">
            <img src="https://img.icons8.com/color/48/000000/google-logo.png" />
            Sign in with Google
        </div>

        <div class="links">
            Don’t have an account? <a href="#">Join now!</a>
        </div>
    </div>
</div>
</body>
</html>
