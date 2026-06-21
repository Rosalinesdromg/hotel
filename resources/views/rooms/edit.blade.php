@extends('layouts.app')
@section('title', 'Edit Kamar')

@section('content')
<div class="card" style="max-width:500px">
    <div class="card-title">Edit Kamar {{ $room->room_number }}</div>
    <form method="POST" action="/rooms/{{ $room->id }}">
        @csrf @method('PUT')
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nomor Kamar</label>
            <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            @error('room_number')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tipe Kamar</label>
            <select name="room_type_id"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                @foreach($roomTypes as $type)
                <option value="{{ $type->id }}" {{ $room->room_type_id == $type->id ? 'selected' : '' }}>
                    {{ $type->name }} — Rp {{ number_format($type->base_price, 0, ',', '.') }}/malam
                </option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Status</label>
            <select name="status"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                <option value="available"    {{ $room->status == 'available'    ? 'selected' : '' }}>Tersedia</option>
                <option value="occupied"     {{ $room->status == 'occupied'     ? 'selected' : '' }}>Terisi</option>
                <option value="dirty"        {{ $room->status == 'dirty'        ? 'selected' : '' }}>Kotor</option>
                <option value="maintenance"  {{ $room->status == 'maintenance'  ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Update</button>
            <a href="/rooms" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection