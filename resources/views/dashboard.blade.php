@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="label">Kamar Tersedia</div>
        <div class="value">{{ $availableRooms }}<span style="font-size:16px;color:#aaa">/{{ $totalRooms }}</span></div>
        <div class="sub">kamar siap pakai</div>
        <div class="icon"><i class="fas fa-door-open"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Tamu Menginap</div>
        <div class="value">{{ $currentGuests }}</div>
        <div class="sub">check-in hari ini: {{ $todayCheckIn }}</div>
        <div class="icon"><i class="fas fa-user-check"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Pendapatan Bulan Ini</div>
        <div class="value" style="font-size:20px">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</div>
        <div class="sub">dari pembayaran kamar</div>
        <div class="icon"><i class="fas fa-coins"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Order Restoran</div>
        <div class="value">{{ $todayOrders }}</div>
        <div class="sub">Rp {{ number_format($todayOrderRev, 0, ',', '.') }} hari ini</div>
        <div class="icon"><i class="fas fa-utensils"></i></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">

    {{-- Status Kamar --}}
    <div class="card">
        <div class="card-title">Status Kamar</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div style="background:#e8f5e9;border-radius:8px;padding:16px;text-align:center">
                <div style="font-size:28px;color:#2e7d32;font-weight:bold">{{ $roomStats['available'] }}</div>
                <div style="font-size:12px;color:#555;font-family:Arial;margin-top:4px">Tersedia</div>
            </div>
            <div style="background:#fff3e0;border-radius:8px;padding:16px;text-align:center">
                <div style="font-size:28px;color:#e65100;font-weight:bold">{{ $roomStats['occupied'] }}</div>
                <div style="font-size:12px;color:#555;font-family:Arial;margin-top:4px">Terisi</div>
            </div>
            <div style="background:#fff8e1;border-radius:8px;padding:16px;text-align:center">
                <div style="font-size:28px;color:#f57f17;font-weight:bold">{{ $roomStats['dirty'] }}</div>
                <div style="font-size:12px;color:#555;font-family:Arial;margin-top:4px">Kotor</div>
            </div>
            <div style="background:#fce4ec;border-radius:8px;padding:16px;text-align:center">
                <div style="font-size:28px;color:#c62828;font-weight:bold">{{ $roomStats['maintenance'] }}</div>
                <div style="font-size:12px;color:#555;font-family:Arial;margin-top:4px">Maintenance</div>
            </div>
        </div>
    </div>

    {{-- Top Menu --}}
    <div class="card">
        <div class="card-title">Menu Terlaris</div>
        @forelse($topMenus as $i => $menu)
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
            <div style="width:28px;height:28px;background:#d4af7a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-family:Arial;flex-shrink:0">
                {{ $i + 1 }}
            </div>
            <div style="flex:1">
                <div style="font-size:14px;color:#1a1a2e;font-family:Arial">{{ $menu->name }}</div>
                <div style="font-size:12px;color:#aaa;font-family:Arial">{{ $menu->category }}</div>
            </div>
            <div style="font-size:14px;color:#d4af7a;font-family:Arial;font-weight:bold">
                {{ $menu->order_items_sum_quantity ?? 0 }}x
            </div>
        </div>
        @empty
        <div style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:20px">Belum ada data</div>
        @endforelse
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

    {{-- Booking Terbaru --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <div class="card-title" style="margin:0;border:none">Reservasi Terbaru</div>
            <a href="/bookings" style="font-size:12px;color:#d4af7a;font-family:Arial;text-decoration:none">Lihat Semua →</a>
        </div>
        @forelse($recentBookings as $b)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f5f3f0">
            <div>
                <div style="font-size:13px;color:#1a1a2e;font-family:Arial">{{ $b->user->name }}</div>
                <div style="font-size:12px;color:#aaa;font-family:Arial">{{ $b->booking_code }} · Kamar {{ $b->room->room_number }}</div>
            </div>
            @php $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending']; @endphp
            <span class="badge {{ $sBadge[$b->status] }}">{{ ucfirst($b->status) }}</span>
        </div>
        @empty
        <div style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:20px">Belum ada reservasi</div>
        @endforelse
    </div>

    {{-- Order Terbaru --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <div class="card-title" style="margin:0;border:none">Order Terbaru</div>
            <a href="/orders" style="font-size:12px;color:#d4af7a;font-family:Arial;text-decoration:none">Lihat Semua →</a>
        </div>
        @forelse($recentOrders as $o)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f5f3f0">
            <div>
                <div style="font-size:13px;color:#1a1a2e;font-family:Arial">{{ $o->order_code }}</div>
                <div style="font-size:12px;color:#aaa;font-family:Arial">{{ $o->items->count() }} item · {{ ucfirst($o->type) }}</div>
            </div>
            <div style="font-size:13px;color:#d4af7a;font-family:Arial">Rp {{ number_format($o->total_price, 0, ',', '.') }}</div>
        </div>
        @empty
        <div style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:20px">Belum ada order</div>
        @endforelse
    </div>
</div>

@endsection