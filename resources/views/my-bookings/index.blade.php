@extends('layouts.app')
@section('title', 'Booking Saya')

@section('content')
<div class="card">
    <div class="card-title">Riwayat Booking Saya</div>
    @forelse($bookings as $b)
    <div style="border:1px solid #e8e4de;border-radius:8px;padding:20px;margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
            <div>
                <div style="font-size:18px;color:#1a1a2e;letter-spacing:1px;margin-bottom:4px">
                    {{ $b->booking_code }}
                </div>
                <div style="font-size:14px;color:#555;font-family:Arial">
                    {{ $b->room->roomType->name }} — Kamar {{ $b->room->room_number }}
                </div>
                <div style="font-size:13px;color:#aaa;font-family:Arial;margin-top:4px">
                    {{ $b->check_in->format('d M Y') }} → {{ $b->check_out->format('d M Y') }}
                    · {{ $b->check_in->diffInDays($b->check_out) }} malam
                </div>
            </div>
            <div style="text-align:right">
                @php
                    $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending'];
                    $sLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','checked_in'=>'Check-in','checked_out'=>'Selesai','cancelled'=>'Dibatalkan'];
                @endphp
                <span class="badge {{ $sBadge[$b->status] }}" style="font-size:12px">{{ $sLabel[$b->status] }}</span>
                <div style="font-size:16px;color:#d4af7a;font-family:Arial;font-weight:bold;margin-top:8px">
                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                </div>
                <div style="font-size:12px;color:#aaa;font-family:Arial">
                    @if($b->payment_status === 'dp') DP 30% dibayar
                    @elseif($b->payment_status === 'paid') Lunas
                    @else Belum bayar @endif
                </div>
            </div>
        </div>
       <div style="display:flex;gap:10px;margin-top:16px;padding-top:16px;border-top:1px solid #f5f3f0;flex-wrap:wrap">
    <a href="/my-bookings/{{ $b->id }}" class="btn btn-outline" style="font-size:13px;padding:7px 16px">Detail</a>

    @if($b->status === 'checked_out')
        <a href="/my-bookings/{{ $b->id }}/invoice" class="btn btn-gold" style="font-size:13px;padding:7px 16px">
            <i class="fas fa-download"></i> Invoice
        </a>
        @php $hasReview = \App\Models\Review::where('booking_id', $b->id)->exists(); @endphp
        @if(!$hasReview)
        <a href="/reviews/{{ $b->id }}/create" class="btn btn-outline" style="font-size:13px;padding:7px 16px">
            <i class="fas fa-star"></i> Tulis Review
        </a>
        @else
        <span style="font-size:13px;color:#aaa;font-family:Arial;padding:7px 0">
            <i class="fas fa-check"></i> Sudah direview
        </span>
        @endif
    @endif

    {{-- TAMBAHAN REFUND DI SINI --}}
    @if(in_array($b->status, ['confirmed', 'pending']) && !$b->refund_status)
    <a href="/refunds/{{ $b->id }}/create" class="btn btn-outline"
        style="font-size:13px;padding:7px 16px;color:#c62828;border-color:#c62828">
        <i class="fas fa-times-circle"></i> Batalkan
    </a>
    @elseif($b->refund_status === 'requested')
    <span style="font-size:13px;color:#f57f17;font-family:Arial;padding:7px 0">
        <i class="fas fa-clock"></i> Refund diproses
    </span>
    @elseif($b->refund_status === 'approved')
    <span style="font-size:13px;color:#2e7d32;font-family:Arial;padding:7px 0">
        <i class="fas fa-check"></i> Refund disetujui
    </span>
    @endif

</div>
    </div>
    @empty
    <div style="text-align:center;padding:48px 0;color:#aaa;font-family:Arial">
        <i class="fas fa-calendar-times" style="font-size:40px;margin-bottom:16px;display:block"></i>
        Belum ada booking
    </div>
    @endforelse
@endsection