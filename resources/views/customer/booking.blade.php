@extends('layouts.app')
@section('title', 'Pesan Kamar')

@section('content')
<div class="card" style="max-width:680px">
    <div class="card-title">Pesan Kamar</div>

    <div style="margin-bottom:20px">
        <div style="font-size:13px;color:#999;font-family:Arial;margin-bottom:16px;letter-spacing:1px;text-transform:uppercase">
            Step 1 — Pilih Kamar & Tanggal
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tipe Kamar</label>
                <select id="room_type_id"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
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
                <input type="number" id="guest_count" min="1" value="1"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Check-in</label>
                <input type="date" id="check_in" min="{{ date('Y-m-d') }}"
                    value="{{ $prefill['check_in'] ?? '' }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
            <div>
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Check-out</label>
                <input type="date" id="check_out"
                    value="{{ $prefill['check_out'] ?? '' }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
            </div>
        </div>
        <button onclick="checkAvailability()" class="btn btn-outline">Cek Ketersediaan</button>
    </div>

    <div id="result" style="display:none;border-top:1px solid #f0ece6;padding-top:20px;margin-top:4px">
        <div style="font-size:13px;color:#999;font-family:Arial;margin-bottom:16px;letter-spacing:1px;text-transform:uppercase">
            Step 2 — Pilih Kamar & Paket
        </div>

        <div id="rooms-list" style="margin-bottom:16px"></div>

        <form method="POST" action="/customer/bookings" id="booking-form">
            @csrf
            <input type="hidden" name="room_id"      id="selected_room_id">
            <input type="hidden" name="check_in"     id="f_check_in">
            <input type="hidden" name="check_out"    id="f_check_out">
            <input type="hidden" name="guest_count"  id="f_guest_count">
            <input type="hidden" name="total_price"  id="f_total_price">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Paket</label>
                    <select name="package"
                        style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                        <option value="room_only">Room Only</option>
                        <option value="with_breakfast">Room + Sarapan</option>
                        <option value="full_package">Full Package</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Pembayaran</label>
                    <select name="payment_type" id="payment_type" onchange="updateTotal()"
                        style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                        
                        <option value="full">Lunas</option>
                        <option value="dp">DP 30%</option>
                    </select>
                </div>
            </div>

            {{-- TAMBAH INI --}}
{{-- Metode Pembayaran --}}
<div style="margin-bottom:16px">
    <label style="display:block;font-size:13px;color:#666;margin-bottom:10px;font-family:Arial">Metode Pembayaran</label>
    <div style="display:flex;gap:12px;margin-bottom:12px">

        {{-- Cash --}}
        <label style="flex:1;border:2px solid #d4af7a;border-radius:6px;padding:14px;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:10px"
            id="label-cash" onclick="selectPayMethod('cash')">
            <input type="radio" name="payment_method" value="cash" checked
                style="display:none" id="radio-cash">
            <div style="width:36px;height:36px;background:#f9f7f4;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-money-bill-wave" style="color:#d4af7a"></i>
            </div>
            <div>
                <div style="font-size:14px;color:#1a1a2e;font-family:Arial;font-weight:bold">Cash</div>
                <div style="font-size:11px;color:#aaa;font-family:Arial">Bayar tunai di resepsionis</div>
            </div>
        </label>

        {{-- Kartu --}}
        <label style="flex:1;border:2px solid #ddd;border-radius:6px;padding:14px;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:10px"
            id="label-card" onclick="selectPayMethod('card')">
            <input type="radio" name="payment_method" value="card"
                style="display:none" id="radio-card">
            <div style="width:36px;height:36px;background:#f9f7f4;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-credit-card" style="color:#d4af7a"></i>
            </div>
            <div>
                <div style="font-size:14px;color:#1a1a2e;font-family:Arial;font-weight:bold">Kartu / Transfer</div>
                <div style="font-size:11px;color:#aaa;font-family:Arial">Debit, Kredit, atau Transfer</div>
            </div>
        </label>
    </div>

    {{-- Pilihan Bank/Aplikasi (muncul kalau pilih Kartu) --}}
    <div id="bank-options" style="display:none">
        <label style="display:block;font-size:12px;color:#888;margin-bottom:8px;font-family:Arial;letter-spacing:1px">Pilih Bank / Aplikasi</label>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px">

            @php
            $banks = [
                ['value'=>'bca',     'label'=>'BCA',      'icon'=>'fas fa-university'],
                ['value'=>'bni',     'label'=>'BNI',      'icon'=>'fas fa-university'],
                ['value'=>'bri',     'label'=>'BRI',      'icon'=>'fas fa-university'],
                ['value'=>'mandiri', 'label'=>'Mandiri',  'icon'=>'fas fa-university'],
                ['value'=>'cimb',    'label'=>'CIMB',     'icon'=>'fas fa-university'],
                ['value'=>'gopay',   'label'=>'GoPay',    'icon'=>'fas fa-mobile-alt'],
                ['value'=>'ovo',     'label'=>'OVO',      'icon'=>'fas fa-mobile-alt'],
                ['value'=>'dana',    'label'=>'DANA',     'icon'=>'fas fa-mobile-alt'],
                ['value'=>'shopeepay','label'=>'ShopeePay','icon'=>'fas fa-mobile-alt'],
            ];
            @endphp

            @foreach($banks as $bank)
            <div onclick="selectBank('{{ $bank['value'] }}')"
                id="bank-{{ $bank['value'] }}"
                style="border:1px solid #ddd;border-radius:6px;padding:10px 8px;cursor:pointer;transition:all 0.2s;text-align:center;background:#fff"
                onmouseover="if(!this.classList.contains('selected-bank')) this.style.borderColor='#d4af7a'"
                onmouseout="if(!this.classList.contains('selected-bank')) this.style.borderColor='#ddd'">
                <i class="{{ $bank['icon'] }}" style="color:#d4af7a;font-size:16px;margin-bottom:4px;display:block"></i>
                <div style="font-size:12px;font-family:Arial;color:#1a1a2e">{{ $bank['label'] }}</div>
            </div>
            @endforeach
        </div>
        <input type="hidden" name="bank_option" id="bank_option" value="">
        <div id="bank-error" style="display:none;color:red;font-size:12px;font-family:Arial;margin-top:6px">
            Pilih bank atau aplikasi pembayaran dulu.
        </div>
    </div>
