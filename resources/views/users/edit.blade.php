@extends('layouts.app')
@section('title', 'Edit Staff')

@section('content')
<div class="card" style="max-width:500px">
    <div class="card-title">Edit Staff — {{ $user->name }}</div>
    <form method="POST" action="/users/{{ $user->id }}">
        @csrf @method('PUT')
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Email</label>
            <input type="email" value="{{ $user->email }}" disabled
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#f9f7f4;color:#aaa">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Role</label>
            <select name="role" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Password Baru <span style="color:#aaa">(kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Update</button>
            <a href="/users" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection