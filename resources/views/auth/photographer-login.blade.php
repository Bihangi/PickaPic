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
        <div class="logo-title">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
            <h2>Photographer Login</h2>
        </div>
        <p>Welcome back!<br>Enter your Credentials to access your account</p>

        @if($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="#">
            @csrf
            <label>Email address</label>
            <input type="email" name="email" placeholder="Enter Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <a href="#" class="forgot-link">forgot password</a>

            <button type="submit" class="login-btn">LOGIN</button>
        </form>

        <div class="divider">Or</div>

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
