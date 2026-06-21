@extends('layouts.app')
@section('title', 'Kasir')

@push('styles')
<style>
    .tab-btn {
        padding: 10px 24px;
        border: none;
        border-bottom: 3px solid transparent;
        background: transparent;
        font-size: 14px;
        font-family: Arial;
        color: #aaa;
        cursor: pointer;
        transition: all 0.2s;
    }
    .tab-btn.active {
        color: #1a1a2e;
        border-bottom-color: #d4af7a;
        font-weight: bold;
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .room-bill-card {
        border: 1px solid #e8e4de;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .room-bill-card:hover { border-color: #d4af7a; }
    .menu-item-card {
        border: 1px solid #e8e4de;
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }
    .menu-item-card:hover { border-color: #d4af7a; background: #faf9f7; }
</style>
@endpush

@section('content')

{{-- Stat Cards --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="label">Order Hari Ini</div>
        <div class="value">{{ $todayOrders }}</div>
        <div class="sub">total transaksi</div>
        <div class="icon"><i class="fas fa-receipt"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Pendapatan Hari Ini</div>
        <div class="value" style="font-size:18px">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="sub">dari restoran</div>
        <div class="icon"><i class="fas fa-coins"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Walk-in</div>
        <div class="value">{{ $walkinCount }}</div>
        <div class="sub">tamu langsung</div>
        <div class="icon"><i class="fas fa-walking"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Room Service</div>
        <div class="value">{{ $roomSvcCount }}</div>
        <div class="sub">order ke kamar</div>
        <div class="icon"><i class="fas fa-concierge-bell"></i></div>
    </div>
</div>

{{-- Tabs --}}
<div class="card">
    <div style="border-bottom:1px solid #e8e4de;margin-bottom:24px;display:flex;gap:4px">
        <button class="tab-btn active" onclick="switchTab('restoran', this)">
            <i class="fas fa-utensils"></i> Kasir Restoran
        </button>
        <button class="tab-btn" onclick="switchTab('kamar', this)">
            <i class="fas fa-door-open"></i> Tagihan Kamar
        </button>
        <button class="tab-btn" onclick="switchTab('riwayat', this)">
            <i class="fas fa-history"></i> Riwayat Hari Ini
        </button>
    </div>

    {{-- TAB 1: KASIR RESTORAN --}}
    <div id="tab-restoran" class="tab-content active">
        <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">

            {{-- Menu --}}
            <div>
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tipe Order</label>
                    <div style="display:flex;gap:8px">
                        <button type="button" onclick="setType('walkin', this)"
                            class="btn btn-gold" id="btn-walkin"
                            style="font-size:13px;padding:8px 20px">
                            <i class="fas fa-walking"></i> Walk-in
                        </button>
                        <button type="button" onclick="setType('room_service', this)"
                            class="btn btn-outline" id="btn-roomsvc"
                            style="font-size:13px;padding:8px 20px">
                            <i class="fas fa-concierge-bell"></i> Room Service
                        </button>
                    </div>
                </div>

                {{-- Pilih Kamar (room service) --}}
                <div id="room-select-wrap" style="display:none;margin-bottom:16px">
                    <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">
                        Pilih Kamar <span style="color:#d4af7a">(Tamu Check-in)</span>
                    </label>
                    <select id="booking_id_select"
                        style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($activeGuests as $b)
                        <option value="{{ $b->id }}">
                            Kamar {{ $b->room->room_number }} ({{ $b->room->roomType->name }}) — {{ $b->user->name }}
                        </option>
                        @endforeach
                    </select>
                    @if($activeGuests->isEmpty())
                    <div style="font-size:12px;color:#aaa;font-family:Arial;margin-top:4px">
                        <i class="fas fa-info-circle"></i> Tidak ada tamu yang sedang check-in
                    </div>
                    @endif
                </div>

                {{-- Daftar Menu --}}
                @foreach($menus as $category => $items)
                <div style="margin-bottom:20px">
                    <div style="font-size:11px;color:#999;letter-spacing:2px;text-transform:uppercase;font-family:Arial;margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid #f0ece6">
                        {{ $category }}
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px">
                        @foreach($items as $menu)
                        <div class="menu-item-card" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})">
                            <div style="font-size:14px;color:#1a1a2e;font-family:Arial;margin-bottom:4px">{{ $menu->name }}</div>
                            <div style="font-size:13px;color:#d4af7a;font-family:Arial">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                            <div style="font-size:11px;color:#aaa;font-family:Arial;margin-top:2px">Stok: {{ $menu->stock }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Keranjang --}}
            <div style="position:sticky;top:20px;border:1px solid #e8e4de;border-radius:8px;padding:20px">
                <div style="font-size:15px;color:#1a1a2e;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f0ece6">
                    <i class="fas fa-shopping-cart" style="color:#d4af7a"></i> Keranjang
                </div>

                <div id="cart-empty" style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:24px 0">
                    Belum ada item
                </div>
                <div id="cart-items"></div>

                <div id="cart-total-wrap" style="display:none;border-top:1px solid #f0ece6;padding-top:12px;margin-top:8px">
                    <div style="display:flex;justify-content:space-between;font-size:16px;font-family:Arial;font-weight:bold;color:#1a1a2e;margin-bottom:16px">
                        <span>Total</span>
                        <span id="cart-total" style="color:#d4af7a">Rp 0</span>
                    </div>

                    <div id="payment-method-wrap" style="margin-bottom:12px">
                        <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-family:Arial">Metode Bayar</label>
                        <select id="payment_method"
                            style="width:100%;padding:9px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;font-family:Arial;background:#fff">
                            <option value="cash">Cash</option>
                            <option value="debit">Debit / Transfer</option>
                        </select>
                    </div>

                    <form method="POST" action="/orders" id="order-form">
                        @csrf
                        <input type="hidden" name="type"           id="f_type" value="walkin">
                        <input type="hidden" name="booking_id"     id="f_booking_id">
                        <input type="hidden" name="payment_method" id="f_payment_method" value="cash">
                        <div id="items-container"></div>
                        <button type="submit" onclick="return submitOrder()"
                            class="btn btn-gold" style="width:100%;padding:12px;font-size:14px">
                            <i class="fas fa-check"></i> Proses Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: TAGIHAN KAMAR --}}
    <div id="tab-kamar" class="tab-content">
        <div style="font-size:13px;color:#aaa;font-family:Arial;margin-bottom:16px">
            Daftar tamu yang sedang menginap beserta tagihan room service hari ini.
        </div>

        @forelse($activeGuests as $b)
        <div class="room-bill-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
                <div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px">
                        <div style="width:40px;height:40px;background:#1a1a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#d4af7a;font-size:14px;font-family:Arial;font-weight:bold;flex-shrink:0">
                            {{ $b->room->room_number }}
                        </div>
                        <div>
                            <div style="font-size:15px;color:#1a1a2e">{{ $b->user->name }}</div>
                            <div style="font-size:12px;color:#aaa;font-family:Arial">{{ $b->room->roomType->name }} · Check-out: {{ $b->check_out->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:12px;color:#aaa;font-family:Arial;margin-bottom:2px">Biaya Kamar</div>
                    <div style="font-size:15px;color:#1a1a2e">Rp {{ number_format($b->total_price, 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Room Service Hari Ini --}}
            @if($b->orders->count() > 0)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f5f3f0">
                <div style="font-size:12px;color:#d4af7a;font-family:Arial;margin-bottom:8px;letter-spacing:1px;text-transform:uppercase">
                    Room Service Hari Ini
                </div>
                @foreach($b->orders as $order)
                <div style="display:flex;justify-content:space-between;font-size:13px;font-family:Arial;color:#555;margin-bottom:4px">
                    <span>
                        {{ $order->items->map(fn($i) => $i->menu->name . ' x' . $i->quantity)->join(', ') }}
                    </span>
                    <span style="color:#d4af7a">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div style="margin-top:10px;font-size:12px;color:#aaa;font-family:Arial">
                Belum ada room service hari ini
            </div>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:48px 0;color:#aaa;font-family:Arial">
            <i class="fas fa-door-open" style="font-size:40px;margin-bottom:16px;display:block;color:#e8e4de"></i>
            Tidak ada tamu yang sedang check-in
        </div>
        @endforelse
    </div>

    {{-- TAB 3: RIWAYAT HARI INI --}}
<div id="tab-riwayat" class="tab-content">

    {{-- Order Pending dari Customer --}}
    @if(isset($pendingOrders) && $pendingOrders->count() > 0)
    <div style="background:#fff3e0;border:1px solid #ffcc80;border-radius:8px;padding:16px;margin-bottom:20px">
        <div style="font-size:13px;color:#e65100;font-family:Arial;font-weight:bold;margin-bottom:12px">
            <i class="fas fa-clock"></i> Order Menunggu Konfirmasi ({{ $pendingOrders->count() }})
        </div>
        @foreach($pendingOrders as $po)
        <div style="background:#fff;border:1px solid #ffe0b2;border-radius:6px;padding:14px;margin-bottom:10px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
                <div>
                    <div style="font-size:14px;color:#1a1a2e;font-family:Arial;font-weight:bold">{{ $po->order_code }}</div>
                    <div style="font-size:13px;color:#555;font-family:Arial;margin-top:4px">
                        {{ $po->items->map(fn($i) => $i->menu->name . ' x' . $i->quantity)->join(', ') }}
                    </div>
                    <div style="font-size:12px;color:#aaa;font-family:Arial;margin-top:4px">
                        Dipesan oleh: {{ $po->user->name ?? 'Customer' }} · {{ $po->created_at->format('H:i') }}
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:16px;color:#d4af7a;font-family:Arial;font-weight:bold;margin-bottom:8px">
                        Rp {{ number_format($po->total_price, 0, ',', '.') }}
                    </div>
                    <form method="POST" action="/orders/{{ $po->id }}/confirm"
                        style="display:flex;gap:8px;align-items:center">
                        @csrf
                        <select name="payment_method"
                            style="padding:6px 10px;border:1px solid #ddd;border-radius:6px;font-size:12px;font-family:Arial;background:#fff">
                            <option value="cash">Cash</option>
                            <option value="debit">Debit</option>
                        </select>
                        <button type="submit" class="btn btn-gold" style="padding:6px 16px;font-size:12px;white-space:nowrap">
                            <i class="fas fa-check"></i> Konfirmasi Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Riwayat Order Hari Ini --}}
    <div style="font-size:13px;color:#aaa;font-family:Arial;margin-bottom:12px">Order hari ini</div>
    <div class="table-responsive">
    <table id="kasir-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Kamar / Tamu</th>
                <th>Item</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $o)
            <tr>
                <td><strong style="font-size:12px">{{ $o->order_code }}</strong></td>
                <td>
                    @if($o->type === 'room_service')
                        <span class="badge badge-dp">Room Svc</span>
                    @else
                        <span class="badge badge-available">Walk-in</span>
                    @endif
                </td>
                <td style="font-size:13px">
                    @if($o->booking)
                        Kamar {{ $o->booking->room->room_number }}
                    @else
                        Tamu Umum
                    @endif
                </td>
                <td style="font-size:12px;color:#666;max-width:200px">
                    {{ $o->items->map(fn($i) => $i->menu->name . ' x' . $i->quantity)->join(', ') }}
                </td>
                <td>Rp {{ number_format($o->total_price, 0, ',', '.') }}</td>
                <td style="font-size:12px">
                    @if($o->payment_method === 'cash')
                        <span style="color:#2e7d32">CASH</span>
                    @elseif($o->payment_method === 'debit')
                        <span style="color:#1565c0">DEBIT</span>
                    @elseif($o->payment_method === 'charge_to_room')
                        <span style="color:#d4af7a">KAMAR</span>
                    @else
                        <span style="color:#aaa">-</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#aaa">{{ $o->created_at->format('H:i') }}</td>
                <td>
                    @if($o->status === 'paid')
                        @role('manager|ceo')
                        <form method="POST" action="/orders/{{ $o->id }}/void"
                            onsubmit="return confirm('Void order ini?')">
                            @csrf
                            <button class="btn btn-danger" style="padding:3px 10px;font-size:11px">Void</button>
                        </form>
                        @endrole
                    @elseif($o->status === 'void')
                        <span class="badge badge-pending">Void</span>
                    @elseif($o->status === 'pending')
                        <span class="badge badge-pending">Pending</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:#aaa;padding:32px">Belum ada order hari ini</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@push('scripts')