</div>

            <div style="margin-bottom:20px">
                <label style="display:flex;align-items:center;gap:8px;font-size:14px;font-family:Arial;cursor:pointer">
                    <input type="checkbox" name="extra_bed" id="extra_bed" onchange="updateTotal()"
                        style="width:16px;height:16px">
                    Tambah Extra Bed (+Rp 150.000/malam)
                </label>
            </div>

            <div id="price-summary"
                style="background:#f9f7f4;border-radius:8px;padding:20px;margin-bottom:20px;font-family:Arial">
                <div style="font-size:13px;color:#999;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px">
                    Ringkasan Pembayaran
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;margin-bottom:6px">
                    <span id="nights-label">— malam</span>
                    <span id="price-label">Rp —</span>
                </div>
                <div id="extra-bed-row" style="display:none;justify-content:space-between;font-size:14px;color:#666;margin-bottom:6px">
                    <span>Extra Bed</span>
                    <span id="extra-bed-price">Rp —</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;margin-bottom:6px">
                    <span id="payment-label">Total</span>
                    <span id="total-label" style="color:#d4af7a;font-size:18px;font-weight:bold">Rp —</span>
                </div>
                <div id="dp-info" style="display:none;font-size:12px;color:#888;text-align:right;margin-top:4px">
                    Sisa: <span id="remaining-label">Rp —</span> dibayar saat check-in
                </div>
            </div>

            <button type="submit" onclick="return validateForm()"
                class="btn btn-gold" style="width:100%;padding:14px;font-size:15px">
                Konfirmasi Booking
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
let basePrice  = 0;
let nightCount = 0;

async function checkAvailability() {
    const roomTypeId = document.getElementById('room_type_id').value;
    const checkIn    = document.getElementById('check_in').value;
    const checkOut   = document.getElementById('check_out').value;

    if (!roomTypeId || !checkIn || !checkOut) {
        alert('Lengkapi tipe kamar dan tanggal dulu.');
        return;
    }

    try {
        const res  = await fetch('/customer/check-availability?' + new URLSearchParams({
            room_type_id: roomTypeId, check_in: checkIn, check_out: checkOut
        }));
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
            btn.style = 'margin-right:8px;margin-bottom:8px;padding:10px 20px;border:2px solid #ddd;border-radius:6px;background:#fff;cursor:pointer;font-family:Arial;font-size:14px;transition:all 0.2s';
            btn.onclick = function() {
                document.querySelectorAll('#rooms-list button').forEach(b => b.style.borderColor = '#ddd');
                this.style.borderColor = '#d4af7a';
                document.getElementById('selected_room_id').value = room.id;
            };
            list.appendChild(btn);
        });

        // Set hidden fields
        document.getElementById('f_check_in').value    = checkIn;
        document.getElementById('f_check_out').value   = checkOut;
        document.getElementById('f_guest_count').value = document.getElementById('guest_count').value;

        basePrice  = data.total_price;
        nightCount = data.nights;

        document.getElementById('f_total_price').value = data.total_price;
        document.getElementById('nights-label').textContent = data.nights + ' malam';
        document.getElementById('price-label').textContent  = 'Rp ' + data.total_price.toLocaleString('id-ID');
        document.getElementById('total-label').textContent  = 'Rp ' + data.total_price.toLocaleString('id-ID');

        document.getElementById('result').style.display       = 'block';
        document.getElementById('booking-form').style.display = 'block';

    } catch(e) {
        alert('Error: ' + e.message);
    }
}

