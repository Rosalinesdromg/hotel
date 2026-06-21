<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Lunar Hotel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Georgia', serif;
            background: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-wrap {
            width: 100%;
            max-width: 420px;
            padding: 24px;
        }
        .brand {
            text-align: center;
            margin-bottom: 36px;
        }
        .brand h1 {
            color: #d4af7a;
            font-size: 32px;
            font-weight: normal;
            letter-spacing: 4px;
        }
        .brand p {
            color: rgba(255,255,255,0.3);
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 6px;
            font-family: Arial, sans-serif;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 36px;
        }
        .card h2 {
            font-size: 18px;
            color: #1a1a2e;
            font-weight: normal;
            margin-bottom: 24px;
            letter-spacing: 1px;
        }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12px;
            color: #888;
            margin-bottom: 6px;
            font-family: Arial, sans-serif;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #e0dbd4;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            color: #2c2c2c;
            outline: none;
            transition: border 0.2s;
        }
        .form-group input:focus { border-color: #d4af7a; }
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #888;
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #d4af7a;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-family: Georgia, serif;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #c49a60; }
        .error-msg {
            background: #ffebee;
            border: 1px solid #ef9a9a;
            color: #c62828;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-family: Arial, sans-serif;
            margin-bottom: 18px;
        }
        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            font-family: Arial, sans-serif;
            color: #aaa;
        }
        .footer-link a { color: #d4af7a; text-decoration: none; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="brand">
        <h1>☽ LUNAR</h1>
        <p>Hotel</p>
    </div>
    <div class="card">
        <h2>Masuk ke Akun</h2>

        @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        @if(session('status'))
        <div style="background:#e8f5e9;border:1px solid #a5d6a7;color:#2e7d32;padding:10px 14px;border-radius:6px;font-size:13px;font-family:Arial;margin-bottom:18px">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="remember">
                <input type="checkbox" name="remember" id="remember" style="width:16px;height:16px">
                <label for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="footer-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
</div>
</body>
</html>