@extends('layouts.app')
@section('title', 'CEO Dashboard')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
    .chart-card { background:#fff; border:1px solid #e8e4de; border-radius:8px; padding:24px; margin-bottom:24px; }
    .chart-card .chart-title { font-size:14px; color:#1a1a2e; margin-bottom:4px; letter-spacing:0.5px; }
    .chart-card .chart-sub   { font-size:12px; color:#aaa; font-family:Arial; margin-bottom:20px; }
    .growth-up   { color:#2e7d32; font-size:12px; font-family:Arial; }
    .growth-down { color:#c62828; font-size:12px; font-family:Arial; }
</style>
@endpush

@section('content')

{{-- STAT CARDS --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="label">Pendapatan Bulan Ini</div>
        <div class="value" style="font-size:20px">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</div>
        <div class="sub">
            @if($revenueGrowth >= 0)
                <span class="growth-up">↑ {{ $revenueGrowth }}% dari bulan lalu</span>
            @else
                <span class="growth-down">↓ {{ abs($revenueGrowth) }}% dari bulan lalu</span>
            @endif
        </div>
        <div class="icon"><i class="fas fa-coins"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Kamar Tersedia</div>
        <div class="value">{{ $availableRooms }}<span style="font-size:16px;color:#aaa">/{{ $totalRooms }}</span></div>
        <div class="sub">{{ $currentGuests }} tamu menginap</div>
        <div class="icon"><i class="fas fa-door-open"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Total Customer</div>
        <div class="value">{{ $totalCustomers }}</div>
        <div class="sub">terdaftar</div>
        <div class="icon"><i class="fas fa-users"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Status Kamar</div>
        <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap">
            <span class="badge badge-available">{{ $roomStats['available'] }} Tersedia</span>
            <span class="badge badge-occupied">{{ $roomStats['occupied'] }} Terisi</span>
            <span class="badge badge-dirty">{{ $roomStats['dirty'] }} Kotor</span>
        </div>
        <div class="icon"><i class="fas fa-chart-pie"></i></div>
    </div>
</div>

{{-- CHART ROW 1 --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px">

    {{-- Revenue Chart --}}
    <div class="chart-card">
        <div class="chart-title">Grafik Pendapatan</div>
        <div class="chart-sub">6 bulan terakhir — kamar & restoran</div>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Donut Room Status --}}
    <div class="chart-card">
        <div class="chart-title">Status Kamar</div>
        <div class="chart-sub">Distribusi saat ini</div>
        <canvas id="roomStatusChart" height="180"></canvas>
    </div>
</div>

{{-- CHART ROW 2 --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">

    {{-- Occupancy Chart --}}
    <div class="chart-card">
        <div class="chart-title">Occupancy Rate</div>
        <div class="chart-sub">6 bulan terakhir (%)</div>
        <canvas id="occupancyChart" height="140"></canvas>
    </div>

    {{-- Top Menu Chart --}}
    <div class="chart-card">
        <div class="chart-title">Menu Terlaris</div>
        <div class="chart-sub">Berdasarkan jumlah terjual</div>
        <canvas id="menuChart" height="140"></canvas>
    </div>
</div>

{{-- Booking per Tipe --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
    <div class="chart-card">
        <div class="chart-title">Booking per Tipe Kamar</div>
        <div class="chart-sub">Bulan ini</div>
        <canvas id="roomTypeChart" height="160"></canvas>
    </div>

    {{-- Tabel Booking Terbaru --}}
    <div class="chart-card">
        <div class="chart-title">Booking Terbaru</div>
        <div class="chart-sub">8 reservasi terakhir</div>
        <table style="width:100%;font-family:Arial;font-size:13px">
            <thead>
                <tr>
                    <th style="text-align:left;color:#aaa;font-weight:normal;padding:6px 0;border-bottom:1px solid #f0ece6">Tamu</th>
                    <th style="text-align:left;color:#aaa;font-weight:normal;padding:6px 0;border-bottom:1px solid #f0ece6">Kamar</th>
                    <th style="text-align:right;color:#aaa;font-weight:normal;padding:6px 0;border-bottom:1px solid #f0ece6">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $b)
                <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #f9f7f4;color:#1a1a2e">{{ Str::limit($b->user->name, 16) }}</td>
                    <td style="padding:8px 0;border-bottom:1px solid #f9f7f4;color:#888">{{ $b->room->room_number }}</td>
                    <td style="padding:8px 0;border-bottom:1px solid #f9f7f4;text-align:right">
                        @php $sBadge = ['pending'=>'badge-pending','confirmed'=>'badge-dp','checked_in'=>'badge-available','checked_out'=>'badge-paid','cancelled'=>'badge-pending']; @endphp
                        <span class="badge {{ $sBadge[$b->status] }}" style="font-size:11px">{{ ucfirst($b->status) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const gold   = '#d4af7a';
const dark   = '#1a1a2e';
const light  = '#f9f7f4';

// === Revenue Chart ===
const revData = @json($revenueChart);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revData.map(d => d.label),
        datasets: [
            {
                label: 'Kamar',
                data: revData.map(d => d.room),
                backgroundColor: 'rgba(212,175,122,0.8)',
                borderRadius: 4,
            },
            {
                label: 'Restoran',
                data: revData.map(d => d.order),
                backgroundColor: 'rgba(26,26,46,0.7)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt'
                }
            }
        }
    }
});

// === Room Status Donut ===
new Chart(document.getElementById('roomStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Tersedia', 'Terisi', 'Kotor', 'Maintenance'],
        datasets: [{
            data: [
                {{ $roomStats['available'] }},
                {{ $roomStats['occupied'] }},
                {{ $roomStats['dirty'] }},
                {{ $roomStats['maintenance'] }}
            ],
            backgroundColor: ['#4caf50','#ff9800','#ffc107','#f44336'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%'
    }
});

// === Occupancy Chart ===
const occData = @json($occupancyChart);
new Chart(document.getElementById('occupancyChart'), {
    type: 'line',
    data: {
        labels: occData.map(d => d.label),
        datasets: [{
            label: 'Occupancy %',
            data: occData.map(d => d.rate),
            borderColor: gold,
            backgroundColor: 'rgba(212,175,122,0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: gold,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { min: 0, max: 100, ticks: { callback: v => v + '%' } }
        }
    }
});

// === Top Menu Chart ===
const menuData = @json($topMenus);
new Chart(document.getElementById('menuChart'), {
    type: 'bar',
    data: {
        labels: menuData.map(d => d.name),
        datasets: [{
            label: 'Terjual',
            data: menuData.map(d => d.order_items_sum_quantity || 0),
            backgroundColor: 'rgba(212,175,122,0.85)',
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

// === Booking per Tipe ===
const typeData = @json($bookingsByType);
new Chart(document.getElementById('roomTypeChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(typeData),
        datasets: [{
            data: Object.values(typeData),
            backgroundColor: ['#d4af7a','#1a1a2e','#4caf50','#ff9800','#9c27b0'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endpush

@endsection