@extends('layouts.app')
@section('title', 'Order Restoran')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Order Restoran</div>
        <a href="/orders/create" class="btn btn-gold">+ Order Baru</a>
    </div>
    <div class="table-responsive">
    <<table id="orders-table">>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Kamar/Tamu</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong>{{ $order->order_code }}</strong></td>
                <td>
                    @if($order->type === 'room_service')
                        <span class="badge badge-dp">Room Service</span>
                    @else
                        <span class="badge badge-available">Walk-in</span>
                    @endif
                </td>
                <td>
                    @if($order->booking)
                        Kamar {{ $order->booking->room->room_number }} — {{ $order->booking->user->name }}
                    @else
                        Tamu Umum
                    @endif
                </td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ strtoupper($order->payment_method ?? '-') }}</td>
                <td>
                    @if($order->status === 'paid')
                        <span class="badge badge-paid">Paid</span>
                    @elseif($order->status === 'void')
                        <span class="badge badge-pending">Void</span>
                    @else
                        <span class="badge badge-pending">Pending</span>
                    @endif
                </td>
                <td style="display:flex;gap:6px">
                    <a href="/orders/{{ $order->id }}" class="btn btn-outline" style="padding:4px 10px;font-size:12px">Detail</a>
                    @if($order->status === 'paid')
                        @role('manager|ceo')
                        <form method="POST" action="/orders/{{ $order->id }}/void" onsubmit="return confirm('Void order ini?')">
                            @csrf
                            <button class="btn btn-danger" style="padding:4px 10px;font-size:12px">Void</button>
                        </form>
                        @endrole
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:32px">Belum ada order</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#orders-table').DataTable({
        responsive: true,
        language: {
            search:         "Cari:",
            lengthMenu:     "Tampilkan _MENU_ data",
            info:           "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty:      "Tidak ada data",
            zeroRecords:    "Data tidak ditemukan",
            paginate: {
                first:    "Pertama",
                last:     "Terakhir",
                next:     "→",
                previous: "←"
            }
        },
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});
</script>
@endpush
@endsection

