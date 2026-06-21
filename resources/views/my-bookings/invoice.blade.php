<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->booking_code }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Georgia, serif; color: #2c2c2c; padding: 48px; background: #fff; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:40px; padding-bottom:24px; border-bottom:2px solid #1a1a2e; }
        .brand h1 { font-size:28px; color:#1a1a2e; letter-spacing:3px; font-weight:normal; }
        .brand p  { font-size:12px; color:#999; letter-spacing:2px; text-transform:uppercase; margin-top:4px; }
        .invoice-info { text-align:right; }
        .invoice-info h2 { font-size:22px; color:#d4af7a; font-weight:normal; letter-spacing:2px; }
        .invoice-info p  { font-size:13px; color:#666; font-family:Arial; margin-top:4px; }
        .section { margin-bottom:28px; }
        .section-title { font-size:11px; color:#999; letter-spacing:2px; text-transform:uppercase; font-family:Arial; margin-bottom:12px; }
        .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
        .info-row { display:flex; gap:8px; margin-bottom:6px; font-family:Arial; font-size:14px; }
        .info-label { color:#999; width:100px; flex-shrink:0; }
        table { width:100%; border-collapse:collapse; font-family:Arial; font-size:14px; }
        thead th { background:#f9f7f4; padding:10px 12px; text-align:left; font-weight:normal; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#666; }
        tbody td { padding:12px; border-bottom:1px solid #f5f3f0; }
        .total-row { display:flex; justify-content:space-between; padding:8px 0; font-family:Arial; font-size:14px; color:#666; }
        .total-final { display:flex; justify-content:space-between; padding:12px 0 0; font-size:18px; color:#1a1a2e; border-top:2px solid #1a1a2e; margin-top:8px; }
        .footer { margin-top:48px; padding-top:20px; border-top:1px solid #e8e4de; text-align:center; font-family:Arial; font-size:12px; color:#aaa; }
        @media print { body { padding:24px; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">
            <h1>☽ LUNAR HOTEL</h1>
            <p>Classic Modern Experience</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p>{{ $booking->booking_code }}</p>
            <p>{{ now()->format('d M Y') }}</p>
        </div>
    </div>

    <div class="grid2 section">
        <div>
            <div class="section-title">Tagihan Kepada</div>
            <div class="info-row"><span class="info-label">Nama</span><span>{{ $booking->user->name }}</span></div>
            <div class="info-row"><span class="info-label">Email</span><span>{{ $booking->user->email }}</span></div>
            <div class="info-row"><span class="info-label">Telepon</span><span>{{ $booking->user->phone ?? '-' }}</span></div>
        </div>
        <div>
            <div class="section-title">Detail Menginap</div>
            <div class="info-row"><span class="info-label">Kamar</span><span>{{ $booking->room->room_number }} — {{ $booking->room->roomType->name }}</span></div>
            <div class="info-row"><span class="info-label">Check-in</span><span>{{ $booking->check_in->format('d M Y') }}</span></div>
            <div class="info-row"><span class="info-label">Check-out</span><span>{{ $booking->check_out->format('d M Y') }}</span></div>
            <div class="info-row"><span class="info-label">Paket</span><span>{{ ucwords(str_replace('_', ' ', $booking->package)) }}</span></div>
            <div class="info-row">
            <span class="info-label">Metode Bayar</span>
            <span>{{ $booking->payment_method === 'cash' ? 'Cash' : 'Kartu (Debit/Kredit)' }}</span>
        </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Rincian Biaya</div>
        <table>
            <thead>
                <tr><th>Keterangan</th><th style="text-align:right">Jumlah</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Biaya Kamar ({{ $booking->check_in->diffInDays($booking->check_out) }} malam)</td>
                    <td style="text-align:right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
                @foreach($booking->orders as $order)
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->menu->name }} x{{ $item->quantity }} (Room Service)</td>
                    <td style="text-align:right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>

        <div style="max-width:300px;margin-left:auto;margin-top:16px">
            <div class="total-row"><span>Subtotal</span><span>Rp {{ number_format($booking->grandTotal(), 0, ',', '.') }}</span></div>
            <div class="total-row"><span>DP Dibayar</span><span style="color:#2e7d32">- Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span></div>
            <div class="total-final">
                <strong>Total Dibayar</strong>
                <strong style="color:#d4af7a">Rp {{ number_format($booking->grandTotal(), 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih telah menginap di Lunar Hotel</p>
        <p style="margin-top:4px">Dokumen ini digenerate otomatis oleh sistem</p>
    </div>

    <script>window.onload = () => window.print();</script>
</body>
</html>