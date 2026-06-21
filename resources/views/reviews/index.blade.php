@extends('layouts.app')
@section('title', 'Kelola Review')

@section('content')
<div class="card">
    <div class="card-title">Kelola Review Tamu</div>
    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Tamu</th>
                <th>Kamar</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>{{ $review->user->name }}</td>
                <td>{{ $review->booking->room->roomType->name }}</td>
                <td>
                    <span style="color:#d4af7a;font-size:15px">
                        @for($i = 1; $i <= $review->rating; $i++)★@endfor
                    </span>
                </td>
                <td style="max-width:250px;font-size:13px;color:#555">{{ Str::limit($review->comment, 80) }}</td>
                <td>
                    @if($review->is_approved)
                        <span class="badge badge-available">Approved</span>
                    @else
                        <span class="badge badge-pending">Pending</span>
                    @endif
                </td>
                <td>{{ $review->created_at->format('d M Y') }}</td>
                <td style="display:flex;gap:6px">
                    @if(!$review->is_approved)
                    <form method="POST" action="/reviews/{{ $review->id }}/approve">
                        @csrf
                        <button class="btn btn-gold" style="padding:4px 12px;font-size:12px">Approve</button>
                    </form>
                    @endif
                    <form method="POST" action="/reviews/{{ $review->id }}/reject" onsubmit="return confirm('Hapus review ini?')">
                        @csrf
                        <button class="btn btn-danger" style="padding:4px 12px;font-size:12px">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:32px">Belum ada review</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection