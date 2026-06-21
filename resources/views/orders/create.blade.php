@extends('layouts.app')
@section('title', 'Order Baru')

@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    {{-- Daftar Menu --}}
    <div class="card">
        <div class="card-title">Pilih Menu</div>

        <div style="margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tipe Order</label>
            <select id="order_type" onchange="toggleBooking()"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                <option value="walkin">Walk-in (Bayar di Kasir)</option>
                <option value="room_service">Room Service (Tagih ke Kamar)</option>
            </select>
        </div>

        <div id="booking_select" style="display:none;margin-bottom:16px">
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Pilih Kamar (Tamu Check-in)</label>
            <select id="booking_id_select"
                style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                <option value="">-- Pilih Kamar --</option>
                @foreach($bookings as $b)
                <option value="{{ $b->id }}">Kamar {{ $b->room->room_number }} — {{ $b->user->name }}</option>
                @endforeach
            </select>
        </div>

        @foreach($menus as $category => $items)
        <div style="margin-bottom:20px">
            <div style="font-size:12px;color:#999;letter-spacing:2px;text-transform:uppercase;font-family:Arial;margin-bottom:10px">
                {{ $category }}
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px">
                @foreach($items as $menu)
                <div onclick="addItem({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})"
                    style="border:1px solid #e8e4de;border-radius:8px;padding:12px;cursor:pointer;transition:all 0.2s"
                    onmouseover="this.style.borderColor='#d4af7a'" onmouseout="this.style.borderColor='#e8e4de'">
                    <div style="font-size:14px;font-family:Arial;color:#1a1a2e;margin-bottom:4px">{{ $menu->name }}</div>
                    <div style="font-size:13px;color:#d4af7a;font-family:Arial">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                    <div style="font-size:11px;color:#aaa;font-family:Arial">Stok: {{ $menu->stock }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Keranjang --}}
    <div class="card" style="position:sticky;top:20px">
        <div class="card-title">Keranjang</div>
        <div id="cart-items" style="min-height:100px;margin-bottom:16px">
            <div id="cart-empty" style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:32px 0">
                Belum ada item
            </div>
        </div>
        <div style="border-top:1px solid #f0ece6;padding-top:12px;margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;font-size:16px;font-family:Arial;font-weight:bold;color:#1a1a2e">
                <span>Total</span>
                <span id="cart-total" style="color:#d4af7a">Rp 0</span>
            </div>
        </div>

        <form method="POST" action="/orders" id="order-form">
            @csrf
            <input type="hidden" name="type" id="f_type" value="walkin">
            <input type="hidden" name="booking_id" id="f_booking_id">
            <div id="payment_method_wrap" style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Metode Bayar</label>
                <select name="payment_method"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                    <option value="cash">Cash</option>
                    <option value="debit">Debit/Transfer</option>
                </select>
            </div>
            <div id="items-container"></div>
            <button type="submit" onclick="return submitOrder()" class="btn btn-gold" style="width:100%;padding:12px;font-size:15px">
                Proses Order
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
let cart = {};

function addItem(id, name, price) {
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { name, price, qty: 1 };
    }
    renderCart();
}

function updateQty(id, delta) {
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const empty     = document.getElementById('cart-empty');
    const keys      = Object.keys(cart);

    if (keys.length === 0) {
        container.innerHTML = '<div id="cart-empty" style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:32px 0">Belum ada item</div>';
        document.getElementById('cart-total').textContent = 'Rp 0';
        return;
    }

    let html = '', total = 0;
    keys.forEach(id => {
        const item = cart[id];
        const sub  = item.price * item.qty;
        total += sub;
        html += `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-family:Arial;font-size:13px">
            <div>
                <div style="color:#1a1a2e">${item.name}</div>
                <div style="color:#aaa">Rp ${item.price.toLocaleString('id-ID')}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <button type="button" onclick="updateQty(${id}, -1)" style="width:24px;height:24px;border:1px solid #ddd;border-radius:4px;background:#fff;cursor:pointer">-</button>
                <span>${item.qty}</span>
                <button type="button" onclick="updateQty(${id}, 1)"  style="width:24px;height:24px;border:1px solid #ddd;border-radius:4px;background:#fff;cursor:pointer">+</button>
            </div>
        </div>`;
    });

    container.innerHTML = html;
    document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function toggleBooking() {
    const type = document.getElementById('order_type').value;
    document.getElementById('booking_select').style.display       = type === 'room_service' ? 'block' : 'none';
    document.getElementById('payment_method_wrap').style.display  = type === 'walkin'       ? 'block' : 'none';
    document.getElementById('f_type').value = type;
}

function submitOrder() {
    if (Object.keys(cart).length === 0) {
        alert('Tambahkan item dulu!');
        return false;
    }

    const type = document.getElementById('order_type').value;
    if (type === 'room_service') {
        const bookingId = document.getElementById('booking_id_select').value;
        if (!bookingId) { alert('Pilih kamar dulu!'); return false; }
        document.getElementById('f_booking_id').value = bookingId;
    }

    // Masukkan items ke form
    const container = document.getElementById('items-container');
    container.innerHTML = '';
    let i = 0;
    Object.keys(cart).forEach(id => {
        container.innerHTML += `
            <input type="hidden" name="items[${i}][menu_id]"  value="${id}">
            <input type="hidden" name="items[${i}][quantity]" value="${cart[id].qty}">`;
        i++;
    });
    return true;
}
</script>
@endpush
@endsection