<script>
let cart = {};
let orderType = 'walkin';

function switchTab(tab, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    el.classList.add('active');

      // Init DataTables saat tab riwayat dibuka
    if (tab === 'riwayat' && !$.fn.dataTable.isDataTable('#kasir-table')) {
        $('#kasir-table').DataTable({
            responsive: true,
            order: [[6, 'desc']],
            language: {
                search:      "Cari:",
                lengthMenu:  "Tampilkan _MENU_ data",
                info:        "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty:   "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                paginate: { next: "→", previous: "←" }
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    }
}

function setType(type, el) {
    orderType = type;
    document.getElementById('f_type').value = type;
    document.getElementById('room-select-wrap').style.display = type === 'room_service' ? 'block' : 'none';
    document.getElementById('payment-method-wrap').style.display = type === 'walkin' ? 'block' : 'none';

    document.getElementById('btn-walkin').className  = type === 'walkin'        ? 'btn btn-gold'    : 'btn btn-outline';
    document.getElementById('btn-roomsvc').className = type === 'room_service'  ? 'btn btn-gold'    : 'btn btn-outline';
    document.getElementById('btn-walkin').style.fontSize  = '13px';
    document.getElementById('btn-roomsvc').style.fontSize = '13px';
    document.getElementById('btn-walkin').style.padding   = '8px 20px';
    document.getElementById('btn-roomsvc').style.padding  = '8px 20px';
}

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

function renderCart() {
    const empty     = document.getElementById('cart-empty');
    const itemsDiv  = document.getElementById('cart-items');
    const totalWrap = document.getElementById('cart-total-wrap');
    const keys      = Object.keys(cart);

    if (keys.length === 0) {
        empty.style.display     = 'block';
        itemsDiv.innerHTML      = '';
        totalWrap.style.display = 'none';
        return;
    }

    empty.style.display     = 'none';
    totalWrap.style.display = 'block';

    let html = '', total = 0;
    keys.forEach(id => {
        const item = cart[id];
        const sub  = item.price * item.qty;
        total += sub;
        html += `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-family:Arial;font-size:13px">
            <div style="flex:1">
                <div style="color:#1a1a2e">${item.name}</div>
                <div style="color:#aaa;font-size:12px">Rp ${item.price.toLocaleString('id-ID')}</div>
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <button type="button" onclick="updateQty(${id}, -1)"
                    style="width:22px;height:22px;border:1px solid #ddd;border-radius:4px;background:#fff;cursor:pointer;font-size:14px;line-height:1">-</button>
                <span style="min-width:16px;text-align:center">${item.qty}</span>
                <button type="button" onclick="updateQty(${id}, 1)"
                    style="width:22px;height:22px;border:1px solid #ddd;border-radius:4px;background:#fff;cursor:pointer;font-size:14px;line-height:1">+</button>
            </div>
        </div>`;
    });

    itemsDiv.innerHTML = html;
    document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Sync payment method
document.getElementById('payment_method').addEventListener('change', function() {
    document.getElementById('f_payment_method').value = this.value;
});

function submitOrder() {
    if (Object.keys(cart).length === 0) {
        alert('Tambahkan item dulu!');
        return false;
    }
    if (orderType === 'room_service') {
        const bookingId = document.getElementById('booking_id_select').value;
        if (!bookingId) {
            alert('Pilih kamar dulu!');
            return false;
        }
        document.getElementById('f_booking_id').value = bookingId;
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