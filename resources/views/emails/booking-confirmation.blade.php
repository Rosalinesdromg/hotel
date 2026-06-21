<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; background:#f9f7f4; color:#2c2c2c; }
        .wrapper { max-width:560px; margin:0 auto; padding:32px 16px; }
        .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.06); }
        .header { background:#1a1a2e; padding:32px; text-align:center; }
        .header h1 { color:#d4af7a; font-size:24px; letter-spacing:3px; font-family:Georgia; font-weight:normal; }
        .header p  { color:rgba(255,255,255,0.4); font-size:11px; letter-spacing:3px; text-transform:uppercase; margin-top:4px; }
        .body { padding:32px; }
        .greeting { font-size:16px; color:#1a1a2e; margin-bottom:16px; }
        .info-text { font-size:14px; color:#666; line-height:1.7; margin-bottom:24px; }
        .booking-code { background:#f9f7f4; border:2px dashed #d4af7a; border-radius:8px; padding:16px; text-align:center; margin-bottom:24px; }
        .booking-code .label { font-size:11px; color:#999; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px; }
        .booking-code .code  { font-size:24px; color:#1a1a2e; letter-spacing:3px; font-family:Georgia; }
        .detail-box { background:#f9f7f4; border-radius:8px; padding:20px; margin-bottom:24px; }
        .detail-row { display:flex; justify-content:space-between; font-size:14px; padding:6px 0; border-bottom:1px solid #f0ece6; }
        .detail-row:last-child { border-bottom:none; }
        .detail-label { color:#999; }
        .detail-value { color:#1a1a2e; font-weight:bold; }
        .total-box { background:#1a1a2e; border-radius:8px; padding:20px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center; }
        .total-box .label { color:rgba(255,255,255,0.6); font-size:13px; }
        .total-box .amount { color:#d4af7a; font-size:20px; font-family:Georgia; }
        .btn { display:block; text-align:center; background:#d4af7a; color:#fff; padding:14px 32px; border-radius:6px; text-decoration:none; font-size:14px; letter-spacing:1px; margin-bottom:24px; }
        .note { font-size:12px; color:#aaa; line-height:1.7; padding:16px; background:#fff8e1; border-radius:6px; border-left:3px solid #ffc107; margin-bottom:24px; }
        .footer { padding:20px 32px; text-align:center; border-top:1px solid #f0ece6; }
        .footer p { font-size:12px; color:#aaa; line-height:1.7; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="header">
            <h1>☽ LUNAR</h1>
            <p>Hotel</p>
        </div>
        <div class="body">
            <div class="greeting">Halo, {{ $booking->user->name }}! 👋</div>
            <div class="info-text">
                Terima kasih telah mempercayai Lunar Hotel. Booking Anda telah berhasil dikonfirmasi.
                Berikut detail reservasi Anda:
            </div>

            <div class="booking-code">
                <div class="label">Kode Booking</div>
                <div class="code">{{ $booking->booking_code }}</div>
            </div>

            <div class="detail-box">
                <div class="detail-row">
                    <span class="detail-label">Tipe Kamar</span>
                    <span class="detail-value">{{ $booking->room->roomType->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nomor Kamar</span>
                    <span class="detail-value">{{ $booking->room->room_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-in</span>
                    <span class="detail-value">{{ $booking->check_in->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-out</span>
                    <span class="detail-value">{{ $booking->check_out->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Durasi</span>
                    <span class="detail-value">{{ $booking->check_in->diffInDays($booking->check_out) }} malam</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tamu</span>
                    <span class="detail-value">{{ $booking->guest_count }} orang</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Paket</span>
                    <span class="detail-value">{{ ucwords(str_replace('_', ' ', $booking->package)) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status Bayar</span>
                    <span class="detail-value">{{ strtoupper($booking->payment_status) }}</span>
                </div>
            </div>

            <div class="total-box">
                <span class="label">
                    @if($booking->payment_status === 'dp')
                        DP Dibayar (30%)
                    @else
                        Total Dibayar
                    @endif
                </span>
                <span class="amount">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>

            @if($booking->payment_status === 'dp')
            <div class="note">
                <strong>Catatan:</strong> Anda memilih pembayaran DP 30%.
                Sisa pembayaran sebesar <strong>Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</strong>
                akan dibayarkan saat check-in.
            </div>
            @endif

            <a href="{{ url('/my-bookings/' . $booking->id) }}" class="btn">
                Lihat Detail Booking
            </a>

            <div class="note">
                <strong>Ketentuan Check-in:</strong><br>
                • Check-in mulai pukul 14.00 WIB<br>
                • Check-out maksimal pukul 12.00 WIB<br>
                • Harap membawa identitas valid (KTP/Paspor) saat check-in<br>
                • Tamu minimal berusia 17 tahun
            </div>
        </div>
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem Lunar Hotel.<br>
                Jika ada pertanyaan, hubungi kami di <strong>info@lunarhotel.com</strong><br>
                atau telepon <strong>+62 21 1234 5678</strong>
            </p>
        </div>
    </div>
</div>
</body>
</html>