function updateTotal() {
    const extraBed    = document.getElementById('extra_bed').checked;
    const paymentType = document.getElementById('payment_type').value;
    const extraCost   = extraBed ? 150000 * nightCount : 0;
    const total       = basePrice + extraCost;
    const pay         = paymentType === 'dp' ? total * 0.3 : total;

    document.getElementById('f_total_price').value = total;
    document.getElementById('extra-bed-row').style.display = extraBed ? 'flex' : 'none';
    document.getElementById('extra-bed-price').textContent = 'Rp ' + extraCost.toLocaleString('id-ID');
    document.getElementById('total-label').textContent = 'Rp ' + pay.toLocaleString('id-ID');
    document.getElementById('payment-label').textContent = paymentType === 'dp' ? 'Bayar Sekarang (DP 30%)' : 'Total Bayar';
    document.getElementById('dp-info').style.display = paymentType === 'dp' ? 'block' : 'none';
    document.getElementById('remaining-label').textContent = 'Rp ' + (total - pay).toLocaleString('id-ID');
}

function validateForm() {
    if (!document.getElementById('selected_room_id').value) {
        alert('Pilih kamar dulu!');
        return false;
    }
    return true;
}

// Auto-cek kalau ada prefill dari landing page
window.onload = function() {
    const roomTypeId = document.getElementById('room_type_id').value;
    const checkIn    = document.getElementById('check_in').value;
    const checkOut   = document.getElementById('check_out').value;
    if (roomTypeId && checkIn && checkOut) {
        checkAvailability();
    }
}

function selectPayMethod(method) {
    // Set radio value
    document.getElementById('radio-cash').checked = method === 'cash';
    document.getElementById('radio-card').checked = method === 'card';

    // Update border
    document.getElementById('label-cash').style.borderColor = method === 'cash' ? '#d4af7a' : '#ddd';
    document.getElementById('label-card').style.borderColor = method === 'card' ? '#d4af7a' : '#ddd';

    // Tampilkan/sembunyikan pilihan bank
    document.getElementById('bank-options').style.display = method === 'card' ? 'block' : 'none';

    // Reset pilihan bank kalau balik ke cash
    if (method === 'cash') {
        document.getElementById('bank_option').value = '';
        document.querySelectorAll('[id^="bank-"]').forEach(el => {
            el.style.borderColor = '#ddd';
            el.classList.remove('selected-bank');
        });
    }
}

function selectBank(bank) {
    // Reset semua
    document.querySelectorAll('[id^="bank-"]').forEach(el => {
        el.style.borderColor = '#ddd';
        el.style.background = '#fff';
        el.classList.remove('selected-bank');
    });

    // Highlight yang dipilih
    const el = document.getElementById('bank-' + bank);
    el.style.borderColor = '#d4af7a';
    el.style.background = '#faf7f0';
    el.classList.add('selected-bank');

    document.getElementById('bank_option').value = bank;
    document.getElementById('bank-error').style.display = 'none';
}

// Override submitOrder untuk validasi bank
function validateForm() {
    if (!document.getElementById('selected_room_id').value) {
        alert('Pilih kamar dulu!');
        return false;
    }

    // Kalau pilih kartu, harus pilih bank juga
    if (document.getElementById('radio-card').checked) {
        if (!document.getElementById('bank_option').value) {
            document.getElementById('bank-error').style.display = 'block';
            document.getElementById('bank-options').scrollIntoView({ behavior: 'smooth' });
            return false;
        }
    }

    return true;
}

// Init
window.addEventListener('load', function() {
    selectPayMethod('cash');
});
</script>
@endpush
@endsection