@extends('layouts.app')
@section('title', 'Detail Order')

@section('content')
<div style="max-width:600px">
    <div class="card" style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div>
                <div style="font-size:22px;color:#1a1a2e;letter-spacing:2px">{{ $order->order_code }}</div>
                <div style="font-size:13px;color:#999;font-family:Arial">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
            <span class="badge {{ $order->status === 'paid' ? 'badge-paid' : 'badge-pending' }}" style="font-size:13px;padding:5px 14px">
                {{ strtoupper($order->status) }}
            </span>
        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="card-title">Item Pesanan</div>
        <table>
            <thead>
                <tr><th>Menu</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->menu->name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="text-align:right;font-size:16px;font-family:Arial;font-weight:bold;color:#d4af7a;margin-top:12px;padding-top:12px;border-top:1px solid #f0ece6">
            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <a href="/orders" class="btn btn-outline">← Kembali</a>
        @if($order->status === 'paid')
            @role('manager|ceo')
            <form method="POST" action="/orders/{{ $order->id }}/void" onsubmit="return confirm('Void order ini?')">
                @csrf
                <button class="btn btn-danger">Void Order</button>
            </form>
            @endrole
        @endif
    </div>
</div>
@endsection