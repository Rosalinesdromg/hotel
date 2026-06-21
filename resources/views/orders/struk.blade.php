<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk — {{ $order->order_code }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #f0ece6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 32px 16px;
        }
        .actions {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            width: 100%;
            max-width: 360px;
        }
        .btn-print {
            flex: 1;
            padding: 12px;
            background: #1a1a2e;
            color: #d4af7a;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-family: Arial;
            transition: all 0.2s;
        }
        .btn-print:hover { background: #d4af7a; color: #fff; }
        .btn-back {
            flex: 1;
            padding: 12px;
            background: transparent;
            color: #1a1a2e;
            border: 2px solid #1a1a2e;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-family: Arial;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-back:hover { background: #1a1a2e; color: #fff; }

        /* Struk */
        .struk {
            width: 100%;
            max-width: 360px;
            background: #fff;
            border-radius: 4px;
            padding: 24px 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
        }
        .struk-header { text-align: center; margin-bottom: 16px; }
        .struk-header h1 { font-size: 20px; letter-spacing: 3px; color: #1a1a2e; }
        .struk-header .sub { font-size: 11px; color: #999; letter-spacing: 2px; margin-top: 2px; }
        .struk-header .address { font-size: 11px; color: #888; margin-top: 6px; line-height: 1.5; }

        .divider-dash { border: none; border-top: 1px dashed #ccc; margin: 12px 0; }
        .divider-solid { border: none; border-top: 2px solid #1a1a2e; margin: 12px 0; }

        .info-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; }
        .info-row .label { color: #888; }
        .info-row .value { color: #1a1a2e; font-weight: bold; }

        .order-code { text-align: center; font-size: 16px; font-weight: bold; color: #1a1a2e; letter-spacing: 2px; margin: 8px 0; }

        .items-header { display: flex; justify-content: space-between; font-size: 11px; color: #999; margin-bottom: 8px; }
        .item-row { margin-bottom: 8px; }
        .item-row .item-name { font-size: 13px; color: #1a1a2e; }
        .item-row .item-detail { display: flex; justify-content: space-between; font-size: 12px; color: #666; margin-top: 2px; }

        .subtotal-row { display: flex; justify-content: space-between; font-size: 13px; color: #666; margin-bottom: 4px; }
        .total-row {
            display: flex; justify-content: space-between;
            font-size: 16px; font-weight: bold; color: #1a1a2e;
            margin-top: 8px;
        }

        .payment-box {
            background: #f9f7f4;
            border-radius: 4px;
            padding: 12px;
            margin-top: 12px;
        }
        .payment-row { display: flex; justify-content: space-between; font-size: 12px; color: #666; margin-bottom: 4px; }
        .payment-row:last-child { margin-bottom: 0; }

        .room-service-box {
            background: #fff8e1;
            border: 1px dashed #ffc107;
            border-radius: 4px;
            padding: 10px;
            margin-top: 12px;
            text-align: center;
            font-size: 12px;
            color: #f57f17;
        }

        .struk-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 11px;
            color: #aaa;
            line-height: 1.7;
        }

        .barcode {
            text-align: center;
            margin-top: 12px;
            letter-spacing: 4px;
            font-size: 10px;
            color: #ccc;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .actions { display: none; }
            .struk { box-shadow: none; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="actions">
    <button onclick="window.print()" class="btn-print">
        🖨️ Print Struk
    </button>
    <a href="/kasir" class="btn-back">← Kasir</a>
</div>

<div class="struk">
    {{-- Header --}}
    <div class="struk-header">
        <h1>☽ LUNAR</h1>
        <div class="sub">H O T E L</div>
        <div class="address">
            Jl. Bulan Purnama No. 1, Jakarta<br>
            Telp: +62 21 1234 5678
        </div>
    </div>

    <hr class="divider-solid">

    {{-- Tipe order --}}
    <div style="text-align:center;font-size:12px;color:#888;margin-bottom:8px;letter-spacing:1px;text-transform:uppercase">
        @if($order->type === 'room_service')
            🛎️ Room Service
        @else
            🚶 Walk-in
        @endif
    </div>

    <div class="order-code">{{ $order->order_code }}</div>

    <hr class="divider-dash">

    {{-- Info --}}
    <div class="info-row">
        <span class="label">Tanggal</span>
        <span class="value">{{ $order->created_at->format('d/m/Y') }}</span>
    </div>
    <div class="info-row">
        <span class="label">Waktu</span>
        <span class="value">{{ $order->created_at->format('H:i') }} WIB</span>
    </div>
    <div class="info-row">
        <span class="label">Kasir</span>
        <span class="value">{{ $order->user->name ?? '-' }}</span>
    </div>
    @if($order->type === 'room_service' && $order->booking)
    <div class="info-row">
        <span class="label">Kamar</span>
        <span class="value">{{ $order->booking->room->room_number }} — {{ $order->booking->room->roomType->name }}</span>
    </div>
    <div class="info-row">
        <span class="label">Tamu</span>
        <span class="value">{{ $order->booking->user->name }}</span>
    </div>
    @endif

    <hr class="divider-dash">

    {{-- Items --}}
    <div class="items-header">
        <span>Item</span>
        <span>Subtotal</span>
    </div>

    @foreach($order->items as $item)
    <div class="item-row">
        <div class="item-name">{{ $item->menu->name }}</div>
        <div class="item-detail">
            <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <hr class="divider-dash">

    {{-- Total --}}
    <div class="subtotal-row">
        <span>Subtotal ({{ $order->items->count() }} item)</span>
        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>
    <div class="subtotal-row">
        <span>Pajak (0%)</span>
        <span>Rp 0</span>
    </div>
    <div class="total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    {{-- Pembayaran --}}
    @if($order->type === 'walkin')
    <div class="payment-box">
        <div class="payment-row">
            <span>Metode</span>
            <span><strong>{{ strtoupper($order->payment_method) }}</strong></span>
        </div>
        <div class="payment-row">
            <span>Dibayar</span>
            <span><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></span>
        </div>
        <div class="payment-row">
            <span>Kembalian</span>
            <span><strong>Rp 0</strong></span>
        </div>
    </div>
    @else
    <div class="room-service-box">
        Ditagihkan ke kamar {{ $order->booking->room->room_number }}<br>
        Dibayar saat check-out
    </div>
    @endif

    <hr class="divider-solid">

    {{-- Footer --}}
    <div class="struk-footer">
        Terima kasih telah memesan<br>
        di Restoran Lunar Hotel<br><br>
        <strong style="color:#d4af7a;letter-spacing:1px">Selamat Menikmati!</strong>
    </div>

    <div class="barcode">
        ||| {{ $order->order_code }} |||
    </div>
</div>

</body>
</html>