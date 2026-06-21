@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')
<div class="card">
    <div class="card-title">Riwayat Aktivitas Sistem</div>
    <div class="table-responsive">
    <table id="audit-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Target</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td style="white-space:nowrap">{{ $log->created_at->format('d M Y, H:i') }}</td>
                <td>{{ $log->user->name ?? '-' }}</td>
                <td><span class="badge badge-dp">{{ $log->action }}</span></td>
                <td style="font-size:12px;color:#aaa">{{ $log->model_type }} #{{ $log->model_id }}</td>
                <td>{{ $log->description }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#aaa;padding:32px">Belum ada aktivitas tercatat</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="margin-top:16px">{{ $logs->links() }}</div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#audit-table').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ada aktivitas",
            paginate: { next: "→", previous: "←" }
        }
    });
});
</script>
@endpush
@endsection

