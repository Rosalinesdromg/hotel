@extends('layouts.app')
@section('title', 'Detail Booking')

@section('content')
<div style="max-width:700px">

    {{-- Header --}}
    <div class="card" style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div>
                <div style="font-size:22px;color:#1a1a2e;letter-spacing:2px;margin-bottom:4px">
                    {{ $booking->booking_code }}
                </div>
                <div style="font-size:13px;color:#999;font-family:Arial">
                    Dibuat {{ $booking->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            <div style="text-align:right">
                @php
                    $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending'];
                    $sLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','checked_in'=>'Check-in','checked_out'=>'Checked Out','cancelled'=>'Cancelled'];
                @endphp
                <span class="badge {{ $sBadge[$booking->status] }}" style="font-size:13px;padding:5px 14px">
                    {{ $sLabel[$booking->status] }}
                </span>
            </div>
        </div>
    </div>

    {{-- Info Tamu & Kamar --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="card">
            <div class="card-title">Info Tamu</div>
            <table style="font-family:Arial;font-size:14px">
                <tr><td style="color:#999;padding:4px 0;width:110px">Nama</td><td>{{ $booking->user->name }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Email</td><td>{{ $booking->user->email }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Telepon</td><td>{{ $booking->user->phone ?? '-' }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Tamu</td><td>{{ $booking->guest_count }} orang</td></tr>
            </table>
        </div>
        <div class="card">
            <div class="card-title">Info Kamar</div>
            <table style="font-family:Arial;font-size:14px">
                <tr><td style="color:#999;padding:4px 0;width:110px">Kamar</td><td>{{ $booking->room->room_number }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Tipe</td><td>{{ $booking->room->roomType->name }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Check-in</td><td>{{ $booking->check_in->format('d M Y') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Check-out</td><td>{{ $booking->check_out->format('d M Y') }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Paket</td><td>{{ ucwords(str_replace('_', ' ', $booking->package)) }}</td></tr>
                <tr><td style="color:#999;padding:4px 0">Extra Bed</td><td>{{ $booking->extra_bed ? 'Ya' : 'Tidak' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Tagihan --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-title">Rincian Tagihan</div>
        <div style="font-family:Arial">
            <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;padding:8px 0;border-bottom:1px solid #f5f3f0">
                <span>Biaya Kamar</span>
                <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>

            @if($booking->orders->count() > 0)
            <div style="padding:8px 0;border-bottom:1px solid #f5f3f0">
                <div style="font-size:13px;color:#999;margin-bottom:8px">Room Service</div>
                @foreach($booking->orders as $order)
                <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;margin-bottom:4px">
                    <span>Order #{{ $order->order_code }}</span>
                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;padding:8px 0;border-bottom:1px solid #f5f3f0">
                <span>DP Dibayar</span>
                <span style="color:#2e7d32">- Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:17px;color:#1a1a2e;padding:12px 0 0">
                <strong>Total Tagihan</strong>
                <strong style="color:#d4af7a">Rp {{ number_format($booking->grandTotal(), 0, ',', '.') }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;color:#999;padding:4px 0">
                <span>Sisa Pembayaran</span>
                <span>Rp {{ number_format($booking->grandTotal() - $booking->dp_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Aksi --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="/bookings" class="btn btn-outline">← Kembali</a>

        @if($booking->status === 'confirmed')
        <form method="POST" action="/bookings/{{ $booking->id }}/check-in">
            @csrf
            <button class="btn btn-gold">Check-in Sekarang</button>
        </form>
        @endif

        @if($booking->status === 'checked_in')
        <form method="POST" action="/bookings/{{ $booking->id }}/check-out">
            @csrf
            <button class="btn btn-danger">Check-out & Selesaikan</button>
        </form>
        @endif
    </div>

</div>
@endsection