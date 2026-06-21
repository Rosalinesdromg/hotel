<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lunar Hotel</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #2c2c2c; }
        .header { background:#1a1a2e; color:#fff; padding:20px 24px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; }
        .header h1 { font-size:20px; color:#d4af7a; letter-spacing:2px; font-family:Georgia; }
        .header p  { font-size:11px; color:rgba(255,255,255,0.5); margin-top:4px; }
        .header .period { text-align:right; font-size:11px; color:rgba(255,255,255,0.7); }
        .stat-row { display:flex; gap:12px; margin-bottom:20px; }
        .stat-box { flex:1; border:1px solid #e8e4de; border-radius:6px; padding:14px; }
        .stat-box .label { font-size:10px; color:#999; letter-spacing:1px; text-transform:uppercase; margin-bottom:4px; }
        .stat-box .value { font-size:16px; color:#1a1a2e; font-weight:bold; }
        .section-title { font-size:13px; color:#1a1a2e; font-weight:bold; margin-bottom:8px; padding-bottom:6px; border-bottom:2px solid #d4af7a; }
        table { width:100%; border-collapse:collapse; margin-bottom:20px; font-size:11px; }
        thead th { background:#1a1a2e; color:#fff; padding:8px 10px; text-align:left; font-weight:normal; }
        tbody td { padding:7px 10px; border-bottom:1px solid #f0ece6; }
        tbody tr:nth-child(even) { background:#f9f7f4; }
        .gold { color:#d4af7a; }
        .footer { margin-top:20px; padding-top:12px; border-top:1px solid #e8e4de; text-align:center; font-size:10px; color:#aaa; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <h1>☽ LUNAR HOTEL</h1>
        <p>Laporan Keuangan</p>
    </div>
    <div class="period">
        Periode: {{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}<br>
        Dicetak: {{ now()->format('d M Y, H:i') }}
    </div>
</div>

<div class="stat-row">
    <div class="stat-box">
        <div class="label">Total Pendapatan</div>
        <div class="value gold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Pendapatan Kamar</div>
        <div class="value">Rp {{ number_format($bookingRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Pendapatan Restoran</div>
        <div class="value">Rp {{ number_format($orderRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Total Booking</div>
        <div class="value">{{ $bookings->count() }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Total Order</div>
        <div class="value">{{ $orders->count() }}</div>
    </div>
</div>

<div class="section-title">Data Booking</div>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Paket</th>
            <th>Total</th>
            <th>DP</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings as $b)
        <tr>
            <td>{{ $b->booking_code }}</td>
            <td>{{ $b->user->name }}</td>
            <td>{{ $b->room->room_number }} — {{ $b->room->roomType->name }}</td>
            <td>{{ $b->check_in->format('d/m/Y') }}</td>
            <td>{{ $b->check_out->format('d/m/Y') }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $b->package)) }}</td>
            <td>Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($b->dp_amount, 0, ',', '.') }}</td>
            <td>{{ ucfirst($b->status) }}</td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;color:#aaa;padding:16px">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

<div class="section-title">Data Order Restoran</div>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tipe</th>
            <th>Item</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $o)
        <tr>
            <td>{{ $o->order_code }}</td>
            <td>{{ ucfirst($o->type) }}</td>
            <td>{{ $o->items->map(fn($i) => $i->menu->name . ' x' . $i->quantity)->join(', ') }}</td>
            <td>Rp {{ number_format($o->total_price, 0, ',', '.') }}</td>
            <td>{{ strtoupper($o->payment_method ?? '-') }}</td>
            <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#aaa;padding:16px">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Laporan ini digenerate otomatis oleh Sistem Manajemen Lunar Hotel — {{ now()->format('d M Y, H:i') }}
</div>

</body>
</html>