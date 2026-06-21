@extends('layouts.app')
@section('title', 'Booking Baru')

@section('content')
<div class="card" style="max-width:680px">
    <div class="card-title">Form Booking Kamar</div>

    {{-- Step 1: Pilih tipe & tanggal --}}
    <div style="margin-bottom:20px">
        <div style="font-size:13px;color:#999;font-family:Arial;margin-bottom:16px;letter-spacing:1px;text-transform:uppercase">Step 1 — Pilih Kamar & Tanggal</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tipe Kamar</label>
                <select id="room_type_id" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}"
                        {{ ($prefill['room_type_id'] ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }} — Rp {{ number_format($type->base_price, 0, ',', '.') }}/malam
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Jumlah Tamu</label>
                <input type="number" id="guest_count" name="guest_count" min="1" value="1"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Check-in</label>
                {{-- Check-in --}}
                <input type="date" id="check_in" min="{{ date('Y-m-d') }}"
                    value="{{ $prefill['check_in'] ?? '' }}"
                    style="...">
            </div>
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Check-out</label>
                {{-- Check-out --}}
                <input type="date" id="check_out"
                    value="{{ $prefill['check_out'] ?? '' }}"
                    style="...">
            </div>
        </div>
        <button onclick="checkAvailability()" class="btn btn-outline">Cek Ketersediaan</button>
    </div>

    {{-- Step 2: Hasil & form submit --}}
    <div id="result" style="display:none;border-top:1px solid #f0ece6;padding-top:20px;margin-top:4px">
        <div style="font-size:13px;color:#999;font-family:Arial;margin-bottom:16px;letter-spacing:1px;text-transform:uppercase">Step 2 — Pilih Kamar & Paket</div>

        <div id="rooms-list" style="margin-bottom:16px"></div>

        <form method="POST" action="/bookings" id="booking-form">
            @csrf
            <input type="hidden" name="room_id" id="selected_room_id">
            <input type="hidden" name="check_in" id="f_check_in">
            <input type="hidden" name="check_out" id="f_check_out">
            <input type="hidden" name="guest_count" id="f_guest_count">
            <input type="hidden" name="total_price" id="f_total_price">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Paket</label>
                    <select name="package" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                        <option value="room_only">Room Only</option>
                        <option value="with_breakfast">Room + Sarapan</option>
                        <option value="full_package">Full Package</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Pembayaran</label>
                    <select name="payment_type" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                        <option value="full">Lunas</option>
                        <option value="dp">DP 30%</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:flex;align-items:center;gap:8px;font-size:14px;font-family:Arial;cursor:pointer">
                    <input type="checkbox" name="extra_bed" style="width:16px;height:16px">
                    Tambah Extra Bed (+Rp 150.000/malam)
                </label>
            </div>

            <div id="price-summary" style="background:#f9f7f4;border-radius:8px;padding:16px;margin-bottom:20px;font-family:Arial">
                <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;margin-bottom:6px">
                    <span id="nights-label">— malam</span>
                    <span id="price-label">Rp —</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:bold;color:#1a1a2e;border-top:1px solid #e8e4de;padding-top:10px;margin-top:6px">
                    <span>Total</span>
                    <span id="total-label" style="color:#d4af7a">Rp —</span>
                </div>
            </div>

            <button type="submit" class="btn btn-gold" style="width:100%;padding:12px;font-size:15px">Konfirmasi Booking</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
async function checkAvailability() {
    const roomTypeId = document.getElementById('room_type_id').value;
    const checkIn    = document.getElementById('check_in').value;
    const checkOut   = document.getElementById('check_out').value;

    if (!roomTypeId || !checkIn || !checkOut) {
        alert('Lengkapi tipe kamar dan tanggal dulu.');
        return;
    }

    try {
        const res = await fetch('/bookings/check-availability?' + new URLSearchParams({
            room_type_id: roomTypeId, check_in: checkIn, check_out: checkOut
        }));

        if (!res.ok) {
            alert('Server error: ' + res.status);
            return;
        }

        const data = await res.json();
        const list = document.getElementById('rooms-list');
        list.innerHTML = '';

        if (data.rooms.length === 0) {
            list.innerHTML = '<div style="color:#c62828;font-family:Arial;font-size:14px;padding:12px;background:#ffebee;border-radius:6px">Tidak ada kamar tersedia di tanggal ini.</div>';
            document.getElementById('result').style.display = 'block';
            document.getElementById('booking-form').style.display = 'none';
            return;
        }

        data.rooms.forEach(room => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = `<strong>Kamar ${room.room_number}</strong>`;
            btn.style = 'margin-right:8px;margin-bottom:8px;padding:8px 16px;border:2px solid #ddd;border-radius:6px;background:#fff;cursor:pointer;font-family:Arial;font-size:14px;transition:all 0.2s';
            btn.onclick = function() {
                document.querySelectorAll('#rooms-list button').forEach(b => b.style.borderColor = '#ddd');
                this.style.borderColor = '#d4af7a';
                document.getElementById('selected_room_id').value = room.id;
            };
            list.appendChild(btn);
        });

        document.getElementById('f_check_in').value    = checkIn;
        document.getElementById('f_check_out').value   = checkOut;
        document.getElementById('f_guest_count').value = document.getElementById('guest_count').value;
        document.getElementById('f_total_price').value = data.total_price;

        document.getElementById('nights-label').textContent = data.nights + ' malam';
        document.getElementById('price-label').textContent  = 'Rp ' + data.total_price.toLocaleString('id-ID');
        document.getElementById('total-label').textContent  = 'Rp ' + data.total_price.toLocaleString('id-ID');

        document.getElementById('result').style.display      = 'block';
        document.getElementById('booking-form').style.display = 'block';

    } catch(e) {
        alert('Terjadi error: ' + e.message);
    }
}
</script>
@endpush
@endsection