@extends('layouts.app')
@section('title', 'Kelola Staff')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Daftar Staff</div>
        <a href="/users/create" class="btn btn-gold">+ Tambah Staff</a>
    </div>
    <div class="table-responsive">
    <<table id="users-table">>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Role</th>
                <th>Bergabung</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            @if(!$user->hasRole('customer'))
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>
                    @foreach($user->roles as $role)
                    <span class="badge badge-dp">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td style="display:flex;gap:8px">
                    @if($user->id !== auth()->id())
                    <a href="/users/{{ $user->id }}/edit" class="btn btn-outline" style="padding:5px 12px;font-size:12px">Edit</a>
                    <form method="POST" action="/users/{{ $user->id }}" onsubmit="return confirm('Hapus staff ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" style="padding:5px 12px;font-size:12px">Hapus</button>
                    </form>
                    @else
                    <span style="font-size:12px;color:#aaa;font-family:Arial">Akun Anda</span>
                    @endif
                </td>
            </tr>
            @endif
            @empty
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:32px">Belum ada staff</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        responsive: true,
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { next: "→", previous: "←" }
        },
        columnDefs: [{ orderable: false, targets: -1 }]
    });
});
</script>
@endpush
@endsection

