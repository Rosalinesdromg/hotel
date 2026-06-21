@extends('layouts.app')
@section('title', 'Housekeeping')

@section('content')

{{-- Stat Cards --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card" style="border-left:4px solid #ffc107">
        <div class="label">Perlu Dibersihkan</div>
        <div class="value" style="color:#f57f17">{{ $stats['dirty'] }}</div>
        <div class="sub">kamar kotor</div>
        <div class="icon"><i class="fas fa-broom" style="color:#ffc107"></i></div>
    </div>
    <div class="stat-card" style="border-left:4px solid #f44336">
        <div class="label">Maintenance</div>
        <div class="value" style="color:#c62828">{{ $stats['maintenance'] }}</div>
        <div class="sub">perlu perbaikan</div>
        <div class="icon"><i class="fas fa-tools" style="color:#f44336"></i></div>
    </div>
    <div class="stat-card" style="border-left:4px solid #4caf50">
        <div class="label">Siap Pakai</div>
        <div class="value" style="color:#2e7d32">{{ $stats['available'] }}</div>
        <div class="sub">kamar tersedia</div>
        <div class="icon"><i class="fas fa-check-circle" style="color:#4caf50"></i></div>
    </div>
    <div class="stat-card" style="border-left:4px solid #ff9800">
        <div class="label">Terisi</div>
        <div class="value" style="color:#e65100">{{ $stats['occupied'] }}</div>
        <div class="sub">tamu menginap</div>
        <div class="icon"><i class="fas fa-user-check" style="color:#ff9800"></i></div>
    </div>
</div>

{{-- Grid Kamar --}}
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Status Semua Kamar</div>
        <div style="font-size:13px;color:#aaa;font-family:Arial">
            Diurutkan: Kotor → Maintenance → Terisi → Tersedia
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
        @foreach($rooms as $room)
        @php
            $colors = [
                'available'   => ['bg'=>'#e8f5e9','border'=>'#4caf50','text'=>'#2e7d32','label'=>'Tersedia'],
                'occupied'    => ['bg'=>'#fff3e0','border'=>'#ff9800','text'=>'#e65100','label'=>'Terisi'],
                'dirty'       => ['bg'=>'#fff8e1','border'=>'#ffc107','text'=>'#f57f17','label'=>'Kotor'],
                'maintenance' => ['bg'=>'#ffebee','border'=>'#f44336','text'=>'#c62828','label'=>'Maintenance'],
            ];
            $c = $colors[$room->status];
        @endphp
        <div style="background:{{ $c['bg'] }};border:2px solid {{ $c['border'] }};border-radius:10px;padding:20px;transition:all 0.2s">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div>
                    <div style="font-size:20px;color:#1a1a2e;font-weight:bold">{{ $room->room_number }}</div>
                    <div style="font-size:12px;color:#888;font-family:Arial;margin-top:2px">{{ $room->roomType->name }}</div>
                </div>
                <div style="background:{{ $c['border'] }};color:#fff;font-size:10px;font-family:Arial;padding:3px 8px;border-radius:20px;letter-spacing:0.5px">
                    {{ $c['label'] }}
                </div>
            </div>

            <div style="font-size:12px;color:#888;font-family:Arial;margin-bottom:12px">
                <i class="fas fa-user" style="margin-right:4px"></i> Maks {{ $room->roomType->capacity }} orang
            </div>

            {{-- Tombol aksi --}}
            <div style="display:flex;flex-direction:column;gap:6px">
                @if($room->status === 'dirty' || $room->status === 'maintenance')
                <form method="POST" action="/housekeeping/{{ $room->id }}/clean">
                    @csrf
                    <button style="width:100%;padding:8px;background:#4caf50;color:#fff;border:none;border-radius:6px;font-size:12px;font-family:Arial;cursor:pointer;transition:all 0.2s"
                        onmouseover="this.style.background='#388e3c'" onmouseout="this.style.background='#4caf50'">
                        <i class="fas fa-check"></i> Tandai Bersih
                    </button>
                </form>
                @endif

                @if($room->status === 'available')
                <form method="POST" action="/housekeeping/{{ $room->id }}/dirty">
                    @csrf
                    <button style="width:100%;padding:8px;background:#ffc107;color:#fff;border:none;border-radius:6px;font-size:12px;font-family:Arial;cursor:pointer;transition:all 0.2s"
                        onmouseover="this.style.background='#f9a825'" onmouseout="this.style.background='#ffc107'">
                        <i class="fas fa-broom"></i> Tandai Kotor
                    </button>
                </form>
                @endif

                @if($room->status !== 'maintenance' && $room->status !== 'occupied')
                <form method="POST" action="/housekeeping/{{ $room->id }}/maintenance"
                    onsubmit="return confirm('Tandai kamar {{ $room->room_number }} sebagai maintenance?')">
                    @csrf
                    <button style="width:100%;padding:8px;background:#f44336;color:#fff;border:none;border-radius:6px;font-size:12px;font-family:Arial;cursor:pointer;transition:all 0.2s"
                        onmouseover="this.style.background='#c62828'" onmouseout="this.style.background='#f44336'">
                        <i class="fas fa-tools"></i> Maintenance
                    </button>
                </form>
                @endif

                @if($room->status === 'occupied')
                <div style="text-align:center;font-size:12px;color:#e65100;font-family:Arial;padding:6px">
                    <i class="fas fa-lock"></i> Tamu sedang menginap
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection