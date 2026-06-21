@extends('layouts.app')
@section('title', 'Kelola Refund')

@section('content')
<div class="card">
    <div class="card-title">Pengajuan Refund</div>
    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Booking</th>
                <th>Tamu</th>
                <th>Check-in</th>
                <th>DP Dibayar</th>
                <th>Refund</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $b)
            @php
                $refundData = json_decode($b->refund_data, true) ?? [];
                $refundAmount = $refundData['refund_amount'] ?? 0;
                $feePercent   = $refundData['fee_percent'] ?? 0;
            @endphp
            <tr>
                <td><strong>{{ $b->booking_code }}</strong></td>
                <td>{{ $b->user->name }}</td>
                <td>{{ $b->check_in->format('d M Y') }}</td>
                <td>Rp {{ number_format($b->dp_amount, 0, ',', '.') }}</td>
                <td>
                    <div style="color:#2e7d32;font-size:13px;font-family:Arial">
                        Rp {{ number_format($refundAmount, 0, ',', '.') }}
                    </div>
                    @if($feePercent > 0)
                    <div style="color:#c62828;font-size:11px;font-family:Arial">Fee {{ $feePercent }}%</div>
                    @endif
                </td>
                <td style="max-width:180px;font-size:13px;color:#555">{{ Str::limit($b->refund_reason, 50) }}</td>
                <td>
                    @if($b->refund_status === 'requested')
                        <span class="badge badge-pending">Pending</span>
                    @elseif($b->refund_status === 'approved')
                        <span class="badge badge-available">Approved</span>
                    @elseif($b->refund_status === 'rejected')
                        <span class="badge badge-pending">Rejected</span>
                    @endif
                </td>
                <td>
                    @if($b->refund_status === 'requested')
                    <div style="display:flex;flex-direction:column;gap:6px">
                        {{-- Detail rekening --}}
                        <div style="font-size:11px;color:#888;font-family:Arial">
                            {{ $refundData['bank_name'] ?? '-' }} — {{ $refundData['account_number'] ?? '-' }}<br>
                            a/n {{ $refundData['account_name'] ?? '-' }}
                        </div>
                        <form method="POST" action="/refunds/{{ $b->id }}/approve"
                            onsubmit="return confirm('Approve refund ini? Dana Rp {{ number_format($refundAmount, 0, ',', '.') }} akan dikembalikan.')">
                            @csrf
                            <button class="btn btn-gold" style="padding:4px 12px;font-size:12px;width:100%">
                                Approve
                            </button>
                        </form>
                        <button onclick="showRejectForm({{ $b->id }})"
                            class="btn btn-danger" style="padding:4px 12px;font-size:12px;width:100%">
                            Tolak
                        </button>
                        {{-- Form reject --}}
                        <div id="reject-form-{{ $b->id }}" style="display:none;margin-top:6px">
                            <form method="POST" action="/refunds/{{ $b->id }}/reject">
                                @csrf
                                <textarea name="reject_reason" rows="2" placeholder="Alasan penolakan..."
                                    style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;font-size:12px;font-family:Arial;margin-bottom:4px"></textarea>
                                <button type="submit" class="btn btn-danger" style="padding:4px 12px;font-size:12px;width:100%">
                                    Konfirmasi Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                    @elseif($b->refund_status === 'approved')
                        <span style="font-size:12px;color:#2e7d32;font-family:Arial">
                            <i class="fas fa-check"></i> Dana dikembalikan
                        </span>
                    @elseif($b->refund_status === 'rejected')
                        <span style="font-size:12px;color:#c62828;font-family:Arial">
                            <i class="fas fa-times"></i> {{ $refundData['reject_reason'] ?? 'Ditolak' }}
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:#aaa;padding:32px">Belum ada pengajuan refund</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

@push('scripts')
<script>
function showRejectForm(id) {
    const form = document.getElementById('reject-form-' + id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
@endsection