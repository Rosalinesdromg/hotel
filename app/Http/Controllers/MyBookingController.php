<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class MyBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('room.roomType')
            ->latest()->get();
        return view('my-bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Pastikan hanya pemilik yang bisa lihat
        abort_if($booking->user_id !== auth()->id(), 403);
        $booking->load(['room.roomType', 'orders.items.menu']);
        return view('my-bookings.show', compact('booking'));
    }

    public function invoice(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        $booking->load(['user', 'room.roomType', 'orders.items.menu']);
        return view('my-bookings.invoice', compact('booking'));
    }
}