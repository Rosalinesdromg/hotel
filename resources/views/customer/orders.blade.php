@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Riwayat Pesanan Restoran</div>
        <a href="/customer/orders/create" class="btn btn-gold">+ Pesan Sekarang</a>
    </div>

    @forelse($orders as $order)
    <div style="border:1px solid #e8e4de;border-radius:8px;padding:20px;margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
            <div>
                <div style="font-size:16px;color:#1a1a2e;letter-spacing:1px;margin-bottom:4px">
                    {{ $order->order_code }}
                </div>
                <div style="font-size:13px;color:#aaa;font-family:Arial">
                    {{ $order->created_at->format('d M Y, H:i') }}
                </div>
                <div style="margin-top:8px;font-size:13px;color:#555;font-family:Arial">
                    {{ $order->items->map(fn($i) => $i->menu->name . ' x' . $i->quantity)->join(', ') }}
                </div>
            </div>
            <div style="text-align:right">
                @if($order->type === 'room_service')
                    <span class="badge badge-dp" style="margin-bottom:8px;display:inline-block">Room Service</span>
                @else
                    <span class="badge badge-available" style="margin-bottom:8px;display:inline-block">Walk-in</span>
                @endif
                <div style="font-size:16px;color:#d4af7a;font-family:Arial;font-weight:bold">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
                <div style="font-size:12px;margin-top:4px">
                    @if($order->status === 'paid')
                        <span class="badge badge-paid">Selesai</span>
                    @elseif($order->status === 'pending')
                        <span class="badge badge-pending">Menunggu Kasir</span>
                    @else
                        <span class="badge badge-pending">Void</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:48px 0;color:#aaa;font-family:Arial">
        <i class="fas fa-utensils" style="font-size:40px;margin-bottom:16px;display:block;color:#e8e4de"></i>
        Belum ada pesanan
        <div style="margin-top:12px">
            <a href="/customer/orders/create" class="btn btn-gold" style="font-size:13px">Pesan Sekarang</a>
        </div>
    </div>
    @endforelse
</div>
@endsection