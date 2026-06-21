@extends('layouts.app')
@section('title', 'Edit Tipe Kamar')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-title">Edit Tipe Kamar</div>
    <form method="POST" action="/room-types/{{ $roomType->id }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Tipe</label>
            <input type="text" name="name" value="{{ old('name', $roomType->name) }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            @error('name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Deskripsi</label>
            <textarea name="description" rows="3"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">{{ old('description', $roomType->description) }}</textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Kapasitas (orang)</label>
                <input type="number" name="capacity" value="{{ old('capacity', $roomType->capacity) }}" min="1"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Harga Weekday (Rp)</label>
                <input type="number" name="base_price" value="{{ old('base_price', $roomType->base_price) }}" min="0"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Harga Weekend (Rp)</label>
            <input type="number" name="weekend_price" value="{{ old('weekend_price', $roomType->weekend_price) }}" min="0"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Foto Kamar</label>
            @if($roomType->image)
                <img src="{{ asset('storage/' . $roomType->image) }}"
                    style="width:120px;height:80px;object-fit:cover;border-radius:6px;margin-bottom:8px;display:block">
            @endif
            <input type="file" name="image" accept="image/*" style="font-size:13px;font-family:Arial">
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Update</button>
            <a href="/room-types" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection