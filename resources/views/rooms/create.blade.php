@extends('layouts.app')
@section('title', 'Tambah Kamar')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-title">Tambah Kamar</div>
    <form method="POST" action="/rooms/bulk">
        @csrf
        <div id="room-rows">
            <div class="room-row" style="display:grid;grid-template-columns:1fr 2fr auto;gap:12px;align-items:end;margin-bottom:12px">
                <div>
                    <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-family:Arial;letter-spacing:1px;text-transform:uppercase">Nomor Kamar</label>
                    <input type="text" name="rooms[0][room_number]"
                        style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial"
                        placeholder="contoh: 101">
                </div>
                <div>
                    <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-family:Arial;letter-spacing:1px;text-transform:uppercase">Tipe Kamar</label>
                    <select name="rooms[0][room_type_id]"
                        style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial;background:#fff">
                        <option value="">-- Pilih Tipe --</option>
                        @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }} — Rp {{ number_format($type->base_price, 0, ',', '.') }}/malam</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button" onclick="removeRow(this)"
                        style="padding:10px 14px;background:#fdecea;border:1px solid #f5b7b1;color:#c0392b;border-radius:4px;cursor:pointer;font-size:16px;line-height:1">
                        ×
                    </button>
                </div>
            </div>
        </div>

        <button type="button" onclick="addRoomRow()"
            style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:transparent;border:1px dashed #d4af7a;color:#d4af7a;border-radius:4px;cursor:pointer;font-size:13px;font-family:Arial;margin-bottom:24px;transition:all 0.2s"
            onmouseover="this.style.background='#faf7f0'" onmouseout="this.style.background='transparent'">
            <i class="fas fa-plus"></i> Tambah Baris
        </button>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-gold">Simpan Semua</button>
            <a href="/rooms" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let rowCount = 1;
const roomTypes = @json($roomTypes);

function addRoomRow() {
    const container = document.getElementById('room-rows');
    const div = document.createElement('div');
    div.className = 'room-row';
    div.style = 'display:grid;grid-template-columns:1fr 2fr auto;gap:12px;align-items:end;margin-bottom:12px';

    let options = '<option value="">-- Pilih Tipe --</option>';
    roomTypes.forEach(t => {
        options += `<option value="${t.id}">${t.name} — Rp ${parseFloat(t.base_price).toLocaleString('id-ID')}/malam</option>`;
    });

    div.innerHTML = `
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-family:Arial;letter-spacing:1px;text-transform:uppercase">Nomor Kamar</label>
            <input type="text" name="rooms[${rowCount}][room_number]"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial"
                placeholder="contoh: 10${rowCount + 1}">
        </div>
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-family:Arial;letter-spacing:1px;text-transform:uppercase">Tipe Kamar</label>
            <select name="rooms[${rowCount}][room_type_id]"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial;background:#fff">
                ${options}
            </select>
        </div>
        <div>
            <button type="button" onclick="removeRow(this)"
                style="padding:10px 14px;background:#fdecea;border:1px solid #f5b7b1;color:#c0392b;border-radius:4px;cursor:pointer;font-size:16px;line-height:1">
                ×
            </button>
        </div>
    `;
    container.appendChild(div);
    rowCount++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.room-row');
    if (rows.length > 1) {
        btn.closest('.room-row').remove();
    } else {
        alert('Minimal harus ada 1 baris!');
    }
}
</script>
@endpush
@endsection