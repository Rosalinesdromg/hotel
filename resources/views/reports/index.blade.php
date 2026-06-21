@extends('layouts.app')
@section('title', 'Laporan Keuangan')

@section('content')

{{-- Filter --}}
<div class="card" style="margin-bottom:24px">
    <div class="card-title">Filter Periode</div>
    <form method="GET" action="/reports" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap">
        <div>
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                style="padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <div>
            <label style="display:block;font-size:13px;color:#666;margin-bottom:6px;font-family:Arial">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                style="padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial">
        </div>
        <button type="submit" class="btn btn-gold">Tampilkan</button>
        <a href="/reports" class="btn btn-outline">Reset</a>

        {{-- TAMBAH INI --}}
    <a href="/reports/export/excel?start_date={{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}&end_date={{ request('end_date', now()->format('Y-m-d')) }}"
        class="btn btn-outline" style="border-color:#2e7d32;color:#2e7d32">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <a href="/reports/export/pdf?start_date={{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}&end_date={{ request('end_date', now()->format('Y-m-d')) }}"
        class="btn btn-outline" style="border-color:#c62828;color:#c62828">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
    </form>
</div>

{{-- Stat Cards --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="label">Total Pendapatan</div>
        <div class="value" style="font-size:20px">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="sub">kamar + restoran</div>
        <div class="icon"><i class="fas fa-coins"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Pendapatan Kamar</div>
        <div class="value" style="font-size:20px">Rp {{ number_format($bookingRevenue, 0, ',', '.') }}</div>
        <div class="sub">dari booking</div>
        <div class="icon"><i class="fas fa-door-open"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Pendapatan Restoran</div>
        <div class="value" style="font-size:20px">Rp {{ number_format($orderRevenue, 0, ',', '.') }}</div>
        <div class="sub">walk-in only</div>
        <div class="icon"><i class="fas fa-utensils"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Occupancy Rate</div>
        <div class="value">{{ $occupancyRate }}%</div>
        <div class="sub">{{ $totalCheckouts }} checkout · {{ $cancelledCount }} cancelled</div>
        <div class="icon"><i class="fas fa-chart-pie"></i></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">

    {{-- Top Menu --}}
    <div class="card">
        <div class="card-title">Menu Terlaris</div>
        @forelse($topMenus as $i => $menu)
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
            <div style="width:26px;height:26px;background:#d4af7a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-family:Arial;flex-shrink:0">
                {{ $i + 1 }}
            </div>
            <div style="flex:1">
                <div style="font-size:14px;color:#1a1a2e;font-family:Arial">{{ $menu->name }}</div>
                <div style="font-size:11px;color:#aaa;font-family:Arial">{{ $menu->category }}</div>
            </div>
            <div style="font-size:14px;color:#d4af7a;font-family:Arial;font-weight:bold">
                {{ $menu->order_items_sum_quantity ?? 0 }}x
            </div>
        </div>
        @empty
        <div style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:20px">Belum ada data</div>
        @endforelse
    </div>

    {{-- Booking per Tipe Kamar --}}
    <div class="card">
        <div class="card-title">Booking per Tipe Kamar</div>
        @forelse($bookingsByType as $type => $count)
        <div style="margin-bottom:12px">
            <div style="display:flex;justify-content:space-between;font-size:14px;font-family:Arial;margin-bottom:4px">
                <span style="color:#1a1a2e">{{ $type }}</span>
                <span style="color:#d4af7a;font-weight:bold">{{ $count }} booking</span>
            </div>
            <div style="background:#f0ece6;border-radius:4px;height:6px">
                <div style="background:#d4af7a;border-radius:4px;height:6px;width:{{ min(($count / max($bookingsByType->max(), 1)) * 100, 100) }}%"></div>
            </div>
        </div>
        @empty
        <div style="color:#aaa;font-family:Arial;font-size:14px;text-align:center;padding:20px">Belum ada data</div>
        @endforelse
    </div>
</div>

{{-- Tabel Booking --}}
<div class="card" style="margin-bottom:24px">
    <div class="card-title">Detail Booking ({{ $bookings->count() }} data)</div>
    <div style="overflow-x:auto">
        <div class="table-responsive">
        <table id="report-bookings-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Paket</th>
                    <th>Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td>{{ $b->booking_code }}</td>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->room->room_number }} — {{ $b->room->roomType->name }}</td>
                    <td>{{ $b->check_in->format('d M Y') }}</td>
                    <td>{{ $b->check_out->format('d M Y') }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $b->package)) }}</td>
                    <td>Rp {{ number_format($b->dp_amount, 0, ',', '.') }}</td>
                    <td>
                        @php $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending']; @endphp
                        <span class="badge {{ $sBadge[$b->status] }}">{{ ucfirst($b->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:#aaa;padding:24px">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>

{{-- Tabel Order --}}
<div class="card">
    <div class="card-title">Detail Order Restoran ({{ $orders->count() }} data)</div>
    <div style="overflow-x:auto">
        <div class="table-responsive">
        <<table id="report-orders-table">>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td>{{ $o->order_code }}</td>
                    <td>{{ ucfirst($o->type) }}</td>
                    <td>{{ $o->items->count() }} item</td>
                    <td>Rp {{ number_format($o->total_price, 0, ',', '.') }}</td>
                    <td>{{ $o->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#aaa;padding:24px">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#report-bookings-table').DataTable({
        responsive: true,
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { next: "→", previous: "←" }
        }
    });
    $('#report-orders-table').DataTable({
        responsive: true,
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { next: "→", previous: "←" }
        }
    });
});
</script>
@endpush
@endsection

