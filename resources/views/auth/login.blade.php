<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="brand">
            Kasir Sederhana
            <small>Point of Sale</small>
        </div>
        <p class="lead">Silakan masuk untuk melanjutkan</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" autofocus required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input class="input" type="password" id="password" name="password" required>
            </div>
            <div class="field checkline">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember" style="margin:0">Ingat saya</label>
            </div>
            <button type="submit" class="btn block">Masuk</button>
        </form>

        <div class="creds">
            <strong>Akun demo</strong><br>
            Admin: admin@kasir.test / password<br>
            Kasir: kasir@kasir.test / password
        </div>
    </div>
</div>
</body>
</html>
