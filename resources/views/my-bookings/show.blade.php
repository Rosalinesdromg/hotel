@extends('layouts.app')
@section('title', 'Detail Booking')

@section('content')
<div style="max-width:680px">
    <div class="card" style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <div style="font-size:22px;color:#1a1a2e;letter-spacing:2px">{{ $booking->booking_code }}</div>
                <div style="font-size:13px;color:#999;font-family:Arial">{{ $booking->created_at->format('d M Y, H:i') }}</div>
            </div>
            @php $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending'];
            $sLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','checked_in'=>'Check-in','checked_out'=>'Selesai','cancelled'=>'Dibatalkan']; @endphp
            <span class="badge {{ $sBadge[$booking->status] }}" style="font-size:13px;padding:5px 14px">{{ $sLabel[$booking->status] }}</span>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="card">
            <div class="card-title">Info Kamar</div>
            <table style="font-family:Arial;font-size:14px">
                <tr><td style="color:#999;padding:4px 0;width:100px">Tipe</td><td>{{ $booking->room->roomType->name }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">No. Kamar</td><td>{{ $booking->room->room_number }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Check-in</td><td>{{ $booking->check_in->format('d M Y') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Check-out</td><td>{{ $booking->check_out->format('d M Y') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Paket</td><td>{{ ucwords(str_replace('_', ' ', $booking->package)) }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Extra Bed</td><td>{{ $booking->extra_bed ? 'Ya' : 'Tidak' }}</td></tr>
                <tr>
                <td style="color:#999;padding:4px 0">Metode Bayar</td>
                <td>
                    @if($booking->payment_method === 'cash')
                        <i class="fas fa-money-bill-wave" style="color:#d4af7a"></i> Cash
                    @else
                        <i class="fas fa-credit-card" style="color:#d4af7a"></i>
                        Kartu / Transfer
                        @if($booking->bank_option)
                            — <strong>{{ strtoupper($booking->bank_option) }}</strong>
                        @endif
                    @endif
                </td>
            </tr>
            </table>
        </div>
        <div class="card">
            <div class="card-title">Tagihan</div>
            <table style="font-family:Arial;font-size:14px">
                <tr><td style="color:#999;padding:4px 0;width:100px">Total</td><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">DP Dibayar</td><td style="color:#2e7d32">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Sisa</td><td style="color:#e65100">Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Status</td>
                    <td>
                        @php $pBadge = ['unpaid'=>'badge-pending','dp'=>'badge-dp','paid'=>'badge-paid']; @endphp
                        <span class="badge {{ $pBadge[$booking->payment_status] }}">{{ strtoupper($booking->payment_status) }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if($booking->orders->count() > 0)
    <div class="card" style="margin-bottom:16px">
        <div class="card-title">Room Service</div>
        @foreach($booking->orders as $order)
        <div style="margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f5f3f0">
            <div style="font-size:13px;color:#999;font-family:Arial;margin-bottom:6px">{{ $order->order_code }}</div>
            @foreach($order->items as $item)
            <div style="display:flex;justify-content:space-between;font-size:14px;font-family:Arial;margin-bottom:4px">
                <span>{{ $item->menu->name }} x{{ $item->quantity }}</span>
                <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif

    <div style="display:flex;gap:12px">
        <a href="/my-bookings" class="btn btn-outline">← Kembali</a>
        @if($booking->status === 'checked_out')
        <a href="/my-bookings/{{ $booking->id }}/invoice" class="btn btn-gold">
            <i class="fas fa-download"></i> Download Invoice
        </a>
        @endif
    </div>
</div>
@endsection