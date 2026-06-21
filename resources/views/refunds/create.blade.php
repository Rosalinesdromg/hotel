@extends('layouts.app')
@section('title', 'Ajukan Refund')

@section('content')
<div style="max-width:600px">

    {{-- Info Kebijakan Refund --}}
    <div class="card" style="margin-bottom:16px;border-left:4px solid #d4af7a">
        <div class="card-title">Kebijakan Pembatalan</div>
        <div style="font-family:Arial;font-size:14px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <div style="background:#e8f5e9;border-radius:6px;padding:12px">
                    <div style="color:#2e7d32;font-weight:bold;margin-bottom:4px">H-7 atau lebih</div>
                    <div style="color:#555;font-size:13px">Refund 100%</div>
                </div>
                <div style="background:#e3f2fd;border-radius:6px;padding:12px">
                    <div style="color:#1565c0;font-weight:bold;margin-bottom:4px">H-3 sampai H-6</div>
                    <div style="color:#555;font-size:13px">Refund 75%</div>
                </div>
                <div style="background:#fff3e0;border-radius:6px;padding:12px">
                    <div style="color:#e65100;font-weight:bold;margin-bottom:4px">H-1 sampai H-2</div>
                    <div style="color:#555;font-size:13px">Refund 50%</div>
                </div>
                <div style="background:#ffebee;border-radius:6px;padding:12px">
                    <div style="color:#c62828;font-weight:bold;margin-bottom:4px">H-0</div>
                    <div style="color:#555;font-size:13px">Tidak bisa refund</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Booking --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-title">Detail Booking</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-family:Arial;font-size:14px">
            <div>
                <div style="color:#999;font-size:12px;margin-bottom:2px">Kode Booking</div>
                <div style="color:#1a1a2e">{{ $booking->booking_code }}</div>
            </div>
            <div>
                <div style="color:#999;font-size:12px;margin-bottom:2px">Kamar</div>
                <div style="color:#1a1a2e">{{ $booking->room->room_number }} — {{ $booking->room->roomType->name }}</div>
            </div>
            <div>
                <div style="color:#999;font-size:12px;margin-bottom:2px">Check-in</div>
                <div style="color:#1a1a2e">{{ $booking->check_in->format('d M Y') }}</div>
            </div>
            <div>
                <div style="color:#999;font-size:12px;margin-bottom:2px">DP Dibayar</div>
                <div style="color:#1a1a2e">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Kalkulasi refund --}}
        @if($refundInfo['percent'] > 0)
        <div style="background:#f9f7f4;border-radius:8px;padding:16px;margin-top:16px;font-family:Arial">
            <div style="font-size:12px;color:#999;margin-bottom:8px;letter-spacing:1px;text-transform:uppercase">Estimasi Refund ({{ $refundInfo['label'] }})</div>
            <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;margin-bottom:4px">
                <span>DP Dibayar</span>
                <span>Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;color:#c62828;margin-bottom:4px">
                <span>Biaya Pembatalan ({{ $refundInfo['fee_percent'] }}%)</span>
                <span>- Rp {{ number_format($booking->dp_amount * $refundInfo['fee_percent'] / 100, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:16px;color:#2e7d32;font-weight:bold;border-top:1px solid #e8e4de;padding-top:8px;margin-top:8px">
                <span>Dana Dikembalikan</span>
                <span>Rp {{ number_format($refundAmount, 0, ',', '.') }}</span>
            </div>
        </div>
        @else
        <div style="background:#ffebee;border-radius:8px;padding:16px;margin-top:16px;font-family:Arial;font-size:14px;color:#c62828">
            <i class="fas fa-exclamation-circle"></i>
            Pembatalan H-0 tidak mendapatkan pengembalian dana.
        </div>
        @endif
    </div>

    {{-- Form Pengajuan --}}
    <div class="card">
        <div class="card-title">Form Pengajuan Refund</div>
        <form method="POST" action="/refunds/{{ $booking->id }}">
            @csrf
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Alasan Pembatalan</label>
                <textarea name="refund_reason" rows="3"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;resize:vertical"
                    placeholder="Jelaskan alasan pembatalan...">{{ old('refund_reason') }}</textarea>
                @error('refund_reason')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Bank</label>
                <select name="bank_name" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;background:#fff">
                    <option value="">-- Pilih Bank --</option>
                    <option value="BCA">BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="CIMB Niaga">CIMB Niaga</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="Dana">Dana</option>
                </select>
                @error('bank_name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nomor Rekening / Dompet Digital</label>
                <input type="text" name="account_number" value="{{ old('account_number') }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial"
                    placeholder="contoh: 1234567890">
                @error('account_number')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Nama Pemilik Rekening</label>
                <input type="text" name="account_name" value="{{ old('account_name', auth()->user()->name) }}"
                    style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
                @error('account_name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Yakin ingin mengajukan refund? Booking akan dibatalkan setelah disetujui manager.')">
                    Ajukan Refund
                </button>
                <a href="/my-bookings" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection