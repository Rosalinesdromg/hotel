<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Form review untuk customer
    public function create(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'checked_out', 403);

        // Cek sudah pernah review belum
        $existing = Review::where('booking_id', $booking->id)->first();
        if ($existing) {
            return redirect('/my-bookings')->with('error', 'Kamu sudah memberikan review untuk booking ini.');
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        Review::create([
            'user_id'    => auth()->id(),
            'booking_id' => $booking->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'is_approved' => false, // butuh approval manager
        ]);

        return redirect('/my-bookings')->with('success', 'Review berhasil dikirim, menunggu persetujuan.');
    }

    // Manager approve/reject review
    public function index()
    {
        $reviews = Review::with(['user', 'booking.room.roomType'])
            ->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review disetujui dan tampil di landing page.');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review ditolak dan dihapus.');
    }
}