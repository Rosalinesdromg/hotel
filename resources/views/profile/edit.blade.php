@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
<div style="max-width:600px">

    {{-- Avatar & Info --}}
    <div class="card" style="margin-bottom:20px">
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f0ece6">
           <div style="width:64px;height:64px;border-radius:50%;flex-shrink:0;overflow:hidden;border:2px solid #e8e4de">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                    style="width:100%;height:100%;object-fit:cover">
            @else
                <div style="width:100%;height:100%;background:#1a1a2e;display:flex;align-items:center;justify-content:center">
                    <span style="color:#d4af7a;font-size:28px">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>
            <div>
                <div style="font-size:20px;color:#1a1a2e">{{ $user->name }}</div>
                <div style="font-size:13px;color:#aaa;font-family:Arial">{{ $user->email }}</div>
                <div style="margin-top:6px">
                    <span class="badge badge-dp">{{ ucfirst(auth()->user()->getRoleNames()->first()) }}</span>
                </div>
            </div>
        </div>

        {{-- Success message --}}
        @if(session('status') === 'profile-updated')
        <div style="background:#e8f5e9;border:1px solid #a5d6a7;color:#2e7d32;padding:10px 14px;border-radius:6px;font-size:13px;font-family:Arial;margin-bottom:16px">
            <i class="fas fa-check"></i> Profil berhasil diupdate.
        </div>
        @endif

        <div class="card-title">Informasi Pribadi</div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf @method('PATCH')

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'">
                @error('name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'">
                @error('email')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'"
                    placeholder="08xxxxxxxxxx">
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">
                    Tanggal Lahir <span style="color:#aaa">(min. 17 tahun)</span>
                </label>
                <input type="date" name="birthdate"
                    value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}"
                    max="{{ now()->subYears(17)->format('Y-m-d') }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'">
            </div>

            <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Foto Profil</label>
            @if(auth()->user()->avatar)
            <div style="margin-bottom:8px">
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                    style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid #e8e4de">
            </div>
            @endif
            <input type="file" name="avatar" accept="image/*" style="font-size:13px;font-family:Arial">
            <div style="font-size:12px;color:#aaa;font-family:Arial;margin-top:4px">Format: JPG, PNG. Maks 2MB.</div>
        </div>

        <button type="submit" class="btn btn-gold">Simpan Perubahan</button>

        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-title">Ganti Password</div>

        @if(session('status') === 'password-updated')
        <div style="background:#e8f5e9;border:1px solid #a5d6a7;color:#2e7d32;padding:10px 14px;border-radius:6px;font-size:13px;font-family:Arial;margin-bottom:16px">
            <i class="fas fa-check"></i> Password berhasil diubah.
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('PUT')

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Password Lama</label>
                <input type="password" name="current_password"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'"
                    placeholder="••••••••">
                @error('current_password', 'updatePassword')
                    <span style="color:red;font-size:12px">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Password Baru</label>
                <input type="password" name="password"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'"
                    placeholder="Min. 8 karakter">
                @error('password', 'updatePassword')
                    <span style="color:red;font-size:12px">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;outline:none"
                    onfocus="this.style.borderColor='#d4af7a'" onblur="this.style.borderColor='#ddd'"
                    placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-outline">Ganti Password</button>
        </form>
    </div>

    {{-- Hapus Akun --}}
    <div class="card" style="border-left:4px solid #f44336">
        <div class="card-title" style="color:#c62828">Hapus Akun</div>
        <p style="font-size:14px;color:#888;font-family:Arial;margin-bottom:16px;line-height:1.6">
            Setelah akun dihapus, semua data akan hilang permanen dan tidak bisa dipulihkan.
        </p>
        <button onclick="document.getElementById('delete-modal').style.display='flex'"
            class="btn btn-danger">Hapus Akun Saya</button>
    </div>

</div>

{{-- Modal Hapus Akun --}}
<div id="delete-modal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:32px;max-width:400px;width:90%">
        <div style="font-size:18px;color:#1a1a2e;margin-bottom:8px">Hapus Akun?</div>
        <p style="font-size:14px;color:#888;font-family:Arial;margin-bottom:20px;line-height:1.6">
            Masukkan password untuk konfirmasi penghapusan akun.
        </p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf @method('DELETE')
            <div style="margin-bottom:16px">
                <input type="password" name="password" placeholder="Password kamu"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
                @error('password', 'userDeletion')
                    <span style="color:red;font-size:12px">{{ $message }}</span>
                @enderror
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
                <button type="button" onclick="document.getElementById('delete-modal').style.display='none'"
                    class="btn btn-outline">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection