@extends('layouts.app')
@section('title', 'Reservasi')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Daftar Reservasi</div>
        <a href="/bookings/create" class="btn btn-gold">+ Booking Baru</a>
    </div>
    <<table id="bookings-table">>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tamu</th>
                <th>Kamar</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $b)
            <tr>
                <td><strong>{{ $b->booking_code }}</strong></td>
                <td>{{ $b->user->name }}</td>
                <td>{{ $b->room->room_number }} — {{ $b->room->roomType->name }}</td>
                <td>{{ $b->check_in->format('d M Y') }}</td>
                <td>{{ $b->check_out->format('d M Y') }}</td>
                <td>Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                <td>
                    @php $pBadge = ['unpaid'=>'badge-pending','dp'=>'badge-dp','paid'=>'badge-paid']; @endphp
                    <span class="badge {{ $pBadge[$b->payment_status] }}">{{ strtoupper($b->payment_status) }}</span>
                </td>
                <td>
                    @php
                        $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending'];
                        $sLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','checked_in'=>'Check-in','checked_out'=>'Check-out','cancelled'=>'Cancelled'];
                    @endphp
                    <span class="badge {{ $sBadge[$b->status] }}">{{ $sLabel[$b->status] }}</span>
                </td>
                <td style="display:flex;gap:6px;flex-wrap:wrap">
                    <a href="/bookings/{{ $b->id }}" class="btn btn-outline" style="padding:4px 10px;font-size:12px">Detail</a>
                    @if($b->status === 'confirmed')
                    <form method="POST" action="/bookings/{{ $b->id }}/check-in">
                        @csrf
                        <button class="btn btn-gold" style="padding:4px 10px;font-size:12px">Check-in</button>
                    </form>
                    @endif
                    @if($b->status === 'checked_in')
                    <form method="POST" action="/bookings/{{ $b->id }}/check-out">
                        @csrf
                        <button class="btn btn-danger" style="padding:4px 10px;font-size:12px">Check-out</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:#aaa;padding:32px">Belum ada reservasi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#bookings-table').DataTable({
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
            { orderable: false, targets: -1 } // kolom aksi tidak bisa di-sort
        ]
    });
});
</script>
@endpush
@endsection

