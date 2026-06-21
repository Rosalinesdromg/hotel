@extends('layouts.app')
@section('title', 'Tambah Tipe Kamar')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-title">Tambah Tipe Kamar</div>
    <form method="POST" action="/room-types" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Tipe</label>
            <input type="text" name="name" value="{{ old('name') }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial"
                placeholder="contoh: Deluxe, Suite, Standard">
            @error('name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Deskripsi</label>
            <textarea name="description" rows="3"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">{{ old('description') }}</textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Kapasitas (orang)</label>
                <input type="number" name="capacity" value="{{ old('capacity') }}" min="1"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Harga Weekday (Rp)</label>
                <input type="number" name="base_price" value="{{ old('base_price') }}" min="0"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Harga Weekend (Rp) — kosongkan jika sama</label>
            <input type="number" name="weekend_price" value="{{ old('weekend_price') }}" min="0"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Foto Kamar</label>
            <input type="file" name="image" accept="image/*"
                style="font-size:13px;font-family:Arial">
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Simpan</button>
            <a href="/room-types" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection