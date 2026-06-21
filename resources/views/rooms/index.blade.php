@extends('layouts.app')
@section('title', 'Data Kamar')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Data Kamar</div>
        <a href="/rooms/create" class="btn btn-gold">+ Tambah Kamar</a>
    </div>
    <div class="table-responsive">
    <table id="rooms-table">
        <thead>
            <tr>
                <th>No. Kamar</th>
                <th>Tipe</th>
                <th>Kapasitas</th>
                <th>Harga/malam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
            <tr>
                <td><strong>{{ $room->room_number }}</strong></td>
                <td>{{ $room->roomType->name }}</td>
                <td>{{ $room->roomType->capacity }} orang</td>
                <td>Rp {{ number_format($room->roomType->base_price, 0, ',', '.') }}</td>
                <td>
                    @php
                        $badges = [
                            'available'   => 'badge-available',
                            'occupied'    => 'badge-occupied',
                            'dirty'       => 'badge-dirty',
                            'maintenance' => 'badge-pending',
                        ];
                        $labels = [
                            'available'   => 'Tersedia',
                            'occupied'    => 'Terisi',
                            'dirty'       => 'Kotor',
                            'maintenance' => 'Maintenance',
                        ];
                    @endphp
                    <span class="badge {{ $badges[$room->status] }}">
                        {{ $labels[$room->status] }}
                    </span>
                </td>
                <td style="display:flex;gap:8px">
                    <a href="/rooms/{{ $room->id }}/edit" class="btn btn-outline" style="padding:5px 12px;font-size:12px">Edit</a>
                    <form method="POST" action="/rooms/{{ $room->id }}" onsubmit="return confirm('Hapus kamar ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" style="padding:5px 12px;font-size:12px">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:32px">Belum ada data kamar</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection