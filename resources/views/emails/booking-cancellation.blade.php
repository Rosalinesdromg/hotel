<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Refund Disetujui</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial,sans-serif; background:#f9f7f4; color:#2c2c2c; }
        .wrapper { max-width:560px; margin:0 auto; padding:32px 16px; }
        .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.06); }
        .header { background:#1a1a2e; padding:32px; text-align:center; }
        .header h1 { color:#d4af7a; font-size:24px; letter-spacing:3px; font-family:Georgia; font-weight:normal; }
        .body { padding:32px; }
        .greeting { font-size:16px; color:#1a1a2e; margin-bottom:16px; }
        .info-text { font-size:14px; color:#666; line-height:1.7; margin-bottom:24px; }
        .refund-box { background:#e8f5e9; border:1px solid #a5d6a7; border-radius:8px; padding:20px; margin-bottom:24px; text-align:center; }
        .refund-box .label  { font-size:12px; color:#2e7d32; margin-bottom:8px; }
        .refund-box .amount { font-size:28px; color:#2e7d32; font-family:Georgia; }
        .detail-box { background:#f9f7f4; border-radius:8px; padding:20px; margin-bottom:24px; }
        .detail-row { display:flex; justify-content:space-between; font-size:14px; padding:6px 0; border-bottom:1px solid #f0ece6; }
        .detail-row:last-child { border-bottom:none; }
        .detail-label { color:#999; }
        .detail-value { color:#1a1a2e; font-weight:bold; }
        .note { font-size:12px; color:#666; line-height:1.7; padding:16px; background:#fff3e0; border-radius:6px; border-left:3px solid #ff9800; margin-bottom:24px; }
        .footer { padding:20px 32px; text-align:center; border-top:1px solid #f0ece6; }
        .footer p { font-size:12px; color:#aaa; line-height:1.7; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="header">
            <h1>☽ LUNAR</h1>
        </div>
        <div class="body">
            @php $refundData = json_decode($booking->refund_data, true) ?? []; @endphp

            <div class="greeting">Halo, {{ $booking->user->name }}</div>
            <div class="info-text">
                Pengajuan pembatalan dan refund untuk booking <strong>{{ $booking->booking_code }}</strong>
                telah disetujui oleh tim kami.
            </div>

            <div class="refund-box">
                <div class="label">Dana yang Dikembalikan</div>
                <div class="amount">Rp {{ number_format($refundData['refund_amount'] ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="detail-box">
                <div class="detail-row">
                    <span class="detail-label">Kode Booking</span>
                    <span class="detail-value">{{ $booking->booking_code }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Bank / Dompet</span>
                    <span class="detail-value">{{ $refundData['bank_name'] ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">No. Rekening</span>
                    <span class="detail-value">{{ $refundData['account_number'] ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Atas Nama</span>
                    <span class="detail-value">{{ $refundData['account_name'] ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Biaya Pembatalan</span>
                    <span class="detail-value" style="color:#c62828">{{ $refundData['fee_percent'] ?? 0 }}%</span>
                </div>
            </div>

            <div class="note">
                Dana akan ditransfer dalam <strong>2-3 hari kerja</strong>.
                Jika dalam 3 hari kerja dana belum masuk, hubungi kami dengan menyertakan
                kode booking <strong>{{ $booking->booking_code }}</strong>.
            </div>
        </div>
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem Lunar Hotel.<br>
                Hubungi kami di <strong>info@lunarhotel.com</strong>
            </p>
        </div>
    </div>
</div>
</body>
</html>