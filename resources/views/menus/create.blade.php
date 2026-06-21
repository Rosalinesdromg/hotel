@extends('layouts.app')
@section('title', 'Tambah Menu')

@section('content')
<div class="card" style="max-width:900px">
    <div class="card-title">Tambah Menu Restoran</div>
    <form method="POST" action="/menus/bulk" enctype="multipart/form-data">
        @csrf

        {{-- Header tabel --}}
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:12px;margin-bottom:8px;padding:0 4px">
            <div style="font-size:10px;color:#aaa;font-family:Arial;letter-spacing:2px;text-transform:uppercase">Nama Menu</div>
            <div style="font-size:10px;color:#aaa;font-family:Arial;letter-spacing:2px;text-transform:uppercase">Kategori</div>
            <div style="font-size:10px;color:#aaa;font-family:Arial;letter-spacing:2px;text-transform:uppercase">Harga (Rp)</div>
            <div style="font-size:10px;color:#aaa;font-family:Arial;letter-spacing:2px;text-transform:uppercase">Stok</div>
            <div></div>
        </div>

        <div id="menu-rows">
            <div class="menu-row" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:12px;align-items:center;margin-bottom:10px">
                <input type="text" name="menus[0][name]"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial"
                    placeholder="Nama menu">
                <select name="menus[0][category]"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial;background:#fff">
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                    <option value="Dessert">Dessert</option>
                </select>
                <input type="number" name="menus[0][price]" min="0"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial"
                    placeholder="0">
                <input type="number" name="menus[0][stock]" min="0" value="50"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial">
                <button type="button" onclick="removeMenuRow(this)"
                    style="padding:10px 14px;background:#fdecea;border:1px solid #f5b7b1;color:#c0392b;border-radius:4px;cursor:pointer;font-size:16px;line-height:1">
                    ×
                </button>
            </div>
        </div>

        <button type="button" onclick="addMenuRow()"
            style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:transparent;border:1px dashed #d4af7a;color:#d4af7a;border-radius:4px;cursor:pointer;font-size:13px;font-family:Arial;margin-bottom:24px;transition:all 0.2s"
            onmouseover="this.style.background='#faf7f0'" onmouseout="this.style.background='transparent'">
            <i class="fas fa-plus"></i> Tambah Baris
        </button>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Simpan Semua</button>
            <a href="/menus" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let menuRowCount = 1;

function addMenuRow() {
    const container = document.getElementById('menu-rows');
    const div = document.createElement('div');
    div.className = 'menu-row';
    div.style = 'display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:12px;align-items:center;margin-bottom:10px';
    div.innerHTML = `
        <input type="text" name="menus[${menuRowCount}][name]"
            style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial"
            placeholder="Nama menu">
        <select name="menus[${menuRowCount}][category]"
            style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial;background:#fff">
            <option value="Makanan">Makanan</option>
            <option value="Minuman">Minuman</option>
            <option value="Snack">Snack</option>
            <option value="Dessert">Dessert</option>
        </select>
        <input type="number" name="menus[${menuRowCount}][price]" min="0"
            style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial"
            placeholder="0">
        <input type="number" name="menus[${menuRowCount}][stock]" min="0" value="50"
            style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial">
        <button type="button" onclick="removeMenuRow(this)"
            style="padding:10px 14px;background:#fdecea;border:1px solid #f5b7b1;color:#c0392b;border-radius:4px;cursor:pointer;font-size:16px;line-height:1">
            ×
        </button>
    `;
    container.appendChild(div);
    menuRowCount++;
}

function removeMenuRow(btn) {
    const rows = document.querySelectorAll('.menu-row');
    if (rows.length > 1) {
        btn.closest('.menu-row').remove();
    } else {
        alert('Minimal harus ada 1 baris!');
    }
}
</script>
@endpush
@endsection