@extends('layouts.app')
@section('title', 'Edit Menu')

@section('content')
<div class="card" style="max-width:500px">
    <div class="card-title">Edit Menu</div>
    <form method="POST" action="/menus/{{ $menu->id }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Menu</label>
            <input type="text" name="name" value="{{ old('name', $menu->name) }}"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Kategori</label>
            <select name="category" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                <option value="Makanan" {{ $menu->category == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                <option value="Minuman" {{ $menu->category == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                <option value="Snack"   {{ $menu->category == 'Snack'   ? 'selected' : '' }}>Snack</option>
                <option value="Dessert" {{ $menu->category == 'Dessert' ? 'selected' : '' }}>Dessert</option>
            </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $menu->price) }}" min="0"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $menu->stock) }}" min="0"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Foto Menu</label>
            @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}"
                    style="width:100px;height:70px;object-fit:cover;border-radius:6px;margin-bottom:8px;display:block">
            @endif
            <input type="file" name="image" accept="image/*" style="font-size:13px;font-family:Arial">
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Update</button>
            <a href="/menus" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection