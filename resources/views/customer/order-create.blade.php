@extends('layouts.app')
@section('title', 'Pesan Makanan')

@section('content')

@if($activeBooking)
<div style="background:#e8f5e9;border:1px solid #a5d6a7;border-radius:8px;padding:14px 18px;margin-bottom:20px;font-family:Arial;font-size:14px;display:flex;align-items:center;gap:12px">
    <i class="fas fa-concierge-bell" style="color:#2e7d32;font-size:20px"></i>
    <div>
        <div style="color:#2e7d32;font-weight:bold">Kamu sedang menginap di Kamar {{ $activeBooking->room->room_number }}</div>
        <div style="color:#555;font-size:13px;margin-top:2px">Pesanan akan ditagihkan ke kamar dan dibayar saat check-out.</div>
    </div>
</div>
@else
<div style="background:#fff3e0;border:1px solid #ffcc80;border-radius:8px;padding:14px 18px;margin-bottom:20px;font-family:Arial;font-size:14px;display:flex;align-items:center;gap:12px">
    <i class="fas fa-info-circle" style="color:#e65100;font-size:20px"></i>
    <div>
        <div style="color:#e65100;font-weight:bold">Kamu tidak sedang menginap</div>
        <div style="color:#555;font-size:13px;margin-top:2px">Pesanan akan disiapkan. Silakan datang ke restoran dan tunjukkan kode pesanan ke kasir untuk pembayaran.</div>
    </div>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">

    {{-- Menu --}}
    <div class="card">
        <div class="card-title">Pilih Menu</div>

        @foreach($menus as $category => $items)
        <div style="margin-bottom:24px">
            <div style="font-size:11px;color:#999;letter-spacing:2px;text-transform:uppercase;font-family:Arial;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #f0ece6">
                {{ $category }}
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px">
                @foreach($items as $menu)
                <div onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                    style="border:1px solid #e8e4de;border-radius:8px;padding:16px;cursor:pointer;transition:all 0.2s;background:#fff"
                    onmouseover="this.style.borderColor='#d4af7a';this.style.background='#faf9f7'"
                    onmouseout="this.style.borderColor='#e8e4de';this.style.background='#fff'">
                    <div style="width:40px;height:40px;background:#f0ece6;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:10px">
                        @if($category === 'Makanan') <i class="fas fa-utensils" style="color:#d4af7a"></i>
                        @elseif($category === 'Minuman') <i class="fas fa-coffee" style="color:#d4af7a"></i>
                        @elseif($category === 'Dessert') <i class="fas fa-ice-cream" style="color:#d4af7a"></i>
                        @else <i class="fas fa-cookie" style="color:#d4af7a"></i>
                        @endif
                    </div>
                    <div style="font-size:14px;color:#1a1a2e;font-family:Arial;margin-bottom:4px">{{ $menu->name }}</div>
                    <div style="font-size:13px;color:#d4af7a;font-family:Arial">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                    <div style="font-size:11px;color:#aaa;font-family:Arial;margin-top:4px">Stok: {{ $menu->stock }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Keranjang --}}
    <div style="position:sticky;top:20px">
        <div class="card">
            <div class="card-title">
                <i class="fas fa-shopping-cart" style="color:#d4af7a"></i> Pesanan Saya
            </div>

            <div id="cart-empty" style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:24px 0">
                <i class="fas fa-utensils" style="font-size:24px;margin-bottom:8px;display:block;color:#e8e4de"></i>
                Belum ada pesanan
            </div>

            <div id="cart-items"></div>

            <div id="cart-footer" style="display:none">
                <div style="border-top:1px solid #f0ece6;padding-top:12px;margin-top:8px">
                    <div style="display:flex;justify-content:space-between;font-size:16px;font-family:Arial;font-weight:bold;color:#1a1a2e;margin-bottom:16px">
                        <span>Total</span>
                        <span id="cart-total" style="color:#d4af7a">Rp 0</span>
                    </div>

                    @if($activeBooking)
                    <div style="background:#f9f7f4;border-radius:6px;padding:10px;margin-bottom:16px;font-size:12px;font-family:Arial;color:#666;text-align:center">
                        <i class="fas fa-info-circle" style="color:#d4af7a"></i>
                        Ditagih ke Kamar {{ $activeBooking->room->room_number }} saat check-out
                    </div>
                    @else
                    <div style="background:#fff3e0;border-radius:6px;padding:10px;margin-bottom:16px;font-size:12px;font-family:Arial;color:#e65100;text-align:center">
                        <i class="fas fa-store"></i>
                        Bayar langsung di kasir restoran
                    </div>
                    @endif

                    <form method="POST" action="/customer/orders" id="order-form">
                        @csrf
                        <div id="items-container"></div>
                        <button type="submit" onclick="return submitOrder()"
                            class="btn btn-gold" style="width:100%;padding:12px;font-size:15px">
                            <i class="fas fa-paper-plane"></i>
                            {{ $activeBooking ? 'Kirim ke Kamar' : 'Buat Pesanan' }}
                        </button>
                    </form>

                    <button onclick="clearCart()"
                        style="width:100%;padding:8px;background:transparent;border:none;color:#aaa;font-size:13px;font-family:Arial;cursor:pointer;margin-top:8px">
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let cart = {};

function addToCart(id, name, price) {
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

function clearCart() {
    cart = {};
    renderCart();
}

function renderCart() {
    const empty  = document.getElementById('cart-empty');
    const items  = document.getElementById('cart-items');
    const footer = document.getElementById('cart-footer');
    const keys   = Object.keys(cart);

    if (keys.length === 0) {
        empty.style.display  = 'block';
        items.innerHTML      = '';
        footer.style.display = 'none';
        return;
    }

    empty.style.display  = 'none';
    footer.style.display = 'block';

    let html = '', total = 0;
    keys.forEach(id => {
        const item = cart[id];
        const sub  = item.price * item.qty;
        total += sub;
        html += `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f9f7f4">
            <div style="flex:1">
                <div style="font-size:14px;color:#1a1a2e;font-family:Arial">${item.name}</div>
                <div style="font-size:12px;color:#aaa;font-family:Arial">Rp ${item.price.toLocaleString('id-ID')} / item</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <button type="button" onclick="updateQty(${id}, -1)"
                    style="width:26px;height:26px;border:1px solid #ddd;border-radius:6px;background:#fff;cursor:pointer;font-size:16px;line-height:1;display:flex;align-items:center;justify-content:center">−</button>
                <span style="font-size:14px;font-family:Arial;min-width:20px;text-align:center">${item.qty}</span>
                <button type="button" onclick="updateQty(${id}, 1)"
                    style="width:26px;height:26px;border:1px solid #ddd;border-radius:6px;background:#fff;cursor:pointer;font-size:16px;line-height:1;display:flex;align-items:center;justify-content:center">+</button>
            </div>
        </div>`;
    });

    items.innerHTML = html;
    document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function submitOrder() {
    if (Object.keys(cart).length === 0) {
        alert('Tambahkan menu dulu!');
        return false;
    }

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