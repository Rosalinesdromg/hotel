<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCancellation;

class RefundController extends Controller
{
    // Hitung cancellation fee otomatis
    private function calculateRefund(Booking $booking)
    {
        $daysUntilCheckIn = now()->diffInDays($booking->check_in, false);

        if ($daysUntilCheckIn >= 7) {
            // H-7 atau lebih: refund 100%
            return ['percent' => 100, 'fee_percent' => 0, 'label' => 'H-7 atau lebih'];
        } elseif ($daysUntilCheckIn >= 3) {
            // H-3 sampai H-6: refund 75%
            return ['percent' => 75, 'fee_percent' => 25, 'label' => 'H-3 sampai H-6'];
        } elseif ($daysUntilCheckIn >= 1) {
            // H-1 sampai H-2: refund 50%
            return ['percent' => 50, 'fee_percent' => 50, 'label' => 'H-1 sampai H-2'];
        } else {
            // H-0 atau sudah lewat: tidak bisa refund
            return ['percent' => 0, 'fee_percent' => 100, 'label' => 'H-0 (tidak bisa refund)'];
        }
    }

    // Customer: form pengajuan refund
    public function create(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if(!in_array($booking->status, ['confirmed', 'pending']), 403, 'Booking ini tidak bisa direfund.');
        abort_if($booking->refund_status === 'requested', 403, 'Refund sudah diajukan sebelumnya.');

        $refundInfo = $this->calculateRefund($booking);
        $refundAmount = $booking->dp_amount * $refundInfo['percent'] / 100;

        return view('refunds.create', compact('booking', 'refundInfo', 'refundAmount'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        $request->validate([
            'refund_reason'     => 'required|string|min:10',
            'bank_name'         => 'required|string',
            'account_number'    => 'required|string',
            'account_name'      => 'required|string',
        ]);

        $refundInfo   = $this->calculateRefund($booking);
        $refundAmount = $booking->dp_amount * $refundInfo['percent'] / 100;

        $booking->update([
            'refund_status' => 'requested',
            'refund_reason' => $request->refund_reason,
            'refund_data'   => json_encode([
                'bank_name'      => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name'   => $request->account_name,
                'refund_percent' => $refundInfo['percent'],
                'refund_amount'  => $refundAmount,
                'fee_percent'    => $refundInfo['fee_percent'],
                'requested_at'   => now()->toDateTimeString(),
            ]),
        ]);

        return redirect('/my-bookings')->with('success', 'Pengajuan refund berhasil dikirim. Menunggu persetujuan manager.');
    }

    // Manager: daftar semua refund
    public function index()
    {
        $bookings = Booking::whereNotNull('refund_status')
            ->with(['user', 'room.roomType'])
            ->latest()->get();
        return view('refunds.index', compact('bookings'));
    }

    // Manager: approve refund
    public function approve(Booking $booking)
    {
        $booking->update([
            'refund_status' => 'approved',
            'status'        => 'cancelled',
        ]);

        // Kosongkan kamar
        $booking->room->update(['status' => 'available']);

        // Di dalam method approve, setelah $booking->update(...)
        $booking->load(['user', 'room.roomType']);
        Mail::to($booking->user->email)->send(new BookingCancellation($booking));

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'approve refund',
            'model_type'  => 'Booking',
            'model_id'    => $booking->id,
            'description' => auth()->user()->name . ' menyetujui refund booking ' . $booking->booking_code,
        ]);

        return back()->with('success', 'Refund disetujui. Booking dibatalkan dan kamar dikosongkan.');
    }

    // Manager: reject refund
    public function reject(Request $request, Booking $booking)
    {
        $request->validate(['reject_reason' => 'required|string']);

        $refundData = json_decode($booking->refund_data, true) ?? [];
        $refundData['reject_reason'] = $request->reject_reason;

        $booking->update([
            'refund_status' => 'rejected',
            'refund_data'   => json_encode($refundData),
        ]);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'reject refund',
            'model_type'  => 'Booking',
            'model_id'    => $booking->id,
            'description' => auth()->user()->name . ' menolak refund booking ' . $booking->booking_code,
        ]);

        return back()->with('success', 'Refund ditolak.');
    }
}