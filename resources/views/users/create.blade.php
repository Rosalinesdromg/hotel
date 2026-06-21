@extends('layouts.app')
@section('title', 'Tambah Staff')

@section('content')
<div class="card" style="max-width:500px">
    <div class="card-title">Tambah Staff Baru</div>
    <form method="POST" action="/users">
        @csrf
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            @error('name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            @error('email')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Role</label>
            <select name="role" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
                @endforeach
            </select>
            @error('role')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Password</label>
            <input type="password" name="password"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            @error('password')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Simpan</button>
            <a href="/users" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection