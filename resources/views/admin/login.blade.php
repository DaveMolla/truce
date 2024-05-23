<!-- resources/views/auth/admin-login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h1>Admin Login</h1>

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div>
            <label for="phone">Phone</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus>
            @error('phone')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
