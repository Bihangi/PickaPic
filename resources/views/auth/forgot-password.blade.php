<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Reset Password</h2>

    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
