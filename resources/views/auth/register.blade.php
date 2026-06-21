<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Lunar Hotel</title>
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
            padding: 24px;
        }
        .login-wrap { width: 100%; max-width: 440px; }
        .brand { text-align:center; margin-bottom:32px; }
        .brand h1 { color:#d4af7a; font-size:28px; font-weight:normal; letter-spacing:4px; }
        .brand p  { color:rgba(255,255,255,0.3); font-size:11px; letter-spacing:4px; text-transform:uppercase; margin-top:6px; font-family:Arial; }
        .card { background:#fff; border-radius:12px; padding:36px; }
        .card h2 { font-size:18px; color:#1a1a2e; font-weight:normal; margin-bottom:24px; letter-spacing:1px; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; font-size:12px; color:#888; margin-bottom:6px; font-family:Arial; letter-spacing:1px; text-transform:uppercase; }
        .form-group input { width:100%; padding:11px 14px; border:1px solid #e0dbd4; border-radius:6px; font-size:14px; font-family:Arial; outline:none; transition:border 0.2s; }
        .form-group input:focus { border-color:#d4af7a; }
        .error-msg { background:#ffebee; border:1px solid #ef9a9a; color:#c62828; padding:10px 14px; border-radius:6px; font-size:13px; font-family:Arial; margin-bottom:16px; }
        .field-error { color:red; font-size:12px; font-family:Arial; margin-top:4px; }
        .btn-register { width:100%; padding:12px; background:#d4af7a; color:#fff; border:none; border-radius:6px; font-size:15px; font-family:Georgia; letter-spacing:1px; cursor:pointer; transition:background 0.2s; margin-top:4px; }
        .btn-register:hover { background:#c49a60; }
        .footer-link { text-align:center; margin-top:20px; font-size:13px; font-family:Arial; color:#aaa; }
        .footer-link a { color:#d4af7a; text-decoration:none; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="brand">
        <h1>☽ LUNAR</h1>
        <p>Hotel</p>
    </div>
    <div class="card">
        <h2>Buat Akun Baru</h2>

        @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                @error('phone')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="birthdate" value="{{ old('birthdate') }}"
                    max="{{ now()->subYears(17)->format('Y-m-d') }}" required>
        @error('birthdate')
            <div class="field-error">{{ $message }}</div>
        @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min. 8 karakter" required>
                @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn-register">Daftar Sekarang</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>
</body>
</html>